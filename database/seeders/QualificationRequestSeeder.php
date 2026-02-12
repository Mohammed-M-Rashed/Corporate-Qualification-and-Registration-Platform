<?php

namespace Database\Seeders;

use App\Models\QualificationRequest;
use App\Models\Company;
use App\Models\User;
use App\Enums\QualificationRequestStatus;
use App\Enums\ReviewStage;
use App\Enums\ReviewStatus;
use App\Enums\LegalDocumentType;
use App\Enums\TechnicalDocumentType;
use App\Enums\FinancialDocumentType;
use App\Enums\ExperienceLevel;
use App\Models\LegalDocument;
use App\Models\TechnicalDocument;
use App\Models\FinancialDocument;
use App\Models\RejectionReason;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QualificationRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $committeeMembers = User::whereHas('roles', function($q) {
            $q->where('name', 'Committee Member');
        })->get();

        if ($companies->isEmpty() || $committeeMembers->isEmpty()) {
            return;
        }

        $statuses = [
            QualificationRequestStatus::New,
            QualificationRequestStatus::UnderReview,
            QualificationRequestStatus::Approved,
            QualificationRequestStatus::Rejected,
        ];

        foreach ($companies->take(5) as $index => $company) {
            $status = $statuses[$index % count($statuses)];
            $requestNumber = 'REQ-' . strtoupper(Str::random(10));

            $requestData = [
                'company_id' => $company->id,
                'status' => $status,
                'approved_by' => $status === QualificationRequestStatus::Approved
                    ? $committeeMembers->random()->id 
                    : null,
                'approved_at' => $status === QualificationRequestStatus::Approved
                    ? now()->subDays(rand(1, 15))
                    : null,
            ];

            // إضافة بيانات المراحل حسب الحالة
            if ($status === QualificationRequestStatus::New) {
                $requestData['current_review_stage'] = ReviewStage::Legal;
                $requestData['legal_review_status'] = ReviewStatus::Pending;
            } elseif ($status === QualificationRequestStatus::UnderReview) {
                $requestData['current_review_stage'] = ReviewStage::Technical;
                $requestData['legal_review_status'] = ReviewStatus::Approved;
                $requestData['legal_reviewed_by'] = $committeeMembers->random()->id;
                $requestData['legal_reviewed_at'] = now()->subDays(rand(1, 30));
                $requestData['technical_review_status'] = ReviewStatus::Pending;
            } elseif ($status === QualificationRequestStatus::Rejected) {
                $requestData['current_review_stage'] = ReviewStage::Completed;
                $requestData['legal_review_status'] = ReviewStatus::Rejected;
                $requestData['legal_reviewed_by'] = $committeeMembers->random()->id;
                $requestData['legal_reviewed_at'] = now()->subDays(rand(1, 30));
            } elseif ($status === QualificationRequestStatus::Approved) {
                $requestData['current_review_stage'] = ReviewStage::Completed;
                $requestData['legal_review_status'] = ReviewStatus::Approved;
                $requestData['technical_review_status'] = ReviewStatus::Approved;
                $requestData['financial_review_status'] = ReviewStatus::Approved;
            }

            $request = QualificationRequest::firstOrCreate(
                ['request_number' => $requestNumber],
                $requestData
            );

            // ربط أسباب الرفض إذا كان الطلب مرفوضاً
            if ($status === QualificationRequestStatus::Rejected) {
                $rejectionReason = RejectionReason::where('is_active', true)->first();
                if ($rejectionReason) {
                    $request->rejectionReasons()->sync([$rejectionReason->id]);
                }
            }

            // Create sample legal documents
            $this->createLegalDocuments($request);
            
            // Create sample technical documents
            $this->createTechnicalDocuments($request);
            
            // Create sample financial documents
            $this->createFinancialDocuments($request);
        }
    }

    protected function createLegalDocuments(QualificationRequest $request): void
    {
        $types = [
            LegalDocumentType::EstablishmentContract,
            LegalDocumentType::CommercialRegisterExtract,
            LegalDocumentType::ActivityLicense,
            LegalDocumentType::ChamberRegistration,
            LegalDocumentType::TaxCertificate,
            LegalDocumentType::SocialSecurityCertificate,
        ];

        foreach ($types as $type) {
            LegalDocument::firstOrCreate(
                [
                    'qualification_request_id' => $request->id,
                    'document_type' => $type,
                ],
                [
                    'file_path' => 'legal_documents/sample_' . Str::random(10) . '.pdf',
                    'file_name' => $type->value . '.pdf',
                    'file_size' => rand(100000, 5000000), // 100KB to 5MB
                ]
            );
        }
    }

    protected function createTechnicalDocuments(QualificationRequest $request): void
    {
        $experienceLevels = [
            ExperienceLevel::ZeroToThree,
            ExperienceLevel::FourToTen,
            ExperienceLevel::MoreThanTen,
        ];

        $types = [
            TechnicalDocumentType::CompletedProjects,
            TechnicalDocumentType::TechnicalStaff,
            TechnicalDocumentType::QualityCertificates,
        ];

        $experienceLevel = $experienceLevels[array_rand($experienceLevels)];

        foreach ($types as $type) {
            TechnicalDocument::firstOrCreate(
                [
                    'qualification_request_id' => $request->id,
                    'document_type' => $type,
                ],
                [
                    'experience_level' => $experienceLevel,
                    'file_path' => 'technical_documents/sample_' . Str::random(10) . '.pdf',
                    'file_name' => $type->value . '.pdf',
                    'file_size' => rand(200000, 8000000), // 200KB to 8MB
                ]
            );
        }
    }

    protected function createFinancialDocuments(QualificationRequest $request): void
    {
        $types = [
            FinancialDocumentType::FinancialStatements,
            FinancialDocumentType::SolvencyCertificate,
        ];

        foreach ($types as $type) {
            FinancialDocument::firstOrCreate(
                [
                    'qualification_request_id' => $request->id,
                    'document_type' => $type,
                ],
                [
                    'file_path' => 'financial_documents/sample_' . Str::random(10) . '.pdf',
                    'file_name' => $type->value . '.pdf',
                    'file_size' => rand(150000, 6000000), // 150KB to 6MB
                    'technical_notes' => $type === FinancialDocumentType::FinancialStatements
                        ? 'البيانات المالية للسنوات الثلاث الماضية'
                        : null,
                ]
            );
        }
    }
}

