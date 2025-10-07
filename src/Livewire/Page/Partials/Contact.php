<?php

namespace Leobsst\LaravelCmsCore\Livewire\Page\Partials;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Leobsst\LaravelCmsCore\Concerns\CanFlashMessage;
use Leobsst\LaravelCmsCore\Concerns\Features\Pages\HasGalleryComponent;
use Leobsst\LaravelCmsCore\Mail\ContactClient;
use Leobsst\LaravelCmsCore\Mail\ContactCustomer;
use Leobsst\LaravelCmsCore\Models\HistoryMail;
use Leobsst\LaravelCmsCore\Models\Setting;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Livewire\Component;

class Contact extends Component
{
    use CanFlashMessage;
    use HasGalleryComponent;

    public ?string $content = null;

    public ?Collection $galleries = null;

    public int $columns = 2;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public $captcha;

    public $response;

    public string $subject = '';

    public bool $sent = false;

    public $messages = [
        'name.required' => 'Votre nom est requis.',
        'email.required' => 'Votr adresse email est requise.',
        'email.email' => 'Veuillez saisir une adresse email valide.',
        'subject.required' => 'Le sujet de votre message est requis.',
        'message.required' => 'Votre message est requis.',
    ];

    protected function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'subject' => ['required'],
            'message' => ['required'],
        ];
    }

    public function mount(): void
    {
        $this->sent = false;
        $this->content = $this->getGalleryComponent($this->content, $this->galleries);
    }

    /**
     * placeholder view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function placeholder()
    {
        return view('laravel-cms-core::components.features.pages.contact.placeholder');
    }

    public function sendMail()
    {
        $this->validate();

        if (! $this->sent) {
            if (filled(env('RECAPTCHA_SITE_KEY')) && filled(env('RECAPTCHA_SITE_SECRET'))) {
                $response = $this->getReCaptchaResponse();
            } else {
                $response = ['score' => 1];
            }

            $result = [];
            foreach ($response as $key => $value) {
                $result[$key] = $value;
            }

            $this->sent = true;

            if (isset($result['score']) && $result['score'] > .3) {
                try {
                    Mail::to([Setting::get('email_address')])
                        ->send(new ContactCustomer(
                            $this->name,
                            $this->email,
                            $this->phone,
                            $this->subject,
                            $this->message
                        ));

                    Mail::to([$this->email])
                        ->send(new ContactClient(
                            $this->name,
                            $this->subject,
                            $this->message
                        ));

                    HistoryMail::create([
                        'name' => $this->name,
                        'email' => $this->email,
                        'phone' => $this->phone,
                        'subject' => $this->subject,
                        'content' => $this->message,
                        'ip' => ClientService::getIp(),
                    ]);
                } catch (Exception $e) {
                    $this->flash('error', 'Une erreur est survenue lors de l\'envoi de votre message.');
                }
                $this->flash('success', 'Votre message a bien été envoyé.');
            } else {
                $this->flash('error', 'Google thinks you are a bot, please refresh and try again');
            }

            return redirect()->route('core.pages.show', ['fallbackPlaceholder' => 'contact'])->with([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ]);
        }
    }

    private function getReCaptchaResponse(): mixed
    {
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret=' . env('RECAPTCHA_SITE_SECRET') . '&response=' . $this->captcha);

        return $response->json();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view(config('core.features.pages.conttact_view', 'laravel-cms-core::livewire.page.partials.contact'));
    }
}
