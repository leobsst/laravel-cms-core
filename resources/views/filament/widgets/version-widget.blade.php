<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="fi-account-widget-main">
            <h2 class="fi-account-widget-heading">
                {{ Setting::get('website_name') }}
            </h2>
            <p class="fi-account-widget-user-name">
                v{{ config('cms-core.APP_VERSION') }}
            </p>
        </div>
        <form class="fi-account-widget-logout-form">
            <a
                href="mailto:support@leobsst.fr?subject=Signalement de bug sur {{ Setting::get('website_name') }}&body=Utilisateur : {{ Auth::user()->name }}%0D%0AVersion : {{ config('app.version') }}%0D%0A%0D%0A%0D%0ADescription du bug : "
                title="Signaler un bug"
                class="fi-btn relative grid-flow-col flex items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg  fi-btn-color-gray fi-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm sm:inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 [input:checked+&]:bg-gray-400 [input:checked+&]:text-white [input:checked+&]:ring-0 [input:checked+&]:hover:bg-gray-300 dark:[input:checked+&]:bg-gray-600 dark:[input:checked+&]:hover:bg-gray-500">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="fi-icon fi-size-md">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                Signaler un bug
            </a>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
