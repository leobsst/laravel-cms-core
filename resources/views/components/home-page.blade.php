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
            style="opacity: @pageOption($page->slug, 'background_opacity', 0.8); transform: scale(1.25);" />
        <span
            class="absolute left-0 top-0 w-full h-screen"
            style="backdrop-filter: blur(@pageOption($page->slug, 'background_blur', 8)px);"></span>
        <div
            id="header_content"
            class="absolute flex flex-col lg:top-28 w-11/12 xl:w-3/4 h-full text-white justify-center left-0 right-0 m-auto title"
            style="top: calc(var(--spacing) * @pageOption($page->slug, 'header_content_top', 38));">
            <h2 class="md:text-center text-4xl lg:text-6xl">@pageOption($page->slug, 'title', 'Bienvenue !')</h2>
@pageOptionExists($page->slug, 'subtitle')
            <h3 class="mt-2 md:text-center text-xl lg:text-3xl">@pageOption($page->slug, 'subtitle')</h3>
@endpageOptionExists
        </div>
@pageOptionExists($page->slug, 'discover_link')
        <a href="@pageOption($page->slug, 'discover_link')" rel="follow">
            <button
                id="scroll_down"
                x-cloak
                x-data="{bgColor: '#fff', textColor: '#000'}"
                x-on:mouseover="bgColor = '{{Setting::get('primary_color')}}'; textColor = '#fff'"
                x-on:mouseleave="bgColor = '#fff'; textColor = '#000'"
                x-bind:style="{ 'background-color': bgColor, 'color': textColor}"
                class="absolute bottom-8 md:bottom-12 w-fit left-0 right-0 m-auto opacity-75 hover:opacity-100 transition duration-300 ease-in-out rounded-xl
                text-md lg:text-xl px-4 py-3 uppercase whitespace-nowrap anim-reveal-bottom">
                @pageOption($page->slug, 'discover_text', 'En savoir plus')
            </button>
        </a>
@endpageOptionExists
    </div>
</div>
