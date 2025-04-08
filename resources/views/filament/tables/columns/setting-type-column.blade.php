<div>
    @switch($getRecord()->type)
        @case(Leobsst\LaravelCmsCore\Enums\SettingTypeEnum::STRING)
        @case(Leobsst\LaravelCmsCore\Enums\SettingTypeEnum::TEXTAREA)
            {{ mb_strimwidth($getState(), 0, 80, '..') }}
            @break
        @case(Leobsst\LaravelCmsCore\Enums\SettingTypeEnum::TAGS)
            <div class="max-w-md whitespace-normal py-3">
            @foreach(json_decode($getRecord()->tags, true) as $tag)
                <span
                    style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"
                    class="fi-badge rounded-md whitespace-nowrap text-xs mx-1 font-medium ring-1 ring-inset px-2 w-fit py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-primary">
                    {{ $tag['name']['fr'] }}
                </span>
            @endforeach
            </div>
            @break
        @case(Leobsst\LaravelCmsCore\Enums\SettingTypeEnum::COLOR)
            <span class="block w-8 h-8 rounded-full" style="background-color: {{$getState()}};">&nbsp;</span>
            @break
        @case(Leobsst\LaravelCmsCore\Enums\SettingTypeEnum::IMAGE)
            @if (filled($getState()))
            <img src="{{ $getState() }}" alt="{{ $getRecord()->name }}" class="w-12 h-12 rounded-md">
            @endif
            @break
        @case(Leobsst\LaravelCmsCore\Enums\SettingTypeEnum::BOOLEAN)
            @if($getState() == '1')
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                style="--c-400:var(--success-400);--c-500:var(--success-500);"
                class="fi-ta-icon-item fi-ta-icon-item-size-lg h-6 w-6 fi-color-custom text-custom-500 dark:text-custom-400 fi-color-success">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            @elseif($getState() == '0')
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                style="--c-400:var(--danger-400);--c-500:var(--danger-500);"
                class="fi-ta-icon-item fi-ta-icon-item-size-lg h-6 w-6 fi-color-custom text-custom-500 dark:text-custom-400 fi-color-danger">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            @endif
            @break
        @default
            {{ $getState() }}
            @break
    @endswitch
</div>
