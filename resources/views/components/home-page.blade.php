<div
    class="w-full min-h-screen"
    x-data="{offset: 0}"
    @scroll.window="if (window.scrollY < 775) {
        document.getElementById('header_bg').style.transform = 'translateY(-' + window.scrollY / 6 + 'px) scale(1.30)';
        document.getElementById('header_content').style.transform = 'translateY(-' + window.scrollY / 8 + 'px)';
        document.getElementById('scroll_down').style.transform = 'translateY(-' + window.scrollY / 18 + 'px)';
    }">
    <div class="relative w-full h-full">
        <span class="absolute left-0 top-0 w-full h-screen bg-black"></span>
        <img
            src="{{asset('assets/img/main_bg_min.webp')}}"
            id="header_bg"
            alt="main-bg"
            class="w-full h-screen object-cover opacity-80 z-10 scale-[1.30] object-center" />
        <span class="absolute left-0 top-0 w-full h-screen backdrop-blur-sm"></span>
        <div
            id="header_content"
            class="absolute flex flex-col top-0 w-11/12 xl:w-3/4 h-full text-white justify-center left-0 right-0 m-auto title">
            <h2 class="text-6xl lg:text-8xl">Bienvenue !</h2>
        </div>
        <button
            id="scroll_down"
            x-cloak
            x-data="{bgColor: '#fff', textColor: '#000'}"
            x-on:mouseover="bgColor = '{{Setting::get('primary_color')}}'; textColor = '#fff'"
            x-on:mouseleave="bgColor = '#fff'; textColor = '#000'"
            x-bind:style="{ 'background-color': bgColor, 'color': textColor}"
            class="absolute bottom-12 w-fit left-0 right-0 m-auto opacity-75 hover:opacity-100 transition duration-300 ease-in-out rounded-xl
            text-md lg:text-xl px-4 py-3 uppercase whitespace-nowrap anim-reveal-bottom"
            onclick="document.getElementById('content').scrollIntoView()">
            En savoir plus
        </button>
    </div>
</div>
