@props(['name', 'id'])

<div>
    <div class="flex flex-col mt-2 {{$attributes->get('class') ?? null}}">
        @if ($attributes->has("label"))
        <label for="{{$name}}" class="global-label">{!! $attributes->get("label") !!}</label>
        @endif

        <div class="relative" x-data="{hidden: true}">
            @if (!$attributes->has('no-icon'))
                @if ($attributes->has('type') && $attributes->get('type') === 'email')
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-5 h-5 global-icon absolute left-2 top-1.5">
                    <path
                        d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z"
                    />
                    <path
                        d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z"
                    />
                </svg>
                @elseif($attributes->has('type') && $attributes->get('type') === 'tel')
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-5 h-5 global-icon absolute left-2 top-1.5">
                    <path
                        fill-rule="evenodd"
                        d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                        clip-rule="evenodd" />
                </svg>
                @endif
            @endif
            <input
                @if ($attributes->has('type') && $attributes->get('type') === 'password')
                x-bind:type="hidden ? 'password' : 'text'"
                @else
                type="{{
                    $attributes->has('type')
                    && in_array($attributes->get('type'), ['text', 'email', 'tel'])
                    ? $attributes->get('type') : 'text'
                }}"
                @endif
                @if($attributes->has('type') && $attributes->get('type') === 'tel')
                pattern="[0-9]{10}"
                @endif

                id="{{$name}}"
                class="global-input
                {{
                    $attributes->has('type')
                    && in_array($attributes->get('type'), ['tel', 'email'])
                    && !$attributes->has('no-icon')
                    ? 'pl-8' : null
                }}
                {{$attributes->has('type') && $attributes->get('type') === 'password' ? 'pr-8' : null}}
                "
                @if ($attributes->has('placeholder'))
                placeholder="{{$attributes->get('placeholder')}}"
                @endif
                @if ($attributes->has('maxlength'))
                maxlength="{{$attributes->get('maxlength')}}"
                @endif
                wire:model.defer="{{$id}}"
                value="{{ old($id) }}"
                wire:loading.attr="disabled"
                @if($attributes->has('required'))
                required
                @endif
            >
            @if ($attributes->has('type') && $attributes->get('type') === 'password')
            <button
                class="absolute right-2 top-1.5"
                x-on:click="hidden = !hidden"
                type="button"
                title="Afficher/Cacher le mot de passe"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-5 h-5 global-icon"
                    x-show="hidden"
                >
                    <path
                        d="M12 15a3 3 0 100-6 3 3 0 000 6z"
                    />
                    <path
                        fill-rule="evenodd"
                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"
                        clip-rule="evenodd"
                    />
                </svg>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-5 h-5 global-icon"
                    x-show="!hidden"
                >
                    <path
                        d="M3.53 2.47a.75.75 0 00-1.06 1.06l18 18a.75.75 0 101.06-1.06l-18-18zM22.676 12.553a11.249 11.249 0 01-2.631 4.31l-3.099-3.099a5.25 5.25 0 00-6.71-6.71L7.759 4.577a11.217 11.217 0 014.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113z"
                    />
                    <path
                        d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0115.75 12zM12.53 15.713l-4.243-4.244a3.75 3.75 0 004.243 4.243z"
                    />
                    <path
                        d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 00-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 016.75 12z"
                    />
                </svg>
            </button>
            @endif
        </div>
    </div>
</div>
