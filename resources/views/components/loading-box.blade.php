@props(['gifUrl' => null])

<div class="loading-box-container">
    <div class="loading-box-shimmer"></div>
    <div class="loading-box-content">
        @if($gifUrl)
            <img src="{{ $gifUrl }}" alt="" class="loading-box-gif" width="88" height="88" style="width:88px;height:88px;max-width:88px;max-height:88px;object-fit:contain;">
        @else
            <div class="loading-box-spinner"></div>
        @endif
    </div>
</div>
