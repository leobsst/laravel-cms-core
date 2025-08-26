<?php

namespace Leobsst\LaravelCmsCore\Livewire\Page;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Leobsst\LaravelCmsCore\Mail\ContactClient;
use Leobsst\LaravelCmsCore\Mail\ContactCustomer;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;
use Leobsst\LaravelCmsCore\Models\HistoryMail;
use Leobsst\LaravelCmsCore\Models\Setting;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Livewire\Component;

class Show extends Component
{
    public ?string $slug = null;

    public ?string $folder = null;

    public $page;

    // Contact page properties
    public $name;

    public $email;

    public $phone;

    public $message;

    public $captcha;

    public $response;

    public $subject;

    public $data;

    public $sent;

    public $messages = [
        'name.required' => 'Votre nom est requis.',
        'email.required' => 'Votr adresse email est requise.',
        'email.email' => 'Veuillez saisir une adresse email valide.',
        'subject.required' => 'Le sujet de votre message est requis.',
        'message.required' => 'Votre message est requis.',
    ];
    // End of contact page properties

    public function mount()
    {
        $path = request()->path();

        if (Str::contains($path, '/')) {
            $segments = explode('/', $path);
            $this->folder = $segments[0];
            $this->slug = end($segments);
        } else {
            $this->slug = $path;
        }

        $this->page = Page::where('slug', 'LIKE', '%'.Str::slug($this->slug).'%')
            ->when(filled($this->folder), function ($query) {
                $query->where('folder', 'LIKE', '%'.Str::slug($this->folder).'%');
            })
            ->first();
        $this->sent = false;
    }

    public function sendMail()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

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
                    session()->flash('error', 'Une erreur est survenue lors de l\'envoi de votre message.');
                }
                session()->flash('success', 'Votre message a bien été envoyé.');
            } else {
                session()->flash('error', 'Google thinks you are a bot, please refresh and try again');
            }

            return redirect()->route('core.page.show', ['slug' => 'contact']);
        }
    }

    private function getReCaptchaResponse()
    {
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret='.env('RECAPTCHA_SITE_SECRET').'&response='.$this->captcha);

        return $response->json();
    }

    public function render()
    {
        try {
            if (is_null($this->page) || ! $this->page->is_published) {
                throw new \Error('Page not found');
            }

            $this->updateStats();
            session()->put('current_page', $this->page->id);
            $view = 'livewire.page.show';
            if ($this->slug == 'contact') {
                $view = 'livewire.page.contact';
            }

            return view($view, [
                'seo' => $this->page->seo,
            ]);
        } catch (\Throwable $e) {
            session()->forget('current_page');
            abort(404, $e instanceof \Error ? $e->getMessage() : 'An error occurred', [
                'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            ]);
        }
    }

    private function updateStats()
    {
        $stats = $this->page->stats()->whereDate('created_at', Carbon::today());
        $location = ClientService::getLocation();
        $ip = ClientService::getIp();

        if (blank($ip) || (filled($ip) && ! in_array($ip, $stats->pluck('ip')->toArray()))) {
            $this->page->stats()->create([
                'ip' => $ip,
                'country' => $location->status == 'success' ? $location->country : 'N/A',
                'city' => $location->status == 'success' ? $location->city : 'N/A',
            ]);
            $this->updateDeviceStats(ClientService::getOS());
        }
    }

    /**
     * update device stats
     *
     * @return mixed
     */
    public function updateDeviceStats($os)
    {
        switch ($os) {
            case 1:
                $this->page->ios += 1;
                $this->page->update();
                break;
            case 2:
                $this->page->android += 1;
                $this->page->update();
                break;
            case 3:
                $this->page->other += 1;
                $this->page->update();
                break;
            default:
                break;
        }
    }
}
