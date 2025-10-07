<div>
    <div
        class="fixed top-28 right-10 z-[200] px-4 py-2 rounded-md shadow-md @switch($type)
        @case('error')
        bg-red-100
        @break
        @case('info')
        bg-yellow-100
        @break
        @default
        bg-green-100
        @endswitch"
        x-data="{ show: false }"
        x-cloak
        x-show="show"
        x-transition:enter="transition ease-in-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        @refresh-flash-{{$type}}.window="$wire.refreshFlash()"
        @display-flash-{{$type}}.window="show = true; setTimeout(() => show = false, 10000)"
        @hide-flash.window="show = false"
        x-init="$wire.refreshFlash()">
        <div class="flex items-center gap-x-2 py-1">
            <div class="flex-shrink-0">
                @switch($type)
                    @case('error')
                    <!-- Heroicon name: solid/x-circle -->
                    <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"/>
                    </svg>
                    @break
                    @case('info')
                    <!-- Heroicon name: solid/exclamation-triangle -->
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5 text-yellow-400">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>                      
                    @break
                    @default
                    <!-- Heroicon name: solid/check-circle -->
                    <svg class="w-5 h-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"/>
                    </svg>
                @endswitch
            </div>
            <p class="text-sm font-medium {{$this->getTextColor()}}">
                {!! $message !!}
            </p>
            <button
                type="button"
                role="button"
                title="@lang('close')"
                x-on:click="show = false"
                class="flex items-center justify-center rounded-md w-8 h-8 focus:outline-none focus:ring-2 focus:ring-offset-2 ml-2 @switch($type)
                @case('error')
                bg-red-100 text-red-500 hover:bg-red-200 focus:ring-offset-red-50 focus:ring-red-600
                @break
                @case('info')
                bg-yellow-100 text-yellow-500 hover:bg-yellow-200 focus:ring-offset-yellow-50 focus:ring-yellow-600
                @break
                @default
                bg-green-100 text-green-500 hover:bg-green-200 focus:ring-offset-green-50 focus:ring-green-600
                @endswitch">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-7 h-7">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18 18 6M6 6l12 12" />
                </svg>                  
            </button>
        </div>
    </div>
</div>
