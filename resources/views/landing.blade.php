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
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav-glass shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold text-blue-600 flex items-center gap-3">
                    @php
                        $logoPath = \App\Models\Setting::where('key', 'system_logo')->value('value');
                        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
                    @endphp
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="شعار المؤسسة" class="h-12 w-auto">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg">NOC</span>
                        </div>
                    @endif
                    <span class="hidden sm:inline">المؤسسة الوطنية للنفط</span>
                </div>
                <div class="hidden md:flex space-x-4 space-x-reverse items-center">
                    <a href="#about" class="text-blue-600 hover:text-blue-700 transition px-4 py-2 rounded-lg hover:bg-blue-50">من نحن</a>
                    <a href="#features" class="text-blue-600 hover:text-blue-700 transition px-4 py-2 rounded-lg hover:bg-blue-50">المميزات</a>
                    <a href="#faq" class="text-blue-600 hover:text-blue-700 transition px-4 py-2 rounded-lg hover:bg-blue-50">الأسئلة الشائعة</a>
                    <a href="#register" class="btn-primary text-white">تسجيل شركة</a>
                </div>
                <button id="mobile-menu-button" class="md:hidden text-blue-600 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div id="mobile-menu" class="hidden md:hidden mt-4 space-y-2">
                <a href="#about" class="block text-blue-600 hover:text-blue-700 transition px-4 py-2 rounded-lg hover:bg-blue-50">من نحن</a>
                <a href="#features" class="block text-blue-600 hover:text-blue-700 transition px-4 py-2 rounded-lg hover:bg-blue-50">المميزات</a>
                <a href="#faq" class="block text-blue-600 hover:text-blue-700 transition px-4 py-2 rounded-lg hover:bg-blue-50">الأسئلة الشائعة</a>
                <a href="#register" class="block btn-primary text-white text-center">تسجيل شركة</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient py-32 text-white relative">
        <div class="hero-content container mx-auto px-6 text-center">
            <div class="fade-in mb-8">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 drop-shadow-lg leading-tight">منصة تأهيل و تسجيل أدوات التنفيذ المحلية</h1>
                <p class="text-xl md:text-2xl mb-12 text-blue-100 max-w-3xl mx-auto leading-relaxed">منصة متكاملة لتأهيل وتسجيل الشركات والمقاولين المحليين</p>
            </div>
            <div class="flex flex-col md:flex-row justify-center gap-4 fade-in-up">
                <a href="#register" class="btn-primary text-white inline-block">
                    تسجيل شركة جديدة
                </a>
                <a href="#inquiry" class="btn-secondary text-white inline-block">
                    استعلام عن طلب
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900">مميزات المنصة</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">منصة متكاملة توفر تجربة سلسة وآمنة لتأهيل الشركات</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card fade-in-up">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mb-6">
                        <span class="text-white text-xl font-bold">1</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900">تسجيل سهل</h3>
                    <p class="text-gray-600 leading-relaxed">منصة تسجيل مبسطة على خطوات واضحة وسهلة مع دعم كامل للوثائق</p>
                </div>
                <div class="feature-card fade-in-up">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mb-6">
                        <span class="text-white text-xl font-bold">2</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900">متابعة دقيقة</h3>
                    <p class="text-gray-600 leading-relaxed">متابعة حالة الطلب في أي وقت وبسهولة مع تحديثات فورية</p>
                </div>
                <div class="feature-card fade-in-up">
                    <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mb-6">
                        <span class="text-white text-xl font-bold">3</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900">آمن ومحمي</h3>
                    <p class="text-gray-600 leading-relaxed">حماية كاملة للبيانات والوثائق مع تشفير متقدم</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=1920&q=80'); background-size: cover; background-position: center;"></div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/90 via-blue-700/90 to-blue-800/90"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center fade-in">
                <h2 class="text-4xl md:text-5xl font-bold mb-8">عن المؤسسة الوطنية للنفط</h2>
                <div class="space-y-6 text-lg leading-relaxed">
                    <p>
                        المؤسسة الوطنية للنفط هي المؤسسة الرائدة في مجال النفط والغاز في ليبيا. 
                        تقود المؤسسة استكشاف النفط والغاز مع احتياطيات واسعة، بما في ذلك الغاز الطبيعي غير المستغل.
                    </p>
                    <p>
                        وتمكّن المؤسسة الوطنية للنفط من مستقبل مستدام وذلك من خلال اعتماد الطاقة المتجددة، 
                        ملتزمة بالمسؤولية البيئية والاستدامة.
                    </p>
                </div>
                <div class="mt-12">
                    <a href="https://noc.ly/" target="_blank" class="btn-primary text-white inline-block">
                        زيارة الموقع الرسمي
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section id="register" class="py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 fade-in">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900">تسجيل شركة جديدة</h2>
                <p class="text-xl text-gray-600">املأ جميع البيانات المطلوبة بدقة</p>
            </div>
            <div class="max-w-6xl mx-auto w-full">
                @include('company-registration-form')
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 fade-in">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">الأسئلة الشائعة</h2>
                <p class="text-xl text-blue-100">إجابات على الأسئلة الأكثر شيوعاً</p>
            </div>
            <div class="max-w-3xl mx-auto space-y-4" id="faq-container">
                <!-- FAQs will be loaded via JavaScript -->
            </div>
        </div>
    </section>

    <!-- Inquiry Section -->
    <section id="inquiry" class="py-24 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 fade-in">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900">استعلام عن حالة الطلب</h2>
                <p class="text-xl text-gray-600">تابع حالة طلبك بسهولة باستخدام رقم الطلب</p>
            </div>
            <div class="max-w-md mx-auto fade-in-up">
                <div class="feature-card bg-white p-8 shadow-2xl">
                    <form id="inquiry-form" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-gray-700 mb-3 font-semibold text-lg">رقم الطلب</label>
                            <input type="text" name="request_number" id="request_number" 
                                   class="form-input w-full"
                                   placeholder="أدخل رقم الطلب">
                        </div>
                        <button type="submit" class="w-full btn-primary text-white">
                            استعلام
                        </button>
                    </form>
                    <div id="inquiry-result" class="mt-6"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        @php
                            $logoPath = \App\Models\Setting::where('key', 'system_logo')->value('value');
                            $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
                        @endphp
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="شعار المؤسسة" class="h-12 w-auto">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold">NOC</span>
                            </div>
                        @endif
                        <span class="text-xl font-bold">المؤسسة الوطنية للنفط</span>
                    </div>
                    <p class="text-blue-300 text-sm">منصة متكاملة لتأهيل الشركات والمقاولين المحليين</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">روابط سريعة</h3>
                    <ul class="space-y-2">
                        <li><a href="#about" class="text-blue-300 hover:text-blue-100 transition">من نحن</a></li>
                        <li><a href="#features" class="text-blue-300 hover:text-blue-100 transition">المميزات</a></li>
                        <li><a href="#faq" class="text-blue-300 hover:text-blue-100 transition">الأسئلة الشائعة</a></li>
                        <li><a href="#register" class="text-blue-300 hover:text-blue-100 transition">تسجيل شركة</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">معلومات الاتصال</h3>
                    <ul class="space-y-2 text-blue-300 text-sm">
                        <li>طرابلس، ليبيا</li>
                        <li><a href="https://noc.ly/" target="_blank" class="hover:text-blue-100 transition">الموقع الرسمي</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">تابعنا</h3>
                    <p class="text-blue-300 text-sm mb-4">تابع آخر الأخبار والتحديثات</p>
                    <a href="https://noc.ly/" target="_blank" class="btn-primary text-white inline-block text-sm">
                        زيارة الموقع
                    </a>
                </div>
            </div>
            <div class="border-t border-blue-800 pt-8 text-center">
                <p class="text-blue-300">حقوق النشر محفوظة © 2025 المؤسسة الوطنية للنفط</p>
            </div>
        </div>
    </footer>

    <script>
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

                init() {
                    this.loadActivities();
                    this.loadCities();
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
                        faqItem.className = 'faq-item';
                        faqItem.innerHTML = `
                            <div class="faq-question">
                                <span>${faq.question}</span>
                                <span class="faq-icon">▼</span>
                            </div>
                            <div class="faq-answer">
                                <p class="text-blue-100 leading-relaxed">${faq.answer}</p>
                            </div>
                        `;
                        container.appendChild(faqItem);
                    });
                    // Initialize FAQ after loading
                    setTimeout(() => {
                        initFAQAccordion();
                    }, 100);
                } else {
                    container.innerHTML = '<p class="text-center text-blue-100">لا توجد أسئلة شائعة متاحة حالياً</p>';
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
