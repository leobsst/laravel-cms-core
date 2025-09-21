<div
    class="w-full h-48 md:h-64"
    x-data="{offset: 0}"
    @scroll.window="if (window.scrollY < 300) {
        document.getElementById('header_bg').style.transform = 'translateY(-' + window.scrollY / 5 + 'px) scale(1.30)';
        document.getElementById('header_content').style.transform = 'translateY(-' + window.scrollY / 6 + 'px)';
    }">
    <div class="relative w-full h-full">
        <span class="absolute left-0 top-0 w-full h-full bg-black"></span>
        <img
            src="{{ optimized($page->banner) }}"
            id="header_bg"
            alt="main-bg"
            class="w-full h-full object-cover opacity-50 z-10 scale-[1.30]" />
        <span class="absolute left-0 top-0 w-full h-full backdrop-blur-sm"></span>
        <div
            id="header_content"
            class="absolute flex flex-col top-5 w-full h-full text-white justify-center items-center left-0 right-0 m-auto text-center">
            <h1 class="global-h1 mb-0 text-4xl lg:text-5xl">{{$page->title}}</h1>
        </div>
    </div>
</div>
