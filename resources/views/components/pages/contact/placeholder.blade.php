<div class="mb-8 mt-24">
    <div class="w-full flex lg:justify-between flex-col lg:flex-row">
        {{-- Contact form --}}
        <div class="flex flex-col w-full min-w-[18rem] sticky top-24 h-fit space-y-4">
            {{-- Title --}}
            <div class="w-60 h-10 rounded-md skeleton bg-zinc-200 mb-4"></div>

            {{-- Form --}}
            <div class="flex flex-col w-full space-y-2">
                <div class="w-2/5 h-8 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-full h-5 rounded-md skeleton bg-zinc-200"></div>
            </div>
    
            <div class="flex flex-col w-full space-y-2">
                <div class="w-2/5 h-8 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-full h-5 rounded-md skeleton bg-zinc-200"></div>
            </div>

            <div class="flex flex-col w-full space-y-2">
                <div class="w-2/5 h-8 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-full h-5 rounded-md skeleton bg-zinc-200"></div>
            </div>

            <div class="flex flex-col w-full space-y-2">
                <div class="w-2/5 h-8 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-full h-5 rounded-md skeleton bg-zinc-200"></div>
            </div>

            <div class="flex flex-col w-full space-y-2">
                <div class="w-2/5 h-8 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-full h-48 rounded-md skeleton bg-zinc-200"></div>
            </div>

            <div class="w-3/5 h-5 rounded-md skeleton bg-zinc-200"></div>
        </div>

        {{-- Location info --}}
        <div class="flex flex-col w-full lg:ml-8 lg:mt-0 mt-8">
            <div class="w-60 h-10 rounded-md skeleton bg-zinc-200 mb-4"></div>

            <div class="flex flex-col space-y-2.5">
                <div class="w-2/5 h-5 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-full h-5 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-4/5 h-5 rounded-md skeleton bg-zinc-200"></div>
            </div>

            <div class="mt-8 w-full flex flex-col space-y-2.5">
                <div class="w-4/5 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-3/5 h-4 rounded-md skeleton bg-zinc-200 mb-8"></div>
                <div class="w-4/6 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-5/6 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-3/6 h-4 rounded-md skeleton bg-zinc-200 mb-8"></div>
                <div class="w-4/5 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-3/5 h-4 rounded-md skeleton bg-zinc-200 mb-8"></div>
                <div class="w-4/6 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-5/6 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-3/6 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-4/6 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-5/6 h-4 rounded-md skeleton bg-zinc-200"></div>
                <div class="w-3/6 h-4 rounded-md skeleton bg-zinc-200"></div>
            </div>
        </div>

        @if($columns === 3)
        {{-- GPS --}}
        <div class="flex flex-col w-full lg:ml-8 lg:mt-0 mt-8 space-y-2.5">
            <div class="w-60 h-10 rounded-md skeleton bg-zinc-200 mb-4"></div>

            <div class="w-2/5 h-5 rounded-md skeleton bg-zinc-200"></div>
            <div class="w-2/5 h-5 rounded-md skeleton bg-zinc-200"></div>

            <div style="overflow:hidden;max-width:100%" class="md:w-[350px] w-full h-[350px] mt-7 space-y-2.5">
                <div class="w-full h-80 rounded-md skeleton bg-zinc-200"></div>
            </div>
        </div>
        @endif
    </div>
</div>