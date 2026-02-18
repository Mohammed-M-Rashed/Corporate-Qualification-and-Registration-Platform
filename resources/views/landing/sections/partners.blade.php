<section id="partners" class="py-20 bg-white border-t border-gray-200">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 fade-in">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900">شركاؤنا واعتماداتنا</h2>
            <p class="text-lg text-gray-600">منصة معتمدة من المؤسسة الوطنية للنفط - ليبيا</p>
        </div>
        <div class="flex flex-wrap justify-center items-center gap-12 max-w-4xl mx-auto fade-in-up">
            <div class="flex items-center gap-4">
                @php
                    $logoPath = \App\Models\Setting::where('key', 'system_logo')->value('value');
                    $logoUrl = $logoPath ? asset($logoPath) : null;
                @endphp
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="المؤسسة الوطنية للنفط" class="h-16 w-auto opacity-90">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold">NOC</span>
                    </div>
                @endif
                <span class="text-lg font-semibold text-gray-700">المؤسسة الوطنية للنفط</span>
            </div>
        </div>
    </div>
</section>
