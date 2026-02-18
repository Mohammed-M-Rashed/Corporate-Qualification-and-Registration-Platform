<footer class="bg-[#0a1628] text-white py-14">
    <div class="container mx-auto px-6">
        <div class="text-center mb-10">
            @php
                $logoPath = \App\Models\Setting::where('key', 'system_logo')->value('value');
                $logoUrl = $logoPath ? asset($logoPath) : null;
            @endphp
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="شعار المؤسسة" class="h-16 w-auto mx-auto mb-3">
            @else
                <div class="w-16 h-16 bg-white/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <span class="text-white font-bold text-xl">NOC</span>
                </div>
            @endif
            <p class="text-white font-bold text-lg">National Oil Corporation</p>
            <p class="text-gray-400 text-sm">المؤسسة الوطنية للنفط</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-3xl mx-auto text-center mb-10">
            <div>
                <h3 class="text-sm font-bold mb-4 text-white">المؤسسة</h3>
                <ul class="space-y-2">
                    <li><a href="#about" class="text-gray-400 hover:text-white transition text-sm">عن المؤسسة</a></li>
                    <li><a href="#features" class="text-gray-400 hover:text-white transition text-sm">المميزات</a></li>
                    <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition text-sm">كيف تعمل</a></li>
                    <li><a href="#stats" class="text-gray-400 hover:text-white transition text-sm">إحصائيات</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-bold mb-4 text-white">خدماتنا</h3>
                <ul class="space-y-2">
                    <li><a href="#register" class="text-gray-400 hover:text-white transition text-sm">تسجيل شركة</a></li>
                    <li><a href="#inquiry" class="text-gray-400 hover:text-white transition text-sm">استعلام عن طلب</a></li>
                    <li><a href="#faq" class="text-gray-400 hover:text-white transition text-sm">الأسئلة الشائعة</a></li>
                    <li><a href="#support" class="text-gray-400 hover:text-white transition text-sm">الدعم</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-bold mb-4 text-white">تواصل معنا</h3>
                <ul class="space-y-2">
                    <li class="text-gray-400 text-sm">طرابلس، ليبيا</li>
                    <li><a href="https://noc.ly/" target="_blank" class="text-gray-400 hover:text-white transition text-sm">noc.ly</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 pt-6 text-center">
            <p class="text-gray-500 text-sm">حقوق النشر محفوظة &copy; {{ date('Y') }} المؤسسة الوطنية للنفط</p>
        </div>
    </div>
</footer>
