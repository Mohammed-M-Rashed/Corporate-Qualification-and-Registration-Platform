<div
    wire:loading.delay
    class="fi-loading-overlay fixed inset-0 z-[9999] flex items-center justify-center bg-black/30"
>
    <div class="fi-loading-box-container">
        <div class="fi-loading-box-shimmer"></div>
        <div class="fi-loading-box-content">
            @if($gifUrl ?? null)
                <img src="{{ $gifUrl }}" alt="جاري التحميل" class="fi-loading-box-gif">
            @else
                <div class="fi-loading-box-spinner"></div>
            @endif
            <p class="fi-loading-box-text">جاري التحميل...</p>
        </div>
    </div>
</div>
