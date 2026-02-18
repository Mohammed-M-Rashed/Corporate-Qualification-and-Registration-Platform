<section id="how-it-works" class="py-20 bg-[#f5f6f8]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold mb-3 text-[#0a1628]">كيف تعمل المنصة</h2>
            <p class="text-[#64748b]">خطوات بسيطة لتأهيل شركتك</p>
        </div>
        <div class="max-w-4xl mx-auto">
            <div class="hidden md:flex items-start">
                <div class="flex-1 text-center">
                    <div class="step-num-circle">1</div>
                    <h3 class="text-lg font-bold mb-1 text-[#0a1628]">التسجيل</h3>
                    <p class="text-[#64748b] text-sm px-2">أكمل نموذج التسجيل بجميع البيانات والوثائق المطلوبة</p>
                </div>
                <div class="step-connector mt-7"></div>
                <div class="flex-1 text-center">
                    <div class="step-num-circle">2</div>
                    <h3 class="text-lg font-bold mb-1 text-[#0a1628]">المراجعة</h3>
                    <p class="text-[#64748b] text-sm px-2">تقوم اللجان المختصة بمراجعة طلبك والوثائق المرفقة</p>
                </div>
                <div class="step-connector mt-7"></div>
                <div class="flex-1 text-center">
                    <div class="step-num-circle">3</div>
                    <h3 class="text-lg font-bold mb-1 text-[#0a1628]">التأهيل</h3>
                    <p class="text-[#64748b] text-sm px-2">بعد الموافقة يتم تأهيل شركتك وإصدار رقم الطلب</p>
                </div>
                <div class="step-connector mt-7"></div>
                <div class="flex-1 text-center">
                    <div class="step-num-circle">4</div>
                    <h3 class="text-lg font-bold mb-1 text-[#0a1628]">المتابعة</h3>
                    <p class="text-[#64748b] text-sm px-2">استعلم عن حالة طلبك في أي وقت عبر رقم الطلب</p>
                </div>
            </div>
            <!-- Mobile: stacked -->
            <div class="md:hidden space-y-6">
                @foreach([
                    ['num' => '1', 'title' => 'التسجيل', 'desc' => 'أكمل نموذج التسجيل بجميع البيانات والوثائق المطلوبة'],
                    ['num' => '2', 'title' => 'المراجعة', 'desc' => 'تقوم اللجان المختصة بمراجعة طلبك والوثائق المرفقة'],
                    ['num' => '3', 'title' => 'التأهيل', 'desc' => 'بعد الموافقة يتم تأهيل شركتك وإصدار رقم الطلب'],
                    ['num' => '4', 'title' => 'المتابعة', 'desc' => 'استعلم عن حالة طلبك في أي وقت عبر رقم الطلب'],
                ] as $step)
                <div class="flex items-start gap-4">
                    <div class="step-num-circle flex-shrink-0" style="margin: 0;">{{ $step['num'] }}</div>
                    <div>
                        <h3 class="text-lg font-bold text-[#0a1628]">{{ $step['title'] }}</h3>
                        <p class="text-[#64748b] text-sm">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
