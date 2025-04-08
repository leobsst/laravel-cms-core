<div>
    @if ($attributes->has('name'))
        @error($attributes->get('name'))
        <span class="global-span text-red-700 dark:text-red-300 font-normal">{{ $message }}</span>
        @enderror
    @else
        @if (session()->has('error'))
        <span class="global-span text-red-700 dark:text-red-300 font-normal">{{session('error')}}</span>
        @endif
    @endif
</div>
