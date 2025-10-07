@props(['name', 'id'])

<div>
    <div
        class="flex mt-2 {{$attributes->get('class') ?? 'flex-col'}}"
        x-data="{value: $wire.entangle('{{$id}}')}"
    >
        @if ($attributes->has("label"))
        <label for="{{$name}}" class="global-label {{$attributes->get('label-class') ?? null}}">
            {!! $attributes->get("label") !!}
        </label>
        @endif

        <button
            type="button"
            x-bind:class="value ? 'bg-blue-500' : 'bg-gray-200'"
            x-on:click="value = !value"
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer
            rounded-full border-2 border-transparent transition-colors duration-200
            ease-in-out focus:outline-none ring-0 {{ !$attributes->has('no-shadow') ? 'shadow-lg' : null }}"
            role="switch"
            aria-checked="false"
        >
            <span class="sr-only">Dark mode toggle</span>
            <span
                x-bind:class="value ? 'translate-x-5 bg-gray-700' : 'translate-x-0 bg-white'"
                class="pointer-events-none relative inline-block h-5 w-5
                transform rounded-full shadow ring-0 transition duration-200 ease-in-out"
            >
                <span
                    x-bind:class="value ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'"
                    class="absolute inset -0 flex h-full w-full items-center justify-center transition-opacity"
                    aria-hidden="true"
                >
                    &nbsp;
                </span>
                <span
                    x-bind:class="value ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'"
                    class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity"
                    aria-hidden="true"
                >
                    &nbsp;
                </span>
            </span>
        </button>
    </div>
</div>