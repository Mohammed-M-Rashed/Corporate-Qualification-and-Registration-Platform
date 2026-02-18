<section id="stats" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold mb-3 text-[#0a1628]">إحصائيات المنصة</h2>
            <p class="text-[#64748b]">أرقام تعكس ثقة الشركات بالمنصة</p>
        </div>
        @php
            $stats = $stats ?? [
                'companies_count' => 0,
                'requests_count' => 0,
                'approved_count' => 0,
            ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-3xl mx-auto">
            <div class="text-center">
                <div class="stat-circle">
                    <span class="text-3xl font-bold text-[#0a1628]">{{ number_format($stats['companies_count']) }}</span>
                    <span class="text-xs text-[#64748b] mt-1">شركة</span>
                </div>
                <p class="text-[#1e293b] font-medium">شركة مسجلة</p>
            </div>
            <div class="text-center">
                <div class="stat-circle">
                    <span class="text-3xl font-bold text-[#0a1628]">{{ number_format($stats['requests_count']) }}</span>
                    <span class="text-xs text-[#64748b] mt-1">طلب</span>
                </div>
                <p class="text-[#1e293b] font-medium">طلب تأهيل</p>
            </div>
            <div class="text-center">
                <div class="stat-circle">
                    <span class="text-3xl font-bold text-[#0a1628]">{{ number_format($stats['approved_count']) }}</span>
                    <span class="text-xs text-[#64748b] mt-1">طلب</span>
                </div>
                <p class="text-[#1e293b] font-medium">طلب مقبول</p>
            </div>
        </div>
    </div>
</section>
