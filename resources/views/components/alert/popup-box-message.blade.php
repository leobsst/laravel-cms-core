<div>
    <div
        class="fixed bg-black/40 w-full h-full z-50 top-0"
        x-data="{displayAlertBox: $persist(true).as('displayAlertBox')}"
        x-show="displayAlertBox"
        x-cloak
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div
            class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-4/5 md:w-3/4
                bg-zinc-100 rounded-md shadow-xl h-4/5 md:h-96 p-4"
            x-cloak
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-0"
            x-show="displayAlertBox">
            <div class="flex justify-end">
                <button
                    type="button"
                    role="button"
                    title="Fermer"
                    x-on:click="displayAlertBox = false"
                    class="inline-flex rounded-md p-1.5 tefocus:outline-none focus:ring-2 focus:ring-offset-2 ">
                    <span class="sr-only">Fermer</span>
                    <!-- Heroicon name: solid/x -->
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                        <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="flex items-center justify-center">
                <div class="text-xl md:text-2xl">
                    {!! nl2br(Setting::get('alert_box_message')) !!}
                </div>
            </div>
        </div>
    </div>
</div>
