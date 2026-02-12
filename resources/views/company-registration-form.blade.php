<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200" x-data="registrationForm()">
    <!-- Stepper Header -->
    <div class="bg-gray-50 border-b border-gray-200 px-6 py-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">تسجيل شركة جديدة</h2>
                <p class="text-sm text-gray-600">املأ جميع البيانات المطلوبة بدقة</p>
            </div>
            
            <!-- Horizontal Step Indicator -->
            <div class="flex items-center justify-between max-w-4xl mx-auto">
                <template x-for="(step, index) in [
                    { number: 1, title: 'البيانات الأساسية', key: 'step1' },
                    { number: 2, title: 'البيانات القانونية', key: 'step2' },
                    { number: 3, title: 'البيانات الفنية', key: 'step3' },
                    { number: 4, title: 'البيانات المالية', key: 'step4' }
                ]" :key="index">
                    <div class="flex items-center flex-1" :class="{ 'flex-1': index < 3 }">
                        <div class="flex flex-col items-center flex-1">
                            <div class="relative">
                                <div class="step-circle-new" 
                                     :class="{
                                         'active': currentStep === step.number,
                                         'completed': currentStep > step.number,
                                         'has-error': hasStepErrors(step.number)
                                     }">
                                    <span x-show="currentStep > step.number" class="check-mark">✓</span>
                                    <span x-show="currentStep <= step.number" x-text="step.number" class="step-num"></span>
                                </div>
                                <div x-show="hasStepErrors(step.number)" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs">!</div>
                            </div>
                            <div class="mt-2 text-center">
                                <div class="text-xs font-medium" 
                                     :class="{
                                         'text-primary-600': currentStep === step.number,
                                         'text-gray-900': currentStep > step.number,
                                         'text-gray-500': currentStep < step.number,
                                         'text-red-600': hasStepErrors(step.number)
                                     }" 
                                     x-text="step.title"></div>
                            </div>
                        </div>
                        <div x-show="index < 3" class="flex-1 h-0.5 mx-2 mt-6" 
                             :class="currentStep > step.number ? 'bg-primary-600' : 'bg-gray-300'"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    
    <div class="p-6 md:p-8">
        <!-- Form -->
        <form @submit.prevent="submitForm" class="w-full">
            @csrf

            <!-- Step 1: Basic Information -->
            <div x-show="currentStep === 1" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="w-full space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                    <!-- Company Name -->
                    <div class="form-group w-full" :class="{ 'has-error': getFieldError('step1', 'name'), 'has-success': formData.step1.name && !getFieldError('step1', 'name') }">
                        <label class="form-label w-full">
                            <span class="text-danger-600">*</span>
                            اسم الشركة
                        </label>
                        <div class="relative w-full">
                            <input type="text" 
                                   x-model="formData.step1.name"
                                   @blur="validateField('step1', 'name')"
                                   class="form-input w-full"
                                   :class="{ 'border-danger-600': getFieldError('step1', 'name'), 'border-success-600': formData.step1.name && !getFieldError('step1', 'name') }"
                                   required>
                            <div x-show="getFieldError('step1', 'name')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-danger-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div x-show="formData.step1.name && !getFieldError('step1', 'name')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <p x-show="getFieldError('step1', 'name')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step1', 'name')"></p>
                    </div>

                    <!-- Activity -->
                    <div class="form-group w-full" :class="{ 'has-error': getFieldError('step1', 'activity_id'), 'has-success': formData.step1.activity_id && !getFieldError('step1', 'activity_id') }">
                        <label class="form-label w-full">
                            <span class="text-danger-600">*</span>
                            النشاط
                        </label>
                        <div class="relative w-full">
                            <select x-model="formData.step1.activity_id"
                                    @blur="validateField('step1', 'activity_id')"
                                    class="form-input w-full"
                                    :class="{ 'border-danger-600': getFieldError('step1', 'activity_id'), 'border-success-600': formData.step1.activity_id && !getFieldError('step1', 'activity_id') }"
                                    required>
                                <option value="">اختر النشاط</option>
                                <template x-for="activity in activities" :key="activity.id">
                                    <option :value="activity.id" x-text="activity.name"></option>
                                </template>
                            </select>
                            <div x-show="getFieldError('step1', 'activity_id')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-danger-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div x-show="formData.step1.activity_id && !getFieldError('step1', 'activity_id')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <p x-show="getFieldError('step1', 'activity_id')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step1', 'activity_id')"></p>
                    </div>

                    <!-- City -->
                    <div class="form-group w-full" :class="{ 'has-error': getFieldError('step1', 'city_id'), 'has-success': formData.step1.city_id && !getFieldError('step1', 'city_id') }">
                        <label class="form-label w-full">
                            <span class="text-danger-600">*</span>
                            المدينة
                        </label>
                        <div class="relative w-full">
                            <select x-model="formData.step1.city_id"
                                    @blur="validateField('step1', 'city_id')"
                                    class="form-input w-full"
                                    :class="{ 'border-danger-600': getFieldError('step1', 'city_id'), 'border-success-600': formData.step1.city_id && !getFieldError('step1', 'city_id') }"
                                    required>
                                <option value="">اختر المدينة</option>
                                <template x-for="city in cities" :key="city.id">
                                    <option :value="city.id" x-text="city.name"></option>
                                </template>
                            </select>
                            <div x-show="getFieldError('step1', 'city_id')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-danger-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div x-show="formData.step1.city_id && !getFieldError('step1', 'city_id')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <p x-show="getFieldError('step1', 'city_id')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step1', 'city_id')"></p>
                    </div>

                    <!-- Email -->
                    <div class="form-group w-full" :class="{ 'has-error': getFieldError('step1', 'email'), 'has-success': formData.step1.email && !getFieldError('step1', 'email') }">
                        <label class="form-label w-full">
                            <span class="text-danger-600">*</span>
                            البريد الإلكتروني
                        </label>
                        <div class="relative w-full">
                            <input type="email" 
                                   x-model="formData.step1.email"
                                   @blur="validateField('step1', 'email')"
                                   class="form-input w-full"
                                   :class="{ 'border-danger-600': getFieldError('step1', 'email'), 'border-success-600': formData.step1.email && !getFieldError('step1', 'email') }"
                                   required>
                            <div x-show="getFieldError('step1', 'email')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-danger-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div x-show="formData.step1.email && !getFieldError('step1', 'email')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <p x-show="getFieldError('step1', 'email')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step1', 'email')"></p>
                    </div>

                    <!-- Phone -->
                    <div class="form-group w-full" :class="{ 'has-error': getFieldError('step1', 'phone'), 'has-success': formData.step1.phone && !getFieldError('step1', 'phone') }">
                        <label class="form-label w-full">
                            <span class="text-danger-600">*</span>
                            رقم الهاتف
                            <span class="text-xs text-gray-500 font-normal">(091/092/093/094 + 7 أرقام)</span>
                        </label>
                        <div class="relative w-full">
                            <input type="text" 
                                   x-model="formData.step1.phone"
                                   @blur="validateField('step1', 'phone')"
                                   pattern="^(091|092|093|094)\d{7}$"
                                   class="form-input w-full"
                                   :class="{ 'border-danger-600': getFieldError('step1', 'phone'), 'border-success-600': formData.step1.phone && !getFieldError('step1', 'phone') }"
                                   required>
                            <div x-show="getFieldError('step1', 'phone')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-danger-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div x-show="formData.step1.phone && !getFieldError('step1', 'phone')" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="h-5 w-5 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <p x-show="getFieldError('step1', 'phone')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step1', 'phone')"></p>
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group w-full" :class="{ 'has-error': getFieldError('step1', 'address'), 'has-success': formData.step1.address && !getFieldError('step1', 'address') }">
                    <label class="form-label">
                        <span class="text-danger-600">*</span>
                        العنوان
                    </label>
                    <div class="relative w-full">
                        <textarea x-model="formData.step1.address"
                                  @blur="validateField('step1', 'address')"
                                  class="form-input w-full"
                                  :class="{ 'border-danger-600': getFieldError('step1', 'address'), 'border-success-600': formData.step1.address && !getFieldError('step1', 'address') }"
                                  rows="3" required></textarea>
                        <div x-show="getFieldError('step1', 'address')" class="absolute left-3 top-3">
                            <svg class="h-5 w-5 text-danger-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div x-show="formData.step1.address && !getFieldError('step1', 'address')" class="absolute left-3 top-3">
                            <svg class="h-5 w-5 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p x-show="getFieldError('step1', 'address')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step1', 'address')"></p>
                </div>

                <!-- Is Agent -->
                <div class="form-group w-full">
                    <label class="form-label flex items-center gap-2">
                        <input type="checkbox" 
                               x-model="formData.step1.is_agent"
                               class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span>هل أنت وكيل معتمد لشركة معينة؟</span>
                    </label>
                </div>

                <!-- Agent Document -->
                <div x-show="formData.step1.is_agent" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="form-group w-full" :class="{ 'has-error': getFieldError('step1', 'agent_document_file') }">
                    <label class="form-label w-full">
                        <span class="text-danger-600">*</span>
                        مستند الوكالة
                        <span class="text-xs text-gray-500 font-normal">(PDF)</span>
                    </label>
                    <div class="file-upload-wrapper w-full" 
                         :class="{ 
                             'has-file': formData.step1.agent_document_file,
                             'has-error': getFieldError('step1', 'agent_document_file'),
                             'is-loading': loadingFiles['step1_agent_document_file']
                         }">
                        <input type="file" 
                               @change="handleFile($event, 'step1', 'agent_document_file', 'step1_agent_document_file')"
                               accept=".pdf"
                               class="hidden"
                               id="agent_document_file">
                        <label for="agent_document_file" class="file-upload-label">
                            <div x-show="!formData.step1.agent_document_file && !loadingFiles['step1_agent_document_file']" class="file-upload-empty">
                                <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">اضغط للاختيار</span>
                                <span class="text-xs text-gray-500">أو اسحب الملف هنا</span>
                            </div>
                            <div x-show="loadingFiles['step1_agent_document_file']" class="file-upload-loading">
                                <svg class="animate-spin h-8 w-8 text-primary-600 mb-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">جاري المعالجة...</span>
                            </div>
                            <div x-show="formData.step1.agent_document_file && !loadingFiles['step1_agent_document_file']" class="file-upload-success">
                                <svg class="w-10 h-10 text-success-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">تم اختيار الملف</span>
                            </div>
                        </label>
                        <div x-show="formData.step1.agent_document_file && !loadingFiles['step1_agent_document_file']" class="file-info-display">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-700 truncate flex-1" x-text="formData.step1.agent_document_file?.name"></span>
                                <span class="text-xs text-gray-500" x-text="formatFileSize(formData.step1.agent_document_file?.size)"></span>
                            </div>
                        </div>
                    </div>
                    <p x-show="getFieldError('step1', 'agent_document_file')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step1', 'agent_document_file')"></p>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="button" 
                            @click="nextStep(1)" 
                            :disabled="isSubmitting"
                            class="btn-primary">
                        <span x-show="!isSubmitting">التالي</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            جاري المعالجة...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Step 2: Legal Documents -->
            <div x-show="currentStep === 2" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="w-full space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                    <!-- Commercial Register Number -->
                    <div class="form-group w-full" :class="{ 'has-error': getFieldError('step2', 'commercial_register_number') }">
                        <label class="form-label w-full">
                            <span class="text-danger-600">*</span>
                            رقم السجل التجاري
                        </label>
                        <input type="text" 
                               x-model="formData.step2.commercial_register_number"
                               @blur="validateField('step2', 'commercial_register_number')"
                               class="form-input w-full"
                               :class="{ 'border-danger-600': getFieldError('step2', 'commercial_register_number') }"
                               required>
                        <p x-show="getFieldError('step2', 'commercial_register_number')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step2', 'commercial_register_number')"></p>
                    </div>

                    <!-- Start Date -->
                    <div class="form-group w-full" :class="{ 'has-error': getFieldError('step2', 'commercial_register_start_date') }">
                        <label class="form-label w-full">
                            <span class="text-danger-600">*</span>
                            تاريخ البداية
                        </label>
                        <input type="date" 
                               x-model="formData.step2.commercial_register_start_date"
                               @blur="validateField('step2', 'commercial_register_start_date')"
                               class="form-input w-full"
                               :class="{ 'border-danger-600': getFieldError('step2', 'commercial_register_start_date') }"
                               required>
                        <p x-show="getFieldError('step2', 'commercial_register_start_date')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step2', 'commercial_register_start_date')"></p>
                    </div>

                    <!-- End Date -->
                    <div class="form-group w-full" :class="{ 'has-error': getFieldError('step2', 'commercial_register_end_date') }">
                        <label class="form-label w-full">
                            <span class="text-danger-600">*</span>
                            تاريخ النهاية
                        </label>
                        <input type="date" 
                               x-model="formData.step2.commercial_register_end_date"
                               @blur="validateField('step2', 'commercial_register_end_date')"
                               class="form-input w-full"
                               :class="{ 'border-danger-600': getFieldError('step2', 'commercial_register_end_date') }"
                               required>
                        <p x-show="getFieldError('step2', 'commercial_register_end_date')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step2', 'commercial_register_end_date')"></p>
                    </div>
                </div>

                <!-- File Uploads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                    <template x-for="(fileField, index) in [
                        { key: 'establishment_contract_file', label: 'عقد التأسيس والنظام الأساسي', id: 'file1' },
                        { key: 'commercial_register_extract_file', label: 'مستخرج حديث من السجل التجاري', id: 'file2' },
                        { key: 'activity_license_file', label: 'ترخيص مزاولة النشاط', id: 'file3' },
                        { key: 'chamber_registration_file', label: 'شهادة قيد بالغرفة التجارية', id: 'file4' },
                        { key: 'tax_certificate_file', label: 'شهادة الضريبة', id: 'file5' },
                        { key: 'social_security_certificate_file', label: 'شهادة الضمان الاجتماعي', id: 'file6' }
                    ]" :key="index">
                        <div class="form-group w-full">
                            <label class="form-label w-full">
                                <span class="text-danger-600">*</span>
                                <span x-text="fileField.label"></span>
                                <span class="text-xs text-gray-500 font-normal">(PDF)</span>
                            </label>
                            <div class="file-upload-wrapper w-full" 
                                 :class="{ 
                                     'has-file': formData.step2[fileField.key],
                                     'has-error': getFieldError('step2', fileField.key),
                                     'is-loading': loadingFiles['step2_' + fileField.key]
                                 }">
                                <input type="file" 
                                       @change="handleFile($event, 'step2', fileField.key, 'step2_' + fileField.key)"
                                       accept=".pdf"
                                       class="hidden"
                                       :id="fileField.id"
                                       required>
                                <label :for="fileField.id" class="file-upload-label">
                                    <div x-show="!formData.step2[fileField.key] && !loadingFiles['step2_' + fileField.key]" class="file-upload-empty">
                                        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">اضغط للاختيار</span>
                                        <span class="text-xs text-gray-500">أو اسحب الملف هنا</span>
                                    </div>
                                    <div x-show="loadingFiles['step2_' + fileField.key]" class="file-upload-loading">
                                        <svg class="animate-spin h-8 w-8 text-primary-600 mb-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">جاري المعالجة...</span>
                                    </div>
                                    <div x-show="formData.step2[fileField.key] && !loadingFiles['step2_' + fileField.key]" class="file-upload-success">
                                        <svg class="w-10 h-10 text-success-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">تم اختيار الملف</span>
                                    </div>
                                </label>
                                <div x-show="formData.step2[fileField.key] && !loadingFiles['step2_' + fileField.key]" class="file-info-display">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700 truncate flex-1" x-text="formData.step2[fileField.key]?.name"></span>
                                        <span class="text-xs text-gray-500" x-text="formatFileSize(formData.step2[fileField.key]?.size)"></span>
                                    </div>
                                </div>
                            </div>
                            <p x-show="getFieldError('step2', fileField.key)" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step2', fileField.key)"></p>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between pt-4">
                    <button type="button" 
                            @click="prevStep()" 
                            :disabled="isSubmitting"
                            class="btn-secondary">
                        السابق
                    </button>
                    <button type="button" 
                            @click="nextStep(2)" 
                            :disabled="isSubmitting"
                            class="btn-primary">
                        <span x-show="!isSubmitting">التالي</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            جاري المعالجة...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Step 3: Technical Documents -->
            <div x-show="currentStep === 3" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="w-full space-y-6">
                
                <!-- Experience Level -->
                <div class="form-group w-full" :class="{ 'has-error': getFieldError('step3', 'experience_level') }">
                    <label class="form-label">
                        <span class="text-danger-600">*</span>
                        خبرة الشركة
                    </label>
                    <select x-model="formData.step3.experience_level"
                            @blur="validateField('step3', 'experience_level')"
                            class="form-input"
                            :class="{ 'border-danger-600': getFieldError('step3', 'experience_level') }"
                            required>
                        <option value="">اختر مستوى الخبرة...</option>
                        <option value="0-3">0-3 سنوات</option>
                        <option value="4-10">4-10 سنوات</option>
                        <option value="more_than_10">أكثر من 10 سنوات</option>
                    </select>
                    <p x-show="getFieldError('step3', 'experience_level')" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step3', 'experience_level')"></p>
                </div>

                <!-- File Uploads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                    <template x-for="(fileField, index) in [
                        { key: 'completed_projects_file', label: 'ملف المشاريع المنفذة', id: 'file7' },
                        { key: 'technical_staff_file', label: 'ملف الكادر الفني', id: 'file8' },
                        { key: 'quality_certificates_file', label: 'شهادات الجودة والاعتماد', id: 'file9' }
                    ]" :key="index">
                        <div class="form-group w-full">
                            <label class="form-label w-full">
                                <span class="text-danger-600">*</span>
                                <span x-text="fileField.label"></span>
                                <span class="text-xs text-gray-500 font-normal">(PDF)</span>
                            </label>
                            <div class="file-upload-wrapper w-full" 
                                 :class="{ 
                                     'has-file': formData.step3[fileField.key],
                                     'has-error': getFieldError('step3', fileField.key),
                                     'is-loading': loadingFiles['step3_' + fileField.key]
                                 }">
                                <input type="file" 
                                       @change="handleFile($event, 'step3', fileField.key, 'step3_' + fileField.key)"
                                       accept=".pdf"
                                       class="hidden"
                                       :id="fileField.id"
                                       required>
                                <label :for="fileField.id" class="file-upload-label">
                                    <div x-show="!formData.step3[fileField.key] && !loadingFiles['step3_' + fileField.key]" class="file-upload-empty">
                                        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">اضغط للاختيار</span>
                                        <span class="text-xs text-gray-500">أو اسحب الملف هنا</span>
                                    </div>
                                    <div x-show="loadingFiles['step3_' + fileField.key]" class="file-upload-loading">
                                        <svg class="animate-spin h-8 w-8 text-primary-600 mb-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">جاري المعالجة...</span>
                                    </div>
                                    <div x-show="formData.step3[fileField.key] && !loadingFiles['step3_' + fileField.key]" class="file-upload-success">
                                        <svg class="w-10 h-10 text-success-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">تم اختيار الملف</span>
                                    </div>
                                </label>
                                <div x-show="formData.step3[fileField.key] && !loadingFiles['step3_' + fileField.key]" class="file-info-display">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700 truncate flex-1" x-text="formData.step3[fileField.key]?.name"></span>
                                        <span class="text-xs text-gray-500" x-text="formatFileSize(formData.step3[fileField.key]?.size)"></span>
                                    </div>
                                </div>
                            </div>
                            <p x-show="getFieldError('step3', fileField.key)" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step3', fileField.key)"></p>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between pt-4">
                    <button type="button" 
                            @click="prevStep()" 
                            :disabled="isSubmitting"
                            class="btn-secondary">
                        السابق
                    </button>
                    <button type="button" 
                            @click="nextStep(3)" 
                            :disabled="isSubmitting"
                            class="btn-primary">
                        <span x-show="!isSubmitting">التالي</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            جاري المعالجة...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Step 4: Financial Documents -->
            <div x-show="currentStep === 4" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="w-full space-y-6">
                
                <!-- File Uploads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                    <template x-for="(fileField, index) in [
                        { key: 'financial_statements_file', label: 'بيانات مالية لآخر 3 سنوات', id: 'file10' },
                        { key: 'solvency_certificate_file', label: 'شهادة ملاءة مالية من المصرف', id: 'file11' }
                    ]" :key="index">
                        <div class="form-group w-full">
                            <label class="form-label w-full">
                                <span class="text-danger-600">*</span>
                                <span x-text="fileField.label"></span>
                                <span class="text-xs text-gray-500 font-normal">(PDF)</span>
                            </label>
                            <div class="file-upload-wrapper w-full" 
                                 :class="{ 
                                     'has-file': formData.step4[fileField.key],
                                     'has-error': getFieldError('step4', fileField.key),
                                     'is-loading': loadingFiles['step4_' + fileField.key]
                                 }">
                                <input type="file" 
                                       @change="handleFile($event, 'step4', fileField.key, 'step4_' + fileField.key)"
                                       accept=".pdf"
                                       class="hidden"
                                       :id="fileField.id"
                                       required>
                                <label :for="fileField.id" class="file-upload-label">
                                    <div x-show="!formData.step4[fileField.key] && !loadingFiles['step4_' + fileField.key]" class="file-upload-empty">
                                        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">اضغط للاختيار</span>
                                        <span class="text-xs text-gray-500">أو اسحب الملف هنا</span>
                                    </div>
                                    <div x-show="loadingFiles['step4_' + fileField.key]" class="file-upload-loading">
                                        <svg class="animate-spin h-8 w-8 text-primary-600 mb-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">جاري المعالجة...</span>
                                    </div>
                                    <div x-show="formData.step4[fileField.key] && !loadingFiles['step4_' + fileField.key]" class="file-upload-success">
                                        <svg class="w-10 h-10 text-success-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">تم اختيار الملف</span>
                                    </div>
                                </label>
                                <div x-show="formData.step4[fileField.key] && !loadingFiles['step4_' + fileField.key]" class="file-info-display">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700 truncate flex-1" x-text="formData.step4[fileField.key]?.name"></span>
                                        <span class="text-xs text-gray-500" x-text="formatFileSize(formData.step4[fileField.key]?.size)"></span>
                                    </div>
                                </div>
                            </div>
                            <p x-show="getFieldError('step4', fileField.key)" class="mt-1 text-sm text-danger-600" x-text="getFieldError('step4', fileField.key)"></p>
                        </div>
                    </template>
                </div>

                <!-- Technical Notes -->
                <div class="form-group w-full">
                    <label class="form-label">ملاحظات تقنية (اختياري)</label>
                    <textarea x-model="formData.step4.technical_notes" 
                              class="form-input w-full"
                              rows="4"></textarea>
                </div>

                <div class="flex justify-between pt-4">
                    <button type="button" 
                            @click="prevStep()" 
                            :disabled="isSubmitting"
                            class="btn-secondary">
                        السابق
                    </button>
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="btn-success">
                        <span x-show="!isSubmitting" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            إرسال الطلب
                        </span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            جاري الإرسال...
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Success Modal -->
        <div x-show="success" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-success-600 text-white p-6 rounded-t-xl text-center">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold" x-text="successMessage"></h3>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الطلب:</label>
                        <div class="flex items-center gap-2 bg-gray-50 border border-gray-300 rounded-lg p-3">
                            <input type="text" 
                                   :value="requestNumber" 
                                   readonly
                                   id="requestNumberInput"
                                   class="flex-1 bg-transparent border-none outline-none text-lg font-bold text-gray-900">
                            <button @click="copyRequestNumber()" 
                                    type="button"
                                    class="btn-primary text-sm py-2 px-3">
                                <span x-show="!copied">نسخ</span>
                                <span x-show="copied">تم!</span>
                            </button>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6 text-center">
                        تم حفظ رقم الطلب بنجاح. يمكنك استخدامه للاستعلام عن حالة الطلب لاحقاً.
                    </p>
                    
                    <button @click="resetForm()" 
                            type="button"
                            class="w-full btn-primary">
                        تسجيل جديد
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
