<?php

namespace App\Http\Controllers;

use App\Services\CompanyRegistrationService;
use App\Rules\LibyanPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyRegistrationController extends Controller
{
    protected $registrationService;

    public function __construct(CompanyRegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function step1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'activity_id' => 'required|exists:company_activities,id',
            'city_id' => 'required|exists:cities,id',
            'email' => 'required|email',
            'phone' => ['required', new LibyanPhoneNumber()],
            'address' => 'required|string',
            'is_agent' => 'nullable',
            'agent_document_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle agent document file
        $validated = $validator->validated();
        
        // Convert is_agent to boolean
        $isAgent = $request->has('is_agent') && ($request->input('is_agent') === '1' || $request->input('is_agent') === true || $request->input('is_agent') === 'true');
        $validated['is_agent'] = $isAgent;
        
        // Validate agent document if is_agent is true
        if ($isAgent && !$request->hasFile('agent_document_file')) {
            return response()->json(['errors' => ['agent_document_file' => ['مستند الوكالة مطلوب إذا كنت وكيل معتمد']]], 422);
        }
        
        if ($request->hasFile('agent_document_file')) {
            $file = $request->file('agent_document_file');
            $path = $file->store('temp/registration', 'public');
            $validated['agent_document_file'] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ];
        }

        // Store step 1 data in session
        $request->session()->put('registration.step1', $validated);

        return response()->json(['success' => true, 'message' => 'تم حفظ البيانات الأساسية']);
    }

    public function step2(Request $request)
    {
        try {
            // Validate step 1 is completed
            if (!$request->session()->has('registration.step1')) {
                return response()->json(['error' => 'يجب إكمال الخطوة الأولى أولاً'], 400);
            }

            $validator = Validator::make($request->all(), [
                'commercial_register_number' => 'required|string|max:255',
                'commercial_register_start_date' => 'required|date',
                'commercial_register_end_date' => 'required|date|after:commercial_register_start_date',
                'establishment_contract_file' => 'required|file|mimes:pdf|max:10240',
                'commercial_register_extract_file' => 'required|file|mimes:pdf|max:10240',
                'activity_license_file' => 'required|file|mimes:pdf|max:10240',
                'chamber_registration_file' => 'required|file|mimes:pdf|max:10240',
                'tax_certificate_file' => 'required|file|mimes:pdf|max:10240',
                'social_security_certificate_file' => 'required|file|mimes:pdf|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Store files temporarily and save paths
            $validated = $validator->validated();
            $fileData = [];
            
            foreach ($validated as $key => $value) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store('temp/registration', 'public');
                    $fileData[$key] = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                    ];
                } else {
                    $fileData[$key] = $value;
                }
            }
            
            $request->session()->put('registration.step2', $fileData);

            return response()->json(['success' => true, 'message' => 'تم حفظ البيانات القانونية']);
        } catch (\Exception $e) {
            \Log::error('Step 2 error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json(['error' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()], 500);
        }
    }

    public function step3(Request $request)
    {
        try {
            if (!$request->session()->has('registration.step2')) {
                return response()->json(['error' => 'يجب إكمال الخطوة الثانية أولاً'], 400);
            }

            $validator = Validator::make($request->all(), [
                'experience_level' => 'required|in:0-3,4-10,more_than_10',
                'completed_projects_file' => 'required|file|mimes:pdf|max:10240',
                'technical_staff_file' => 'required|file|mimes:pdf|max:10240',
                'quality_certificates_file' => 'required|file|mimes:pdf|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Store files temporarily and save paths
            $validated = $validator->validated();
            $fileData = [];
            
            foreach ($validated as $key => $value) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store('temp/registration', 'public');
                    $fileData[$key] = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                    ];
                } else {
                    $fileData[$key] = $value;
                }
            }
            
            $request->session()->put('registration.step3', $fileData);

            return response()->json(['success' => true, 'message' => 'تم حفظ البيانات الفنية']);
        } catch (\Exception $e) {
            \Log::error('Step 3 error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json(['error' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()], 500);
        }
    }

    public function step4(Request $request)
    {
        try {
            if (!$request->session()->has('registration.step3')) {
                return response()->json(['error' => 'يجب إكمال الخطوة الثالثة أولاً'], 400);
            }

            $validator = Validator::make($request->all(), [
                'financial_statements_file' => 'required|file|mimes:pdf|max:10240',
                'solvency_certificate_file' => 'required|file|mimes:pdf|max:10240',
                'technical_notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Store files temporarily and save paths
            $validated = $validator->validated();
            $fileData = [];
            
            foreach ($validated as $key => $value) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store('temp/registration', 'public');
                    $fileData[$key] = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                    ];
                } else {
                    $fileData[$key] = $value;
                }
            }
            
            $request->session()->put('registration.step4', $fileData);

            return response()->json(['success' => true, 'message' => 'تم حفظ البيانات المالية']);
        } catch (\Exception $e) {
            \Log::error('Step 4 error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json(['error' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()], 500);
        }
    }

    public function complete(Request $request)
    {
        try {
            // Get data from session or request
            $step1 = $request->session()->get('registration.step1') ?? [
                'name' => $request->input('name'),
                'activity_id' => $request->input('activity_id'),
                'city_id' => $request->input('city_id'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'is_agent' => $request->input('is_agent', false),
                'agent_document_file' => $request->file('agent_document_file'),
            ];

            $step2 = $request->session()->get('registration.step2') ?? [
                'commercial_register_number' => $request->input('commercial_register_number'),
                'commercial_register_start_date' => $request->input('commercial_register_start_date'),
                'commercial_register_end_date' => $request->input('commercial_register_end_date'),
                'establishment_contract_file' => $request->file('establishment_contract_file'),
                'commercial_register_extract_file' => $request->file('commercial_register_extract_file'),
                'activity_license_file' => $request->file('activity_license_file'),
                'chamber_registration_file' => $request->file('chamber_registration_file'),
                'tax_certificate_file' => $request->file('tax_certificate_file'),
                'social_security_certificate_file' => $request->file('social_security_certificate_file'),
            ];

            $step3 = $request->session()->get('registration.step3') ?? [
                'experience_level' => $request->input('experience_level'),
                'completed_projects_file' => $request->file('completed_projects_file'),
                'technical_staff_file' => $request->file('technical_staff_file'),
                'quality_certificates_file' => $request->file('quality_certificates_file'),
            ];

            $step4 = $request->session()->get('registration.step4') ?? [
                'financial_statements_file' => $request->file('financial_statements_file'),
                'solvency_certificate_file' => $request->file('solvency_certificate_file'),
                'technical_notes' => $request->input('technical_notes'),
            ];

            // Always validate step1 data, especially uniqueness checks
            $step1Validator = Validator::make($step1, [
                'name' => 'required|string|max:255',
                'activity_id' => 'required|exists:company_activities,id',
                'city_id' => 'required|exists:cities,id',
                'email' => 'required|email|unique:companies',
                'phone' => ['required', new LibyanPhoneNumber()],
                'address' => 'required|string',
                'is_agent' => 'nullable|boolean',
                'agent_document_file' => 'required_if:is_agent,true|file|mimes:pdf|max:10240',
            ]);
            
            if ($step1Validator->fails()) {
                // Format errors for frontend
                $errors = [];
                foreach ($step1Validator->errors()->messages() as $key => $messages) {
                    $errors[$key] = $messages;
                }
                return response()->json(['errors' => $errors], 422);
            }

            if (!$request->session()->has('registration.step2')) {
                $validator = Validator::make($request->all(), [
                    'commercial_register_number' => 'required|string|max:255',
                    'commercial_register_start_date' => 'required|date',
                    'commercial_register_end_date' => 'required|date|after:commercial_register_start_date',
                    'establishment_contract_file' => 'required|file|mimes:pdf|max:10240',
                    'commercial_register_extract_file' => 'required|file|mimes:pdf|max:10240',
                    'activity_license_file' => 'required|file|mimes:pdf|max:10240',
                    'chamber_registration_file' => 'required|file|mimes:pdf|max:10240',
                    'tax_certificate_file' => 'required|file|mimes:pdf|max:10240',
                    'social_security_certificate_file' => 'required|file|mimes:pdf|max:10240',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
            }

            if (!$request->session()->has('registration.step3')) {
                $validator = Validator::make($request->all(), [
                    'experience_level' => 'required|in:0-3,4-10,more_than_10',
                    'completed_projects_file' => 'required|file|mimes:pdf|max:10240',
                    'technical_staff_file' => 'required|file|mimes:pdf|max:10240',
                    'quality_certificates_file' => 'required|file|mimes:pdf|max:10240',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
            }

            if (!$request->session()->has('registration.step4')) {
                $validator = Validator::make($request->all(), [
                    'financial_statements_file' => 'required|file|mimes:pdf|max:10240',
                    'solvency_certificate_file' => 'required|file|mimes:pdf|max:10240',
                    'technical_notes' => 'nullable|string',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
            }

            $requestObj = $this->registrationService->registerCompany($step1, $step2, $step3, $step4);

            // Clear session
            $request->session()->forget('registration');

            return response()->json([
                'success' => true,
                'message' => 'تم التسجيل بنجاح',
                'request_number' => $requestObj->request_number,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Company registration database error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check for duplicate entry errors
            if ($e->getCode() == 23000) {
                $errorMessage = $e->getMessage();
                $errors = [];
                
                if (str_contains($errorMessage, 'companies_email_unique') || str_contains($errorMessage, 'email')) {
                    $errors['email'] = ['البريد الإلكتروني مستخدم بالفعل. يرجى استخدام بريد إلكتروني آخر.'];
                } else {
                    $errors['general'] = ['حدث خطأ في قاعدة البيانات. يرجى المحاولة مرة أخرى.'];
                }
                
                return response()->json([
                    'success' => false,
                    'errors' => $errors,
                    'error' => 'حدث خطأ أثناء التسجيل'
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Company registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage()
            ], 500);
        }
    }
}
