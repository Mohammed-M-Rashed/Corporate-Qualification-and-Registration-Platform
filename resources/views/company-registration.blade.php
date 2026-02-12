<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل شركة - نظام تأهيل أدوات التنفيذ المحلية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', 'Arial', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .step::after {
            content: '';
            position: absolute;
            top: 25px;
            right: 50%;
            width: 100%;
            height: 3px;
            background: #e5e7eb;
            z-index: -1;
            transition: all 0.3s ease;
        }
        .step:first-child::after {
            display: none;
        }
        .step.completed::after {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }
        .step.active .step-circle {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.4);
        }
        .step.completed .step-circle {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }
        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s ease;
            border: 3px solid white;
        }
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .form-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.4);
        }
        .file-upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        .file-upload-area:hover {
            border-color: #007bff;
            background: #f0f7ff;
        }
        .file-upload-area.has-file {
            border-color: #10b981;
            background: #f0fdf4;
        }
        .file-info {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #e0f2fe;
            border-radius: 5px;
            font-size: 0.875rem;
            color: #0369a1;
        }
        .file-info.has-file {
            background: #d1fae5;
            color: #065f46;
        }
        .file-loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 123, 255, 0.3);
            border-top-color: #007bff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .file-upload-area.loading {
            pointer-events: none;
            opacity: 0.7;
        }
    </style>
</head>
<body class="min-h-screen py-12" x-data="registrationForm()">
    <div class="container mx-auto px-6 max-w-5xl">
        <div class="form-container">
            <div class="form-header">
                <h1 class="text-3xl font-bold mb-2">تسجيل شركة جديدة</h1>
                <p class="text-blue-100">املأ جميع البيانات المطلوبة بدقة</p>
            </div>
            
            <div class="p-8">
                <!-- Step Indicator -->
                <div class="step-indicator mb-8">
                    <div class="step" :class="{ 'active': currentStep === 1, 'completed': currentStep > 1 }">
                        <div class="step-circle">1</div>
                        <div class="text-sm font-semibold text-gray-700">البيانات الأساسية</div>
                    </div>
                    <div class="step" :class="{ 'active': currentStep === 2, 'completed': currentStep > 2 }">
                        <div class="step-circle">2</div>
                        <div class="text-sm font-semibold text-gray-700">البيانات القانونية</div>
                    </div>
                    <div class="step" :class="{ 'active': currentStep === 3, 'completed': currentStep > 3 }">
                        <div class="step-circle">3</div>
                        <div class="text-sm font-semibold text-gray-700">البيانات الفنية</div>
                    </div>
                    <div class="step" :class="{ 'active': currentStep === 4, 'completed': currentStep > 4 }">
                        <div class="step-circle">4</div>
                        <div class="text-sm font-semibold text-gray-700">البيانات المالية</div>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm">
                    @csrf

                    <!-- Step 1: Basic Information -->
                    <div x-show="currentStep === 1" class="space-y-6" x-transition>
                        <h2 class="text-2xl font-bold mb-6 text-blue-600 border-b-2 border-blue-200 pb-3">البيانات الأساسية</h2>
                        
                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">اسم الشركة *</label>
                            <input type="text" x-model="formData.step1.name" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                   required>
                            <span x-show="errors.step1?.name" class="text-red-500 text-sm mt-1 block" x-text="errors.step1?.name"></span>
                        </div>


                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">البريد الإلكتروني *</label>
                            <input type="email" x-model="formData.step1.email" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                   required>
                            <span x-show="errors.step1?.email" class="text-red-500 text-sm mt-1 block" x-text="errors.step1?.email"></span>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">رقم الهاتف * <span class="text-sm text-gray-500">(091/092/093/094 + 7 أرقام)</span></label>
                            <input type="text" x-model="formData.step1.phone" 
                                   pattern="^(091|092|093|094)\d{7}$"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                   required>
                            <span x-show="errors.step1?.phone" class="text-red-500 text-sm mt-1 block" x-text="errors.step1?.phone"></span>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">العنوان *</label>
                            <textarea x-model="formData.step1.address" 
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                      rows="3" required></textarea>
                            <span x-show="errors.step1?.address" class="text-red-500 text-sm mt-1 block" x-text="errors.step1?.address"></span>
                        </div>

                        <button type="button" @click="nextStep(1)" 
                                class="w-full btn-primary text-white px-6 py-4 rounded-lg font-semibold text-lg">
                            التالي →
                        </button>
                    </div>

                    <!-- Step 2: Legal Documents -->
                    <div x-show="currentStep === 2" class="space-y-6" x-transition>
                        <h2 class="text-2xl font-bold mb-6 text-blue-600 border-b-2 border-blue-200 pb-3">البيانات القانونية (PDF فقط)</h2>
                        
                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">رقم السجل التجاري *</label>
                            <input type="text" x-model="formData.step2.commercial_register_number" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                   required>
                            <span x-show="errors.commercial_register_number" class="text-red-500 text-sm mt-1 block" x-text="errors.commercial_register_number?.[0]"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 mb-2 font-semibold">تاريخ بداية السجل التجاري *</label>
                                <input type="date" x-model="formData.step2.commercial_register_start_date" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                       required>
                                <span x-show="errors.commercial_register_start_date" class="text-red-500 text-sm mt-1 block" x-text="errors.commercial_register_start_date?.[0]"></span>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2 font-semibold">تاريخ نهاية السجل التجاري *</label>
                                <input type="date" x-model="formData.step2.commercial_register_end_date" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                       required>
                                <span x-show="errors.commercial_register_end_date" class="text-red-500 text-sm mt-1 block" x-text="errors.commercial_register_end_date?.[0]"></span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 mb-2 font-semibold">عقد التأسيس والنظام الأساسي (PDF) *</label>
                                <div class="file-upload-area" 
                                     :class="{ 'has-file': formData.step2.establishment_contract_file, 'loading': loadingFiles['step2_establishment_contract_file'] }"
                                     x-data="{ fileId: 'step2_establishment_contract_file' }">
                                    <input type="file" @change="handleFile($event, 'step2', 'establishment_contract_file', 'step2_establishment_contract_file')" 
                                           accept=".pdf"
                                           class="hidden" id="file1" required>
                                    <label for="file1" class="cursor-pointer block">
                                        <span class="text-blue-600 font-semibold" x-show="!formData.step2.establishment_contract_file && !loadingFiles['step2_establishment_contract_file']">📄 اختر ملف PDF</span>
                                        <span class="text-blue-600 font-semibold" x-show="loadingFiles['step2_establishment_contract_file']">
                                            جاري معالجة الملف...
                                            <span class="file-loading"></span>
                                        </span>
                                        <span class="text-green-600 font-semibold" x-show="formData.step2.establishment_contract_file && !loadingFiles['step2_establishment_contract_file']">✓ تم اختيار الملف</span>
                                    </label>
                                    <div class="file-info" :class="{ 'has-file': formData.step2.establishment_contract_file }" x-show="formData.step2.establishment_contract_file && !loadingFiles['step2_establishment_contract_file']">
                                        <span x-text="formData.step2.establishment_contract_file?.name"></span>
                                        <span class="text-xs" x-text="' (' + formatFileSize(formData.step2.establishment_contract_file?.size) + ')'"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2 font-semibold">مستخرج حديث من السجل التجاري (PDF) *</label>
                                <div class="file-upload-area" 
                                     :class="{ 'has-file': formData.step2.commercial_register_extract_file, 'loading': loadingFiles['step2_commercial_register_extract_file'] }">
                                    <input type="file" @change="handleFile($event, 'step2', 'commercial_register_extract_file', 'step2_commercial_register_extract_file')" 
                                           accept=".pdf"
                                           class="hidden" id="file2" required>
                                    <label for="file2" class="cursor-pointer block">
                                        <span class="text-blue-600 font-semibold" x-show="!formData.step2.commercial_register_extract_file && !loadingFiles['step2_commercial_register_extract_file']">📄 اختر ملف PDF</span>
                                        <span class="text-blue-600 font-semibold" x-show="loadingFiles['step2_commercial_register_extract_file']">
                                            جاري معالجة الملف...
                                            <span class="file-loading"></span>
                                        </span>
                                        <span class="text-green-600 font-semibold" x-show="formData.step2.commercial_register_extract_file && !loadingFiles['step2_commercial_register_extract_file']">✓ تم اختيار الملف</span>
                                    </label>
                                    <div class="file-info" :class="{ 'has-file': formData.step2.commercial_register_extract_file }" x-show="formData.step2.commercial_register_extract_file && !loadingFiles['step2_commercial_register_extract_file']">
                                        <span x-text="formData.step2.commercial_register_extract_file?.name"></span>
                                        <span class="text-xs" x-text="' (' + formatFileSize(formData.step2.commercial_register_extract_file?.size) + ')'"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2 font-semibold">ترخيص مزاولة النشاط (PDF) *</label>
                                <div class="file-upload-area" 
                                     :class="{ 'has-file': formData.step2.activity_license_file, 'loading': loadingFiles['step2_activity_license_file'] }">
                                    <input type="file" @change="handleFile($event, 'step2', 'activity_license_file', 'step2_activity_license_file')" 
                                           accept=".pdf"
                                           class="hidden" id="file3" required>
                                    <label for="file3" class="cursor-pointer block">
                                        <span class="text-blue-600 font-semibold" x-show="!formData.step2.activity_license_file && !loadingFiles['step2_activity_license_file']">📄 اختر ملف PDF</span>
                                        <span class="text-blue-600 font-semibold" x-show="loadingFiles['step2_activity_license_file']">
                                            جاري معالجة الملف...
                                            <span class="file-loading"></span>
                                        </span>
                                        <span class="text-green-600 font-semibold" x-show="formData.step2.activity_license_file && !loadingFiles['step2_activity_license_file']">✓ تم اختيار الملف</span>
                                    </label>
                                    <div class="file-info" :class="{ 'has-file': formData.step2.activity_license_file }" x-show="formData.step2.activity_license_file && !loadingFiles['step2_activity_license_file']">
                                        <span x-text="formData.step2.activity_license_file?.name"></span>
                                        <span class="text-xs" x-text="' (' + formatFileSize(formData.step2.activity_license_file?.size) + ')'"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2 font-semibold">شهادة قيد بالغرفة التجارية (PDF) *</label>
                                <div class="file-upload-area" 
                                     :class="{ 'has-file': formData.step2.chamber_registration_file, 'loading': loadingFiles['step2_chamber_registration_file'] }">
                                    <input type="file" @change="handleFile($event, 'step2', 'chamber_registration_file', 'step2_chamber_registration_file')" 
                                           accept=".pdf"
                                           class="hidden" id="file4" required>
                                    <label for="file4" class="cursor-pointer block">
                                        <span class="text-blue-600 font-semibold" x-show="!formData.step2.chamber_registration_file && !loadingFiles['step2_chamber_registration_file']">📄 اختر ملف PDF</span>
                                        <span class="text-blue-600 font-semibold" x-show="loadingFiles['step2_chamber_registration_file']">
                                            جاري معالجة الملف...
                                            <span class="file-loading"></span>
                                        </span>
                                        <span class="text-green-600 font-semibold" x-show="formData.step2.chamber_registration_file && !loadingFiles['step2_chamber_registration_file']">✓ تم اختيار الملف</span>
                                    </label>
                                    <div class="file-info" :class="{ 'has-file': formData.step2.chamber_registration_file }" x-show="formData.step2.chamber_registration_file && !loadingFiles['step2_chamber_registration_file']">
                                        <span x-text="formData.step2.chamber_registration_file?.name"></span>
                                        <span class="text-xs" x-text="' (' + formatFileSize(formData.step2.chamber_registration_file?.size) + ')'"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2 font-semibold">شهادة الضريبة (PDF) *</label>
                                <div class="file-upload-area" 
                                     :class="{ 'has-file': formData.step2.tax_certificate_file, 'loading': loadingFiles['step2_tax_certificate_file'] }">
                                    <input type="file" @change="handleFile($event, 'step2', 'tax_certificate_file', 'step2_tax_certificate_file')" 
                                           accept=".pdf"
                                           class="hidden" id="file5" required>
                                    <label for="file5" class="cursor-pointer block">
                                        <span class="text-blue-600 font-semibold" x-show="!formData.step2.tax_certificate_file && !loadingFiles['step2_tax_certificate_file']">📄 اختر ملف PDF</span>
                                        <span class="text-blue-600 font-semibold" x-show="loadingFiles['step2_tax_certificate_file']">
                                            جاري معالجة الملف...
                                            <span class="file-loading"></span>
                                        </span>
                                        <span class="text-green-600 font-semibold" x-show="formData.step2.tax_certificate_file && !loadingFiles['step2_tax_certificate_file']">✓ تم اختيار الملف</span>
                                    </label>
                                    <div class="file-info" :class="{ 'has-file': formData.step2.tax_certificate_file }" x-show="formData.step2.tax_certificate_file && !loadingFiles['step2_tax_certificate_file']">
                                        <span x-text="formData.step2.tax_certificate_file?.name"></span>
                                        <span class="text-xs" x-text="' (' + formatFileSize(formData.step2.tax_certificate_file?.size) + ')'"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2 font-semibold">شهادة الضمان الاجتماعي (PDF) *</label>
                                <div class="file-upload-area" 
                                     :class="{ 'has-file': formData.step2.social_security_certificate_file, 'loading': loadingFiles['step2_social_security_certificate_file'] }">
                                    <input type="file" @change="handleFile($event, 'step2', 'social_security_certificate_file', 'step2_social_security_certificate_file')" 
                                           accept=".pdf"
                                           class="hidden" id="file6" required>
                                    <label for="file6" class="cursor-pointer block">
                                        <span class="text-blue-600 font-semibold" x-show="!formData.step2.social_security_certificate_file && !loadingFiles['step2_social_security_certificate_file']">📄 اختر ملف PDF</span>
                                        <span class="text-blue-600 font-semibold" x-show="loadingFiles['step2_social_security_certificate_file']">
                                            جاري معالجة الملف...
                                            <span class="file-loading"></span>
                                        </span>
                                        <span class="text-green-600 font-semibold" x-show="formData.step2.social_security_certificate_file && !loadingFiles['step2_social_security_certificate_file']">✓ تم اختيار الملف</span>
                                    </label>
                                    <div class="file-info" :class="{ 'has-file': formData.step2.social_security_certificate_file }" x-show="formData.step2.social_security_certificate_file && !loadingFiles['step2_social_security_certificate_file']">
                                        <span x-text="formData.step2.social_security_certificate_file?.name"></span>
                                        <span class="text-xs" x-text="' (' + formatFileSize(formData.step2.social_security_certificate_file?.size) + ')'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" @click="prevStep()" 
                                    class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 font-semibold transition">
                                ← السابق
                            </button>
                            <button type="button" @click="nextStep(2)" 
                                    class="flex-1 btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                                التالي →
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Technical Documents -->
                    <div x-show="currentStep === 3" class="space-y-6" x-transition>
                        <h2 class="text-2xl font-bold mb-6 text-blue-600 border-b-2 border-blue-200 pb-3">البيانات الفنية (PDF فقط)</h2>
                        
                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">خبرة الشركة *</label>
                            <select x-model="formData.step3.experience_level" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                    required>
                                <option value="">اختر مستوى الخبرة...</option>
                                <option value="0-3">0-3 سنوات</option>
                                <option value="4-10">4-10 سنوات</option>
                                <option value="more_than_10">أكثر من 10 سنوات</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">ملف المشاريع المنفذة (PDF) *</label>
                            <div class="file-upload-area" 
                                 :class="{ 'has-file': formData.step3.completed_projects_file, 'loading': loadingFiles['step3_completed_projects_file'] }">
                                <input type="file" @change="handleFile($event, 'step3', 'completed_projects_file', 'step3_completed_projects_file')" 
                                       accept=".pdf"
                                       class="hidden" id="file7" required>
                                <label for="file7" class="cursor-pointer block">
                                    <span class="text-blue-600 font-semibold" x-show="!formData.step3.completed_projects_file && !loadingFiles['step3_completed_projects_file']">📄 اختر ملف PDF</span>
                                    <span class="text-blue-600 font-semibold" x-show="loadingFiles['step3_completed_projects_file']">
                                        جاري معالجة الملف...
                                        <span class="file-loading"></span>
                                    </span>
                                    <span class="text-green-600 font-semibold" x-show="formData.step3.completed_projects_file && !loadingFiles['step3_completed_projects_file']">✓ تم اختيار الملف</span>
                                </label>
                                <div class="file-info" :class="{ 'has-file': formData.step3.completed_projects_file }" x-show="formData.step3.completed_projects_file && !loadingFiles['step3_completed_projects_file']">
                                    <span x-text="formData.step3.completed_projects_file?.name"></span>
                                    <span class="text-xs" x-text="' (' + formatFileSize(formData.step3.completed_projects_file?.size) + ')'"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">ملف الكادر الفني (PDF) *</label>
                            <div class="file-upload-area" 
                                 :class="{ 'has-file': formData.step3.technical_staff_file, 'loading': loadingFiles['step3_technical_staff_file'] }">
                                <input type="file" @change="handleFile($event, 'step3', 'technical_staff_file', 'step3_technical_staff_file')" 
                                       accept=".pdf"
                                       class="hidden" id="file8" required>
                                <label for="file8" class="cursor-pointer block">
                                    <span class="text-blue-600 font-semibold" x-show="!formData.step3.technical_staff_file && !loadingFiles['step3_technical_staff_file']">📄 اختر ملف PDF</span>
                                    <span class="text-blue-600 font-semibold" x-show="loadingFiles['step3_technical_staff_file']">
                                        جاري معالجة الملف...
                                        <span class="file-loading"></span>
                                    </span>
                                    <span class="text-green-600 font-semibold" x-show="formData.step3.technical_staff_file && !loadingFiles['step3_technical_staff_file']">✓ تم اختيار الملف</span>
                                </label>
                                <div class="file-info" :class="{ 'has-file': formData.step3.technical_staff_file }" x-show="formData.step3.technical_staff_file && !loadingFiles['step3_technical_staff_file']">
                                    <span x-text="formData.step3.technical_staff_file?.name"></span>
                                    <span class="text-xs" x-text="' (' + formatFileSize(formData.step3.technical_staff_file?.size) + ')'"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">شهادات الجودة والاعتماد (PDF) *</label>
                            <div class="file-upload-area" 
                                 :class="{ 'has-file': formData.step3.quality_certificates_file, 'loading': loadingFiles['step3_quality_certificates_file'] }">
                                <input type="file" @change="handleFile($event, 'step3', 'quality_certificates_file', 'step3_quality_certificates_file')" 
                                       accept=".pdf"
                                       class="hidden" id="file9" required>
                                <label for="file9" class="cursor-pointer block">
                                    <span class="text-blue-600 font-semibold" x-show="!formData.step3.quality_certificates_file && !loadingFiles['step3_quality_certificates_file']">📄 اختر ملف PDF</span>
                                    <span class="text-blue-600 font-semibold" x-show="loadingFiles['step3_quality_certificates_file']">
                                        جاري معالجة الملف...
                                        <span class="file-loading"></span>
                                    </span>
                                    <span class="text-green-600 font-semibold" x-show="formData.step3.quality_certificates_file && !loadingFiles['step3_quality_certificates_file']">✓ تم اختيار الملف</span>
                                </label>
                                <div class="file-info" :class="{ 'has-file': formData.step3.quality_certificates_file }" x-show="formData.step3.quality_certificates_file && !loadingFiles['step3_quality_certificates_file']">
                                    <span x-text="formData.step3.quality_certificates_file?.name"></span>
                                    <span class="text-xs" x-text="' (' + formatFileSize(formData.step3.quality_certificates_file?.size) + ')'"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" @click="prevStep()" 
                                    class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 font-semibold transition">
                                ← السابق
                            </button>
                            <button type="button" @click="nextStep(3)" 
                                    class="flex-1 btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                                التالي →
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Financial Documents -->
                    <div x-show="currentStep === 4" class="space-y-6" x-transition>
                        <h2 class="text-2xl font-bold mb-6 text-blue-600 border-b-2 border-blue-200 pb-3">البيانات المالية (PDF فقط)</h2>
                        
                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">بيانات مالية لآخر 3 سنوات (PDF) *</label>
                            <div class="file-upload-area" 
                                 :class="{ 'has-file': formData.step4.financial_statements_file, 'loading': loadingFiles['step4_financial_statements_file'] }">
                                <input type="file" @change="handleFile($event, 'step4', 'financial_statements_file', 'step4_financial_statements_file')" 
                                       accept=".pdf"
                                       class="hidden" id="file10" required>
                                <label for="file10" class="cursor-pointer block">
                                    <span class="text-blue-600 font-semibold" x-show="!formData.step4.financial_statements_file && !loadingFiles['step4_financial_statements_file']">📄 اختر ملف PDF</span>
                                    <span class="text-blue-600 font-semibold" x-show="loadingFiles['step4_financial_statements_file']">
                                        جاري معالجة الملف...
                                        <span class="file-loading"></span>
                                    </span>
                                    <span class="text-green-600 font-semibold" x-show="formData.step4.financial_statements_file && !loadingFiles['step4_financial_statements_file']">✓ تم اختيار الملف</span>
                                </label>
                                <div class="file-info" :class="{ 'has-file': formData.step4.financial_statements_file }" x-show="formData.step4.financial_statements_file && !loadingFiles['step4_financial_statements_file']">
                                    <span x-text="formData.step4.financial_statements_file?.name"></span>
                                    <span class="text-xs" x-text="' (' + formatFileSize(formData.step4.financial_statements_file?.size) + ')'"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">شهادة ملاءة مالية من المصرف (PDF) *</label>
                            <div class="file-upload-area" 
                                 :class="{ 'has-file': formData.step4.solvency_certificate_file, 'loading': loadingFiles['step4_solvency_certificate_file'] }">
                                <input type="file" @change="handleFile($event, 'step4', 'solvency_certificate_file', 'step4_solvency_certificate_file')" 
                                       accept=".pdf"
                                       class="hidden" id="file11" required>
                                <label for="file11" class="cursor-pointer block">
                                    <span class="text-blue-600 font-semibold" x-show="!formData.step4.solvency_certificate_file && !loadingFiles['step4_solvency_certificate_file']">📄 اختر ملف PDF</span>
                                    <span class="text-blue-600 font-semibold" x-show="loadingFiles['step4_solvency_certificate_file']">
                                        جاري معالجة الملف...
                                        <span class="file-loading"></span>
                                    </span>
                                    <span class="text-green-600 font-semibold" x-show="formData.step4.solvency_certificate_file && !loadingFiles['step4_solvency_certificate_file']">✓ تم اختيار الملف</span>
                                </label>
                                <div class="file-info" :class="{ 'has-file': formData.step4.solvency_certificate_file }" x-show="formData.step4.solvency_certificate_file && !loadingFiles['step4_solvency_certificate_file']">
                                    <span x-text="formData.step4.solvency_certificate_file?.name"></span>
                                    <span class="text-xs" x-text="' (' + formatFileSize(formData.step4.solvency_certificate_file?.size) + ')'"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2 font-semibold">ملاحظات تقنية (اختياري)</label>
                            <textarea x-model="formData.step4.technical_notes" 
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-field focus:outline-none"
                                      rows="4"></textarea>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" @click="prevStep()" 
                                    class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 font-semibold transition">
                                ← السابق
                            </button>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 font-semibold transition transform hover:scale-105">
                                ✓ إرسال الطلب
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Success Modal -->
                <div x-show="success" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                     style="display: none;">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-t-2xl text-center">
                            <div class="text-6xl mb-4">✓</div>
                            <h3 class="text-2xl font-bold" x-text="successMessage"></h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-semibold mb-2">رقم الطلب:</label>
                                <div class="flex items-center gap-2 bg-gray-50 border-2 border-gray-200 rounded-lg p-3">
                                    <input type="text" 
                                           :value="requestNumber" 
                                           readonly
                                           id="requestNumberInput"
                                           class="flex-1 bg-transparent border-none outline-none text-lg font-bold text-gray-800">
                                    <button @click="copyRequestNumber()" 
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                                        <span>📋</span>
                                        <span x-text="copied ? 'تم النسخ!' : 'نسخ'"></span>
                                    </button>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-6 text-center">
                                تم حفظ رقم الطلب بنجاح. يمكنك استخدامه للاستعلام عن حالة الطلب لاحقاً.
                            </p>
                            
                            <button @click="goToHome()" 
                                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg transition transform hover:scale-105">
                                موافق
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function registrationForm() {
            return {
                currentStep: 1,
                formData: {
                    step1: {},
                    step2: {},
                    step3: {},
                    step4: {}
                },
                errors: {},
                success: false,
                successMessage: '',
                requestNumber: '',
                loadingFiles: {},
                copied: false,

                async nextStep(step) {
                    const endpoint = `/register/step${step}`;
                    const formData = new FormData();
                    
                    // Add all data from current step
                    const stepData = this.formData[`step${step}`];
                    console.log('Step data before sending:', stepData);
                    
                    Object.keys(stepData).forEach(key => {
                        const value = stepData[key];
                        if (value instanceof File) {
                            formData.append(key, value);
                            console.log(`Added file: ${key}`, value.name);
                        } else if (value !== null && value !== undefined && value !== '') {
                            formData.append(key, value);
                            console.log(`Added field: ${key} = ${value}`);
                        }
                    });

                    formData.append('_token', document.querySelector('input[name="_token"]').value);

                    try {
                        const response = await fetch(endpoint, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);

                        let data;
                        const contentType = response.headers.get('content-type');
                        
                        if (contentType && contentType.includes('application/json')) {
                            data = await response.json();
                        } else {
                            const text = await response.text();
                            console.error('Non-JSON response:', text);
                            alert('حدث خطأ في الخادم. يرجى التحقق من Console للمزيد من التفاصيل.');
                            return;
                        }

                        console.log('Response data:', data);

                        if (response.ok) {
                            this.errors = {};
                            if (step < 4) {
                                this.currentStep = step + 1;
                            }
                        } else {
                            this.errors = data.errors || {};
                            console.log('Validation errors:', this.errors);
                            // Show error message if exists
                            if (data.error) {
                                alert(data.error);
                            } else if (data.errors) {
                                // Show first error
                                const firstError = Object.values(data.errors)[0];
                                if (firstError && firstError.length > 0) {
                                    alert(firstError[0]);
                                } else {
                                    alert('يرجى التحقق من جميع الحقول المطلوبة');
                                }
                            } else {
                                alert('حدث خطأ غير معروف. يرجى المحاولة مرة أخرى.');
                            }
                        }
                    } catch (error) {
                        console.error('Error details:', error);
                        console.error('Error message:', error.message);
                        console.error('Error stack:', error.stack);
                        alert('حدث خطأ أثناء إرسال البيانات: ' + error.message + '\nيرجى فتح Console للمزيد من التفاصيل.');
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },

                async handleFile(event, step, field, loadingKey) {
                    const file = event.target.files[0];
                    if (file) {
                        // Set loading state
                        this.loadingFiles[loadingKey] = true;
                        
                        // Simulate file processing delay for better UX
                        await new Promise(resolve => setTimeout(resolve, 300));
                        
                        try {
                            // Validate PDF
                            if (file.type !== 'application/pdf') {
                                alert('يجب أن يكون الملف بصيغة PDF فقط');
                                event.target.value = '';
                                this.loadingFiles[loadingKey] = false;
                                return;
                            }
                            // Validate file size (10MB max)
                            if (file.size > 10 * 1024 * 1024) {
                                alert('حجم الملف يجب أن يكون أقل من 10 ميجابايت');
                                event.target.value = '';
                                this.loadingFiles[loadingKey] = false;
                                return;
                            }
                            
                            // Store file
                            this.formData[step][field] = file;
                            
                            // Small delay to show success state
                            await new Promise(resolve => setTimeout(resolve, 200));
                        } catch (error) {
                            console.error('Error handling file:', error);
                            alert('حدث خطأ أثناء معالجة الملف');
                        } finally {
                            // Clear loading state
                            this.loadingFiles[loadingKey] = false;
                        }
                    }
                },

                formatFileSize(bytes) {
                    if (!bytes) return '0 B';
                    const k = 1024;
                    const sizes = ['B', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                },

                copyRequestNumber() {
                    const input = document.getElementById('requestNumberInput');
                    if (input) {
                        input.select();
                        input.setSelectionRange(0, 99999); // For mobile devices
                        try {
                            document.execCommand('copy');
                            this.copied = true;
                            setTimeout(() => {
                                this.copied = false;
                            }, 2000);
                        } catch (err) {
                            // Fallback for modern browsers
                            navigator.clipboard.writeText(this.requestNumber).then(() => {
                                this.copied = true;
                                setTimeout(() => {
                                    this.copied = false;
                                }, 2000);
                            });
                        }
                    }
                },

                goToHome() {
                    window.location.href = '/';
                },

                async submitForm() {
                    // Validate step 4 first
                    await this.nextStep(4);

                    if (Object.keys(this.errors).length === 0) {
                        const formData = new FormData();
                        formData.append('_token', document.querySelector('input[name="_token"]').value);

                        // Add all data from all steps
                        ['step1', 'step2', 'step3', 'step4'].forEach(step => {
                            Object.keys(this.formData[step]).forEach(key => {
                                const value = this.formData[step][key];
                                if (value instanceof File) {
                                    formData.append(key, value);
                                } else if (value !== null && value !== undefined) {
                                    formData.append(key, value);
                                }
                            });
                        });

                        try {
                            const response = await fetch('/register/complete', {
                                method: 'POST',
                                body: formData
                            });

                            const data = await response.json();

                            if (response.ok) {
                                this.success = true;
                                this.successMessage = data.message;
                                this.requestNumber = data.request_number;
                            } else {
                                this.errors = data.errors || {};
                                alert(data.error || 'حدث خطأ أثناء إرسال الطلب');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.');
                        }
                    } else {
                        alert('يرجى إكمال جميع الحقول المطلوبة');
                    }
                }
            }
        }
    </script>
</body>
</html>
