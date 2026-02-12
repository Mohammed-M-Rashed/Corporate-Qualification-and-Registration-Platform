<div class="w-full">
    <div class="bg-gray-100 rounded-lg p-4 mb-4">
        <p class="text-sm text-gray-600 mb-2">يمكنك التكبير والتصغير والتمرير داخل الملف</p>
        <div class="flex gap-2">
            <a href="{{ $fileUrl }}" 
               target="_blank" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                فتح في نافذة جديدة
            </a>
            <a href="{{ $fileUrl }}" 
               download 
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                تحميل
            </a>
        </div>
    </div>
    <div class="border-2 border-gray-300 rounded-lg overflow-hidden" style="height: 80vh;">
        <iframe 
            src="{{ $fileUrl }}#toolbar=1&navpanes=1&scrollbar=1" 
            class="w-full h-full"
            frameborder="0"
            style="min-height: 600px;">
            <p class="p-4 text-center text-gray-600">
                المتصفح لا يدعم معاينة PDF. 
                <a href="{{ $fileUrl }}" target="_blank" class="text-blue-500 hover:underline">اضغط هنا لفتح الملف</a>
            </p>
        </iframe>
    </div>
</div>

