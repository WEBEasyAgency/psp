@props(['seo' => null])

@if($seo)
<div class="seo__section">
    <div class="seo__container">
        <div class="seo__wrapper">
            <div class="seo__left-column">
                <div class="seo__title">{{ $seo['title'] }}</div>
                @if(isset($seo['subtitle']))
                <div class="seo__subtitle">{{ $seo['subtitle'] }}</div>
                @endif
            </div>
            <div class="seo__text">{!! $seo['text'] !!}</div>
        </div>
    </div>
</div>
@endif
