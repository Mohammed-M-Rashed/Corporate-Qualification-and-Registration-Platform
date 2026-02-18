<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة تأهيل و تسجيل أدوات التنفيذ المحلية - المؤسسة الوطنية للنفط</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
    <style>
        * {
            font-family: 'Tajawal', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>
    @include('landing.sections.nav')
    @include('landing.sections.hero')

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold mb-3 text-[#0a1628]">مميزات المنصة</h2>
                <p class="text-[#64748b]">منصة متكاملة توفر تجربة سلسة وآمنة لتأهيل الشركات</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="feature-card">
                    <svg class="w-7 h-7 text-[#1a6fb5] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                    </svg>
                    <h3 class="text-lg font-bold mb-2 text-[#0a1628]">تسجيل سهل</h3>
                    <p class="text-[#64748b] text-sm leading-relaxed">منصة تسجيل مبسطة على خطوات واضحة وسهلة مع دعم كامل للوثائق</p>
                </div>
                <div class="feature-card">
                    <svg class="w-7 h-7 text-[#1a6fb5] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                    </svg>
                    <h3 class="text-lg font-bold mb-2 text-[#0a1628]">متابعة دقيقة</h3>
                    <p class="text-[#64748b] text-sm leading-relaxed">متابعة حالة الطلب في أي وقت وبسهولة مع تحديثات فورية</p>
                </div>
                <div class="feature-card">
                    <svg class="w-7 h-7 text-[#1a6fb5] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                    <h3 class="text-lg font-bold mb-2 text-[#0a1628]">آمن ومحمي</h3>
                    <p class="text-[#64748b] text-sm leading-relaxed">حماية كاملة للبيانات والوثائق مع تشفير متقدم</p>
                </div>
            </div>
        </div>
    </section>

    @include('landing.sections.how-it-works')

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto md:flex md:items-center md:gap-16">
                <div class="md:flex-1 mb-10 md:mb-0">
                    <h2 class="text-3xl md:text-4xl font-bold mb-5 text-[#0a1628]">عن المؤسسة الوطنية للنفط</h2>
                    <p class="text-[#64748b] leading-relaxed mb-4">
                        المؤسسة الوطنية للنفط هي المؤسسة الرائدة في مجال النفط والغاز في ليبيا.
                        تقود المؤسسة استكشاف النفط والغاز مع احتياطيات واسعة، بما في ذلك الغاز الطبيعي غير المستغل.
                    </p>
                    <p class="text-[#64748b] leading-relaxed">
                        وتمكّن المؤسسة الوطنية للنفط من مستقبل مستدام وذلك من خلال اعتماد الطاقة المتجددة،
                        ملتزمة بالمسؤولية البيئية والاستدامة.
                    </p>
                </div>
                <div class="md:flex-shrink-0 flex gap-6 justify-center">
                    <div class="stat-circle">
                        <span class="text-2xl font-bold text-[#0a1628]">1.4</span>
                        <span class="text-xs text-[#64748b]">م/يوم</span>
                    </div>
                    <div class="stat-circle">
                        <span class="text-2xl font-bold text-[#0a1628]">2-3</span>
                        <span class="text-xs text-[#64748b]">م/يوم مستقبلي</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('landing.sections.stats')

    <!-- Registration Section -->
    <section id="register" class="py-20 bg-[#f5f6f8]">
        <div class="container mx-auto px-6">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold mb-3 text-[#0a1628]">تسجيل شركة جديدة</h2>
                <p class="text-[#64748b]">املأ جميع البيانات المطلوبة بدقة</p>
            </div>
            <div class="max-w-6xl mx-auto w-full">
                @include('company-registration-form')
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold mb-3 text-[#0a1628]">الأسئلة الشائعة</h2>
                <p class="text-[#64748b]">إجابات على الأسئلة الأكثر شيوعاً</p>
            </div>
            <div class="max-w-3xl mx-auto" id="faq-container">
                <!-- FAQs will be loaded via JavaScript -->
            </div>
        </div>
    </section>

    <!-- Inquiry Section -->
    <section id="inquiry" class="py-20 bg-[#f5f6f8]">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto md:flex md:items-center md:gap-12">
                <div class="md:flex-1 mb-8 md:mb-0">
                    <h2 class="text-3xl md:text-4xl font-bold mb-3 text-[#0a1628]">استعلام عن حالة الطلب</h2>
                    <p class="text-[#64748b] leading-relaxed">تابع حالة طلبك بسهولة باستخدام رقم الطلب الذي حصلت عليه عند التسجيل.</p>
                </div>
                <div class="md:flex-1">
                    <form id="inquiry-form" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[#1e293b] mb-2 font-medium text-sm">رقم الطلب</label>
                            <input type="text" name="request_number" id="request_number"
                                   class="form-input w-full"
                                   placeholder="أدخل رقم الطلب">
                        </div>
                        <button type="submit" class="w-full btn-primary text-white py-3">
                            استعلام
                        </button>
                    </form>
                    <div id="inquiry-result" class="mt-4"></div>
                </div>
            </div>
        </div>
    </section>

    @include('landing.sections.support')

    @include('landing.sections.footer')

    @php
        $loadingGifPath = \App\Models\Setting::where('key', 'loading_gif')->value('value');
        $loadingGifUrl = $loadingGifPath ? asset($loadingGifPath) : null;
    @endphp
    <div x-data
         x-show="$store.loading"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/30"
         style="display: none;">
        <x-loading-box :gif-url="$loadingGifUrl" />
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('loading', false);
        });
        function registrationForm() {
            return {
                currentStep: 1,
                formData: {
                    step1: {
                        activity_id: null,
                        city_id: null,
                        is_agent: false,
                        agent_document_file: null
                    },
                    step2: {},
                    step3: {},
                    step4: {}
                },
                activities: [],
                cities: [],
                errors: {},
                validationErrors: {},
                success: false,
                successMessage: '',
                requestNumber: '',
                loadingFiles: {},
                copied: false,
                isSubmitting: false,

                getFieldError(step, field) {
                    const stepErrors = this.errors[step] || {};
                    const error = stepErrors[field];
                    if (Array.isArray(error)) {
                        return error[0];
                    }
                    if (typeof error === 'string') {
                        return error;
                    }
                    return null;
                },

                hasStepErrors(step) {
                    const stepKey = `step${step}`;
                    return this.errors[stepKey] && Object.keys(this.errors[stepKey]).length > 0;
                },

                isFieldRequiredEmpty(step, field) {
                    const stepData = this.formData[step] || {};
                    const value = stepData[field];
                    const hasError = this.getFieldError(step, field);
                    
                    // Check if field is required but empty
                    if (hasError) return true;
                    
                    // For file fields
                    if (field.includes('_file')) {
                        return !value;
                    }
                    
                    // For regular fields
                    return value === null || value === undefined || value === '';
                },

                validateField(step, field) {
                    // Clear previous error for this field
                    if (this.errors[step]) {
                        delete this.errors[step][field];
                    }
                    // Trigger validation on blur
                    this.updateValidationErrors();
                },

                updateValidationErrors() {
                    const flatErrors = {};
                    Object.keys(this.errors).forEach(step => {
                        Object.keys(this.errors[step] || {}).forEach(field => {
                            const error = this.errors[step][field];
                            const key = `${step}.${field}`;
                            if (Array.isArray(error)) {
                                flatErrors[key] = error[0];
                            } else if (error) {
                                flatErrors[key] = error;
                            }
                        });
                    });
                    this.validationErrors = flatErrors;
                },

                validateStep(step) {
                    const stepData = this.formData[`step${step}`] || {};
                    const stepErrors = {};
                    let hasErrors = false;
                    
                    // Define required fields for each step
                    const requiredFields = {
                        1: ['name', 'activity_id', 'city_id', 'email', 'phone', 'address'],
                        2: ['commercial_register_number', 'commercial_register_start_date', 'commercial_register_end_date', 
                            'establishment_contract_file', 'commercial_register_extract_file', 'activity_license_file', 
                            'chamber_registration_file', 'tax_certificate_file', 'social_security_certificate_file'],
                        3: ['experience_level', 'completed_projects_file', 'technical_staff_file', 'quality_certificates_file'],
                        4: ['financial_statements_file', 'solvency_certificate_file']
                    };
                    
                    const fields = requiredFields[step] || [];
                    
                    fields.forEach(field => {
                        const value = stepData[field];
                        const isEmpty = value === null || value === undefined || value === '' || 
                                       (value instanceof File && !value.name);
                        
                        if (isEmpty) {
                            stepErrors[field] = ['هذا الحقل مطلوب'];
                            hasErrors = true;
                        }
                    });
                    
                    // Special validation for agent_document_file (only required if is_agent is true)
                    if (step === 1 && stepData.is_agent && !stepData.agent_document_file) {
                        stepErrors['agent_document_file'] = ['مستند الوكالة مطلوب إذا كنت وكيل معتمد'];
                        hasErrors = true;
                    }
                    
                    if (hasErrors) {
                        const stepKey = `step${step}`;
                        this.errors[stepKey] = stepErrors;
                        this.updateValidationErrors();
                        
                        // Show alert message
                        setTimeout(() => {
                            const summary = document.querySelector('.validation-summary');
                            if (summary) {
                                summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }, 100);
                    }
                    
                    return !hasErrors;
                },

                async nextStep(step) {
                    if (this.isSubmitting) return;
                    
                    // Validate current step before proceeding
                    if (!this.validateStep(step)) {
                        return;
                    }
                    
                    this.isSubmitting = true;
                    if (window.Alpine?.store) Alpine.store('loading', true);
                    
                    const endpoint = `/register/step${step}`;
                    const formData = new FormData();
                    
                    const stepData = this.formData[`step${step}`];
                    
                    Object.keys(stepData).forEach(key => {
                        const value = stepData[key];
                        if (value instanceof File) {
                            formData.append(key, value);
                        } else if (key === 'is_agent') {
                            // Always send is_agent (even if false)
                            formData.append(key, value ? '1' : '0');
                        } else if (value !== null && value !== undefined && value !== '') {
                            formData.append(key, value);
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

                        let data;
                        const contentType = response.headers.get('content-type');
                        
                        if (contentType && contentType.includes('application/json')) {
                            data = await response.json();
                        } else {
                            const text = await response.text();
                            this.errors = { [`step${step}`]: { general: ['حدث خطأ في الخادم. يرجى المحاولة مرة أخرى.'] } };
                            this.updateValidationErrors();
                            setTimeout(() => {
                                const summary = document.querySelector('.validation-summary');
                                if (summary) {
                                    summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }, 100);
                            this.isSubmitting = false;
                            return;
                        }

                        if (response.ok) {
                            this.errors = {};
                            this.validationErrors = {};
                            if (step < 4) {
                                this.currentStep = step + 1;
                                window.scrollTo({ top: document.querySelector('#register').offsetTop - 100, behavior: 'smooth' });
                            }
                        } else {
                            const formattedErrors = {};
                            if (data.errors) {
                                Object.keys(data.errors).forEach(key => {
                                    const errorValue = data.errors[key];
                                    const stepKey = `step${step}`;
                                    if (!formattedErrors[stepKey]) formattedErrors[stepKey] = {};
                                    
                                    if (key.includes('.')) {
                                        const [, fieldKey] = key.split('.');
                                        formattedErrors[stepKey][fieldKey] = Array.isArray(errorValue) ? errorValue : [errorValue];
                                    } else {
                                        formattedErrors[stepKey][key] = Array.isArray(errorValue) ? errorValue : [errorValue];
                                    }
                                });
                            }
                            this.errors = formattedErrors;
                            this.updateValidationErrors();
                            
                            setTimeout(() => {
                                const summary = document.querySelector('.validation-summary');
                                if (summary) {
                                    summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }, 100);
                        }
                    } catch (error) {
                        this.errors = { [`step${step}`]: { general: ['حدث خطأ أثناء إرسال البيانات. يرجى المحاولة مرة أخرى.'] } };
                        this.updateValidationErrors();
                        setTimeout(() => {
                            const summary = document.querySelector('.validation-summary');
                            if (summary) {
                                summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }, 100);
                    } finally {
                        this.isSubmitting = false;
                        if (window.Alpine?.store) Alpine.store('loading', false);
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
                        this.loadingFiles[loadingKey] = true;
                        await new Promise(resolve => setTimeout(resolve, 300));
                        
                        try {
                            // Clear previous error
                            if (this.errors[step] && this.errors[step][field]) {
                                delete this.errors[step][field];
                                this.updateValidationErrors();
                            }
                            
                            if (file.type !== 'application/pdf') {
                                if (!this.errors[step]) this.errors[step] = {};
                                this.errors[step][field] = ['يجب أن يكون الملف بصيغة PDF فقط'];
                                this.updateValidationErrors();
                                event.target.value = '';
                                this.loadingFiles[loadingKey] = false;
                                return;
                            }
                            if (file.size > 10 * 1024 * 1024) {
                                if (!this.errors[step]) this.errors[step] = {};
                                this.errors[step][field] = ['حجم الملف يجب أن يكون أقل من 10 ميجابايت'];
                                this.updateValidationErrors();
                                event.target.value = '';
                                this.loadingFiles[loadingKey] = false;
                                return;
                            }
                            
                            this.formData[step][field] = file;
                            await new Promise(resolve => setTimeout(resolve, 200));
                        } catch (error) {
                            if (!this.errors[step]) this.errors[step] = {};
                            this.errors[step][field] = ['حدث خطأ أثناء معالجة الملف'];
                            this.updateValidationErrors();
                        } finally {
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
                        input.setSelectionRange(0, 99999);
                        try {
                            document.execCommand('copy');
                            this.copied = true;
                            setTimeout(() => {
                                this.copied = false;
                            }, 2000);
                        } catch (err) {
                            navigator.clipboard.writeText(this.requestNumber).then(() => {
                                this.copied = true;
                                setTimeout(() => {
                                    this.copied = false;
                                }, 2000);
                            });
                        }
                    }
                },

                async loadActivities() {
                    try {
                        const response = await fetch('/api/company-activities');
                        if (response.ok) {
                            this.activities = await response.json();
                        }
                    } catch (error) {
                        console.error('Error loading activities:', error);
                    }
                },

                async loadCities() {
                    try {
                        const response = await fetch('/api/cities');
                        if (response.ok) {
                            this.cities = await response.json();
                        }
                    } catch (error) {
                        console.error('Error loading cities:', error);
                    }
                },

                async init() {
                    if (window.Alpine?.store) Alpine.store('loading', true);
                    try {
                        await Promise.all([this.loadActivities(), this.loadCities()]);
                    } finally {
                        if (window.Alpine?.store) Alpine.store('loading', false);
                    }
                },

                resetForm() {
                    // Reset all form data
                    this.formData = {
                        step1: {
                            activity_id: null,
                            city_id: null,
                            is_agent: false,
                            agent_document_file: null
                        },
                        step2: {},
                        step3: {},
                        step4: {}
                    };
                    
                    // Reset errors
                    this.errors = {};
                    this.validationErrors = {};
                    
                    // Reset step to first
                    this.currentStep = 1;
                    
                    // Reset success state
                    this.success = false;
                    this.successMessage = '';
                    this.requestNumber = '';
                    
                    // Reset loading files
                    this.loadingFiles = {};
                    
                    // Reset copied state
                    this.copied = false;
                    
                    // Reset submitting state
                    this.isSubmitting = false;
                    
                    // Reset all file inputs
                    document.querySelectorAll('input[type="file"]').forEach(input => {
                        input.value = '';
                    });
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                goToHome() {
                    this.resetForm();
                },

                async submitForm() {
                    if (this.isSubmitting) return;
                    
                    // Validate step 4 first
                    if (!this.validateStep(4)) {
                        return;
                    }
                    
                    this.isSubmitting = true;
                    if (window.Alpine?.store) Alpine.store('loading', true);
                    
                    // Validate all steps before final submission
                    let allValid = true;
                    for (let step = 1; step <= 4; step++) {
                        if (!this.validateStep(step)) {
                            allValid = false;
                            this.currentStep = step;
                            break;
                        }
                    }
                    
                    if (!allValid) {
                        this.isSubmitting = false;
                        setTimeout(() => {
                            const summary = document.querySelector('.validation-summary');
                            if (summary) {
                                summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }, 100);
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('input[name="_token"]').value);

                    ['step1', 'step2', 'step3', 'step4'].forEach(step => {
                        Object.keys(this.formData[step] || {}).forEach(key => {
                            const value = this.formData[step][key];
                            if (value instanceof File) {
                                formData.append(key, value);
                            } else if (value !== null && value !== undefined && value !== '') {
                                formData.append(key, value);
                            }
                        });
                    });

                    try {
                        const response = await fetch('/register/complete', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        const contentType = response.headers.get('content-type');
                        let data;
                        
                        if (contentType && contentType.includes('application/json')) {
                            data = await response.json();
                        } else {
                            const text = await response.text();
                            console.error('Non-JSON response:', text);
                            this.errors = { step4: { general: ['حدث خطأ في الخادم. يرجى المحاولة مرة أخرى.'] } };
                            this.updateValidationErrors();
                            setTimeout(() => {
                                const summary = document.querySelector('.validation-summary');
                                if (summary) {
                                    summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }, 100);
                            this.isSubmitting = false;
                            return;
                        }

                        console.log('Response status:', response.status);
                        console.log('Response data:', data);

                        // Check if request was successful (status 200-299) or if data contains success flag
                        if (response.ok && data && (data.success || data.request_number)) {
                            this.success = true;
                            this.successMessage = data.message || 'تم إرسال الطلب بنجاح';
                            this.requestNumber = data.request_number || data.requestNumber || '';
                            this.errors = {};
                            this.validationErrors = {};
                            this.isSubmitting = false;
                            setTimeout(() => {
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }, 100);
                            return; // Exit early on success
                        } else {
                            const formattedErrors = {};
                            
                            // Handle both errors object and error string
                            if (data.errors) {
                                Object.keys(data.errors).forEach(key => {
                                    const errorValue = data.errors[key];
                                    let stepKey = 'step4';
                                    
                                    if (key.includes('.')) {
                                        const [sKey, fieldKey] = key.split('.');
                                        stepKey = sKey;
                                        if (!formattedErrors[stepKey]) formattedErrors[stepKey] = {};
                                        formattedErrors[stepKey][fieldKey] = Array.isArray(errorValue) ? errorValue : [errorValue];
                                    } else {
                                        if (['name', 'city_id', 'email', 'phone', 'address', 'activity_id'].includes(key)) {
                                            stepKey = 'step1';
                                        } else if (key.includes('commercial_register') || key.includes('establishment') || key.includes('chamber') || key.includes('tax') || key.includes('social_security') || key.includes('activity_license')) {
                                            stepKey = 'step2';
                                        } else if (key.includes('experience') || key.includes('projects') || key.includes('technical_staff') || key.includes('quality')) {
                                            stepKey = 'step3';
                                        }
                                        
                                        if (!formattedErrors[stepKey]) formattedErrors[stepKey] = {};
                                        formattedErrors[stepKey][key] = Array.isArray(errorValue) ? errorValue : [errorValue];
                                    }
                                });
                            } else if (data.error) {
                                // Handle general error message
                                formattedErrors['step1'] = formattedErrors['step1'] || {};
                                formattedErrors['step1']['general'] = [data.error];
                            }
                            
                            this.errors = formattedErrors;
                            this.updateValidationErrors();
                            
                            // Navigate to the step with errors
                            if (formattedErrors['step1']) {
                                this.currentStep = 1;
                            } else if (formattedErrors['step2']) {
                                this.currentStep = 2;
                            } else if (formattedErrors['step3']) {
                                this.currentStep = 3;
                            } else if (formattedErrors['step4']) {
                                this.currentStep = 4;
                            }
                            
                            setTimeout(() => {
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }, 100);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.errors = { step4: { general: ['حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.'] } };
                        this.updateValidationErrors();
                        setTimeout(() => {
                            const summary = document.querySelector('.validation-summary');
                            if (summary) {
                                summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }, 100);
                    } finally {
                        this.isSubmitting = false;
                        if (window.Alpine?.store) Alpine.store('loading', false);
                    }
                }
            }
        }

        // Load FAQs
        fetch('/faqs')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('faq-container');
                if (data && data.length > 0) {
                    data.forEach((faq, index) => {
                        const faqItem = document.createElement('div');
                        faqItem.className = 'faq-item fade-in-up';
                        faqItem.innerHTML = `
                            <div class="faq-question">
                                <span>${faq.question}</span>
                                <span class="faq-icon">▼</span>
                            </div>
                            <div class="faq-answer">
                                <p class="text-[#64748b] leading-relaxed">${faq.answer}</p>
                            </div>
                        `;
                        container.appendChild(faqItem);
                    });
                    // Initialize FAQ after loading
                    setTimeout(() => {
                        initFAQAccordion();
                    }, 100);
                } else {
                    container.innerHTML = '<p class="text-center text-[#64748b]">لا توجد أسئلة شائعة متاحة حالياً</p>';
                }
            })
            .catch(error => {
                console.error('Error loading FAQs:', error);
            });

        // FAQ Accordion initialization function
        function initFAQAccordion() {
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                if (question && !question.hasAttribute('data-initialized')) {
                    question.setAttribute('data-initialized', 'true');
                    question.addEventListener('click', () => {
                        const isActive = item.classList.contains('active');
                        // Close all items
                        faqItems.forEach(i => i.classList.remove('active'));
                        // Open clicked item if it wasn't active
                        if (!isActive) {
                            item.classList.add('active');
                        }
                    });
                }
            });
        }

        // Also initialize on DOMContentLoaded in case FAQs are already loaded
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                initFAQAccordion();
            }, 500);
        });
    </script>
</body>
</html>
