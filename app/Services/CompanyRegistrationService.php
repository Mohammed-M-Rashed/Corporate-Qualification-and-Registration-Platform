<?php

namespace App\Services;

use App\Models\Company;
use App\Models\QualificationRequest;
use App\Models\LegalDocument;
use App\Models\TechnicalDocument;
use App\Models\FinancialDocument;
use App\Enums\QualificationRequestStatus;
use App\Enums\ReviewStage;
use App\Enums\ReviewStatus;
use App\Enums\MemberType;
use App\Enums\LegalDocumentType;
use App\Enums\TechnicalDocumentType;
use App\Enums\FinancialDocumentType;
use App\Enums\ExperienceLevel;
use App\Mail\RequestReceivedMail;
use App\Notifications\NewQualificationRequestNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyRegistrationService
{
    public function registerCompany(array $step1Data, array $step2Data, array $step3Data, array $step4Data): QualificationRequest
    {
        return DB::transaction(function () use ($step1Data, $step2Data, $step3Data, $step4Data) {
            // Handle agent document if exists
            $agentDocumentPath = null;
            if (!empty($step1Data['is_agent']) && isset($step1Data['agent_document_file'])) {
                $agentFile = $step1Data['agent_document_file'];
                
                if (is_array($agentFile) && isset($agentFile['path'])) {
                    // File was stored temporarily, move it to final location
                    $tempPath = $agentFile['path'];
                    $newPath = 'agent_documents/' . basename($tempPath);
                    Storage::disk('public')->move($tempPath, $newPath);
                    $agentDocumentPath = $newPath;
                } else {
                    // Direct file upload
                    $path = $agentFile->store('agent_documents', 'public');
                    $agentDocumentPath = $path;
                }
            }

            // Create company
            $company = Company::create([
                'name' => $step1Data['name'],
                'city_id' => $step1Data['city_id'],
                'commercial_register_number' => $step2Data['commercial_register_number'],
                'commercial_register_start_date' => $step2Data['commercial_register_start_date'],
                'commercial_register_end_date' => $step2Data['commercial_register_end_date'],
                'commercial_register_date' => $step2Data['commercial_register_date'] ?? null,
                'email' => $step1Data['email'],
                'phone' => $step1Data['phone'],
                'address' => $step1Data['address'],
                'is_agent' => !empty($step1Data['is_agent']),
                'agent_document_path' => $agentDocumentPath,
                'is_active' => true,
            ]);

            // Attach activity to company
            if (isset($step1Data['activity_id'])) {
                $company->activities()->attach($step1Data['activity_id']);
            }

            // Generate unique request number
            $requestNumber = 'REQ-' . strtoupper(Str::random(10));

            // Create qualification request
            $request = QualificationRequest::create([
                'company_id' => $company->id,
                'request_number' => $requestNumber,
                'status' => QualificationRequestStatus::New,
                'current_review_stage' => ReviewStage::Legal,
                'legal_review_status' => ReviewStatus::Pending,
            ]);

            // Save legal documents
            $this->saveLegalDocuments($request, $step2Data);

            // Save technical documents
            $this->saveTechnicalDocuments($request, $step3Data);

            // Save financial documents
            $this->saveFinancialDocuments($request, $step4Data);

            // Send email
            Mail::to($company->email)->send(new RequestReceivedMail($request));

            // Send notification to legal member only
            $qualificationCommitteeType = \App\Models\CommitteeType::where('name', 'Qualification')->first();
            if ($qualificationCommitteeType) {
                $activeCommittee = \App\Models\Committee::where('committee_type_id', $qualificationCommitteeType->id)
                    ->where('is_active', true)
                    ->first();
                
                if ($activeCommittee) {
                    $legalMember = $activeCommittee->members()->where('member_type', MemberType::Legal)->first();
                    if ($legalMember && $legalMember->user) {
                        $legalMember->user->notify(new NewQualificationRequestNotification($request));
                    }
                }
            }

            return $request;
        });
    }

    protected function saveLegalDocuments(QualificationRequest $request, array $data): void
    {
        $documentTypes = [
            LegalDocumentType::EstablishmentContract->value => 'establishment_contract_file',
            LegalDocumentType::CommercialRegisterExtract->value => 'commercial_register_extract_file',
            LegalDocumentType::ActivityLicense->value => 'activity_license_file',
            LegalDocumentType::ChamberRegistration->value => 'chamber_registration_file',
            LegalDocumentType::TaxCertificate->value => 'tax_certificate_file',
            LegalDocumentType::SocialSecurityCertificate->value => 'social_security_certificate_file',
        ];

        foreach ($documentTypes as $type => $fileKey) {
            if (isset($data[$fileKey]) && $data[$fileKey]) {
                $fileInfo = $data[$fileKey];
                
                // Handle both file objects and stored file info
                if (is_array($fileInfo) && isset($fileInfo['path'])) {
                    // File was stored temporarily, move it to final location
                    $tempPath = $fileInfo['path'];
                    $newPath = 'legal_documents/' . basename($tempPath);
                    Storage::disk('public')->move($tempPath, $newPath);
                    
                    LegalDocument::create([
                        'qualification_request_id' => $request->id,
                        'document_type' => LegalDocumentType::from($type),
                        'file_path' => $newPath,
                        'file_name' => $fileInfo['original_name'],
                        'file_size' => $fileInfo['size'],
                    ]);
                } else {
                    // Direct file upload (fallback)
                    $file = $fileInfo;
                    $path = $file->store('legal_documents', 'public');
                    
                    LegalDocument::create([
                        'qualification_request_id' => $request->id,
                        'document_type' => LegalDocumentType::from($type),
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        }
    }

    protected function saveTechnicalDocuments(QualificationRequest $request, array $data): void
    {
        $documentTypes = [
            TechnicalDocumentType::CompletedProjects->value => 'completed_projects_file',
            TechnicalDocumentType::TechnicalStaff->value => 'technical_staff_file',
            TechnicalDocumentType::QualityCertificates->value => 'quality_certificates_file',
        ];

        foreach ($documentTypes as $type => $fileKey) {
            if (isset($data[$fileKey]) && $data[$fileKey]) {
                $fileInfo = $data[$fileKey];
                
                // Handle both file objects and stored file info
                if (is_array($fileInfo) && isset($fileInfo['path'])) {
                    // File was stored temporarily, move it to final location
                    $tempPath = $fileInfo['path'];
                    $newPath = 'technical_documents/' . basename($tempPath);
                    Storage::disk('public')->move($tempPath, $newPath);
                    
                    TechnicalDocument::create([
                        'qualification_request_id' => $request->id,
                        'experience_level' => ExperienceLevel::from($data['experience_level']),
                        'document_type' => TechnicalDocumentType::from($type),
                        'file_path' => $newPath,
                        'file_name' => $fileInfo['original_name'],
                        'file_size' => $fileInfo['size'],
                    ]);
                } else {
                    // Direct file upload (fallback)
                    $file = $fileInfo;
                    $path = $file->store('technical_documents', 'public');
                    
                    TechnicalDocument::create([
                        'qualification_request_id' => $request->id,
                        'experience_level' => ExperienceLevel::from($data['experience_level']),
                        'document_type' => TechnicalDocumentType::from($type),
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        }
    }

    protected function saveFinancialDocuments(QualificationRequest $request, array $data): void
    {
        $documentTypes = [
            FinancialDocumentType::FinancialStatements->value => 'financial_statements_file',
            FinancialDocumentType::SolvencyCertificate->value => 'solvency_certificate_file',
        ];

        foreach ($documentTypes as $type => $fileKey) {
            if (isset($data[$fileKey]) && $data[$fileKey]) {
                $fileInfo = $data[$fileKey];
                
                // Handle both file objects and stored file info
                if (is_array($fileInfo) && isset($fileInfo['path'])) {
                    // File was stored temporarily, move it to final location
                    $tempPath = $fileInfo['path'];
                    $newPath = 'financial_documents/' . basename($tempPath);
                    Storage::disk('public')->move($tempPath, $newPath);
                    
                    FinancialDocument::create([
                        'qualification_request_id' => $request->id,
                        'document_type' => FinancialDocumentType::from($type),
                        'file_path' => $newPath,
                        'file_name' => $fileInfo['original_name'],
                        'file_size' => $fileInfo['size'],
                        'technical_notes' => $data['technical_notes'] ?? null,
                    ]);
                } else {
                    // Direct file upload (fallback)
                    $file = $fileInfo;
                    $path = $file->store('financial_documents', 'public');
                    
                    FinancialDocument::create([
                        'qualification_request_id' => $request->id,
                        'document_type' => FinancialDocumentType::from($type),
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'technical_notes' => $data['technical_notes'] ?? null,
                    ]);
                }
            }
        }
    }
}
