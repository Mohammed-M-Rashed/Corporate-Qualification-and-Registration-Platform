<nav class="site-nav sticky top-0 z-50">
    <div class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                @php
                    $logoPath = \App\Models\Setting::where('key', 'system_logo')->value('value');
                    $logoUrl = $logoPath ? asset($logoPath) : null;
                @endphp
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="شعار المؤسسة" class="h-14 w-auto">
                @else
                    <div class="w-14 h-14 bg-[#0a1628] rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">NOC</span>
                    </div>
                @endif
                <div class="hidden sm:block">
                    <span class="text-lg font-bold text-[#0a1628] block leading-tight">المؤسسة الوطنية للنفط</span>
                    <span class="text-xs text-[#64748b]">National Oil Corporation</span>
                </div>
            </div>
            <div class="hidden lg:flex items-center gap-1">
                <a href="#about" class="text-[#1e293b] hover:text-[#1a6fb5] transition text-sm font-medium px-3 py-2">من نحن</a>
                <a href="#features" class="text-[#1e293b] hover:text-[#1a6fb5] transition text-sm font-medium px-3 py-2">المميزات</a>
                <a href="#how-it-works" class="text-[#1e293b] hover:text-[#1a6fb5] transition text-sm font-medium px-3 py-2">كيف تعمل</a>
                <a href="#stats" class="text-[#1e293b] hover:text-[#1a6fb5] transition text-sm font-medium px-3 py-2">إحصائيات</a>
                <a href="#faq" class="text-[#1e293b] hover:text-[#1a6fb5] transition text-sm font-medium px-3 py-2">الأسئلة الشائعة</a>
                <a href="#inquiry" class="text-[#1e293b] hover:text-[#1a6fb5] transition text-sm font-medium px-3 py-2">استعلام</a>
                <a href="#register" class="btn-primary text-white mr-2">تسجيل شركة</a>
            </div>
            <button id="mobile-menu-button" class="lg:hidden text-[#0a1628] p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        <div id="mobile-menu" class="hidden lg:hidden mt-3 pt-3 border-t border-gray-100 space-y-1">
            <a href="#about" class="block text-[#1e293b] text-sm font-medium px-3 py-2 hover:bg-gray-50 rounded">من نحن</a>
            <a href="#features" class="block text-[#1e293b] text-sm font-medium px-3 py-2 hover:bg-gray-50 rounded">المميزات</a>
            <a href="#how-it-works" class="block text-[#1e293b] text-sm font-medium px-3 py-2 hover:bg-gray-50 rounded">كيف تعمل</a>
            <a href="#stats" class="block text-[#1e293b] text-sm font-medium px-3 py-2 hover:bg-gray-50 rounded">إحصائيات</a>
            <a href="#faq" class="block text-[#1e293b] text-sm font-medium px-3 py-2 hover:bg-gray-50 rounded">الأسئلة الشائعة</a>
            <a href="#inquiry" class="block text-[#1e293b] text-sm font-medium px-3 py-2 hover:bg-gray-50 rounded">استعلام</a>
            <a href="#register" class="block btn-primary text-white text-center mt-2">تسجيل شركة</a>
        </div>
    </div>
</nav>
