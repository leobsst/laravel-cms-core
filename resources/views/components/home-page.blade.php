@php
    $page = \Leobsst\LaravelCmsCore\Models\Features\Pages\Page::where('is_home', true)->first(['banner', 'additional_data']);
    $additionalData = (object) collect($page->additional_data ?? [])
        ->pluck('value', 'key')
        ->toArray();
@endphp

<div
    class="w-full min-h-screen"
    x-data="{offset: 0}"
    @scroll.window="if (window.scrollY < 775) {
        document.getElementById('header_bg').style.transform = 'translateY(-' + window.scrollY / 6 + 'px) scale(1.25)';
        document.getElementById('header_content').style.transform = 'translateY(-' + window.scrollY / 8 + 'px)';
        document.getElementById('scroll_down').style.transform = 'translateY(-' + window.scrollY / 18 + 'px)';
    }">
    <div class="relative w-full h-full">
        <span class="absolute left-0 top-0 w-full h-screen bg-black"></span>
        <img
            src="{{$page->banner ? optimized($page->banner, 'uploads') : asset('assets/img/main_bg_min.webp')}}"
            id="header_bg"
            alt="main-bg"
            class="w-full h-screen object-cover z-10 transform object-center"
            style="opacity: {{ $additionalData->background_opacity ?? '0.8' }}; transform: scale(1.25);" />
        <span
            class="absolute left-0 top-0 w-full h-screen"
            style="backdrop-filter: blur({{ $additionalData->background_blur ?? '8' }}px);"></span>
        <div
            id="header_content"
            class="absolute flex flex-col top-32 lg:top-24 w-11/12 xl:w-3/4 h-full text-white justify-center left-0 right-0 m-auto title">
            <h2 class="md:text-center text-5xl lg:text-7xl">{{ $additionalData->title ?? 'Bienvenue !' }}</h2>
@if(isset($additionalData->subtitle))
            <h3 class="md:text-center text-2xl lg:text-4xl">{{ $additionalData->subtitle }}</h3>
@endif
        </div>
@if(isset($additionalData->discover_link))
        <a href="{{ $additionalData->discover_link }}" rel="follow">
            <button
                id="scroll_down"
                x-cloak
                x-data="{bgColor: '#fff', textColor: '#000'}"
                x-on:mouseover="bgColor = '{{Setting::get('primary_color')}}'; textColor = '#fff'"
                x-on:mouseleave="bgColor = '#fff'; textColor = '#000'"
                x-bind:style="{ 'background-color': bgColor, 'color': textColor}"
                class="absolute bottom-12 w-fit left-0 right-0 m-auto opacity-75 hover:opacity-100 transition duration-300 ease-in-out rounded-xl
                text-md lg:text-xl px-4 py-3 uppercase whitespace-nowrap anim-reveal-bottom">
                {{ $additionalData->discover_text ?? 'En savoir plus' }}
            </button>
        </a>
@endif
    </div>
</div>
