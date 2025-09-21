@pushOnce('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{config('core.RECAPTCHA_SITE_KEY')}}" async></script>
@endpushOnce
<div>
    <div class="mb-8 mt-24 p-4" wire:key='contact_{{uniqid()}}'>
        <div class="w-full flex lg:justify-between flex-col lg:flex-row">

            {{-- Contact form --}}
            <div class="flex flex-col w-full min-w-[18rem] sticky top-24 h-fit">
                <h1 class="global-h1 text-xl lg:text-2xl whitespace-nowrap">Contactez-nous</h1>

                <div class="flex flex-col w-full">
                    <x-laravel-cms-core::forms.input
                        id="name"
                        name="name"
                        label="Nom"
                        type="text"
                        class="w-full"
                        required
                    />
                    <div class="mt-2">
                        <x-laravel-cms-core::error name="name" />
                    </div>
                </div>
        
                <div class="flex flex-col w-full">
                    <x-laravel-cms-core::forms.input
                        id="email"
                        name="email"
                        label="Email"
                        type="email"
                        class="w-full"
                        required
                    />
                    <div class="mt-2">
                        <x-laravel-cms-core::error name="email" />
                    </div>
                </div>

                <div class="flex flex-col w-full">
                    <x-laravel-cms-core::forms.input
                        id="phone"
                        name="phone"
                        label="Téléphone"
                        type="tel"
                        class="w-full"
                        required
                    />
                    <div class="mt-2">
                        <x-laravel-cms-core::error name="phone" />
                    </div>
                </div>

                <div class="flex flex-col w-full">
                    <x-laravel-cms-core::forms.input
                        id="subject"
                        name="subject"
                        label="Sujet"
                        type="text"
                        class="w-full"
                    />
                    <div class="mt-2">
                        <x-laravel-cms-core::error name="subject" />
                    </div>
                </div>

                <div class="flex flex-col w-full">
                    <x-laravel-cms-core::forms.textarea
                        id="message"
                        type="text"
                        name="message"
                        label="Message"
                        class="w-full"
                        required
                        resize-none
                        rows="5"
                    />
                    <div class="mt-2">
                        <x-laravel-cms-core::error name="message" />
                    </div>
                </div>

                <div x-data="{consent: false}">
                    <div class="flex mt-4">
                        <input
                            type="checkbox"
                            class="global-checkbox"
                            style="color: {{Setting::get('primary_color')}};"
                            name="consent"
                            required
                            x-model="consent"
                        />
                        <label for="consent" class="global-label text-xs ml-4">
                            En soumettant ce formulaire, <br>
                            j'accepte que les informations saisies
                            dans ce formulaire soient utilisées pour permettre de me recontacter.
                        </label>
                    </div>

                    <button
                        x-show="consent"
                        x-bind:disabled="!consent"
                        @if(filled(config('core.RECAPTCHA_SITE_KEY')) && filled(config('core.RECAPTCHA_SECRET_KEY')))
                        x-on:click="() => {
                            grecaptcha.ready(() => {
                                grecaptcha.execute('{{config('core.RECAPTCHA_SITE_KEY')}}').then((token) => {
                                    @this.set('captcha', token);
                                    @this.call('sendMail');
                                });
                            });
                        }"
                        @else
                        wire:click="sendMail"
                        @endif
                        x-cloak
                        wire:loading.attr="disabled"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        type="button"
                        wire:loading.attr="disabled"
                        class="hover:opacity-75 transition text-white font-bold uppercase py-2 px-4 rounded-md mt-4 flex items-center"
                        style="background-color: {{Setting::get('primary_color_dark')}};"
                        title="Envoyer">
                        <x-laravel-cms-core::loader wire:loading />
                        Envoyer
                    </button>
                </div>
            </div>

            {{-- Location info --}}
            <div class="flex flex-col w-full lg:ml-8 lg:mt-0 mt-8">
                <h2 class="global-h2 text-2xl lg:text-4xl">
                    {{Setting::get('website_name')}}
                </h2>

                <p class="global-p flex flex-col">
                    <span>
                        {{Setting::get('address')}}
                    </span>
                    <span>
                        {{Setting::get('zip')}} {{Setting::get('city')}}
                    </span>
                    <span>
                        {{Setting::get('country')}}
                    </span>
                    @if(Setting::get('phone_number'))
                    <span class="text-xl flex">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-6 h-6 mr-2"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Tel: <span class="italic ml-2">
                            {{Setting::get('phone_number')}}
                        </span>
                    </span>
                    @endif
                    @if(! page_option('contact', 'hide_email', false))
                    <span class="text-xl flex">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                            <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                            <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                        </svg>                      
                        Email: <span class="italic ml-2">
                            {{Setting::get('email_address')}}
                    </span>
                    @endif
                </p>

                <div class="mt-8">
                    {!! str($content)->sanitizeHtml() !!}
                </div>
            </div>

            @if(filled(Setting::get('lat')) && filled(Setting::get('long')))
            {{-- GPS --}}
            <div class="flex flex-col w-full lg:ml-8 lg:mt-0 mt-8">
                <h2 class="global-h2 text-xl lg:text-2xl">Coordonnées GPS</h2>

                <p class="global-p whitespace-nowrap">
                    Latitude : {{Setting::get('lat')}} <br>
                    Longitude : {{Setting::get('long')}}
                </p>

                <div style="overflow:hidden;max-width:100%" class="md:w-[350px] w-full h-[350px] mt-7">
                    <div id="embed-ded-map-canvas" style="height:100%; width:100%;max-width:100%;">
                        <iframe
                            style="height:100%;width:100%;border:0;"
                            frameborder="0"
                            loading="lazy"
                            title="{{Setting::get('website_name')}} GPS"
                            src="https://www.google.com/maps/embed/v1/place?q={{Setting::get('lat')}},{{Setting::get('long')}}&key={{config('core.GOOGLE_MAPS_API_KEY')}}"
                        >
                        </iframe>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
