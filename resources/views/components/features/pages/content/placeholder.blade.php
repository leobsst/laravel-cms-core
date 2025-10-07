<div class="mb-8 w-full h-fit overflow-y-hidden overflow-x-hidden content p-2 flex flex-col space-y-2.5">
    <!-- TITLE -->
    <div class="w-full flex items-center justify-center mb-12">
        <div class="w-60 h-10 rounded-md skeleton bg-zinc-200">&nbsp;</div>
    </div>

    <!-- CONTENT -->
    @for($i = 0; $i < 4; $i++)
        <div class="w-4/5 h-4 rounded-md skeleton bg-zinc-200"></div>
        <div class="w-3/5 h-4 rounded-md skeleton bg-zinc-200"></div>
        <div class="w-4/6 h-4 rounded-md skeleton bg-zinc-200"></div>
        <div class="w-5/6 h-4 rounded-md skeleton bg-zinc-200"></div>
        <div class="w-3/6 h-4 rounded-md skeleton bg-zinc-200"></div>
    @endfor
</div>