<?php

namespace Leobsst\LaravelCmsCore\Livewire\Page;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Mail\ContactClient;
use Leobsst\LaravelCmsCore\Mail\ContactCustomer;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;
use Leobsst\LaravelCmsCore\Models\HistoryMail;
use Leobsst\LaravelCmsCore\Models\Log;
use Leobsst\LaravelCmsCore\Models\Setting;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Livewire\Component;

class Show extends Component
{
    public ?Page $page = null;

    private ?int $pageId = null;

    private ?bool $isPublished = null;

    // Contact page properties
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
    // End of contact page properties

    public function mount(): void
    {
        $this->definePage(request()->path());
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

    private function getReCaptchaResponse(): mixed
    {
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret='.env('RECAPTCHA_SITE_SECRET').'&response='.$this->captcha);

        return $response->json();
    }

    public function render()
    {
        try {
            if (is_null($this->page) || ! $this->isPublished) {
                throw new \Error('Page not found');
            }

            $this->updateStats();
            session()->put('current_page', $this->pageId);
            $view = 'laravel-cms-core::livewire.page.show';
            if ($this->page->slug == 'contact') {
                $view = 'laravel-cms-core::livewire.page.contact';
            }

            return view($view, [
                'seo' => $this->page->seo,
            ]);
        } catch (\Throwable $e) {
            Log::create(attributes: [
                'type' => LogType::ERROR->value,
                'message' => 'Accessing page '.$this->page?->title.' failed',
                'data' => $e->getMessage(),
                'reference_id' => $this->pageId,
                'status' => LogStatus::SUCCESS->value,
                'ip_address' => ClientService::getIp(),
            ]);
            session()->forget('current_page');
            abort(404, $e instanceof \Error ? $e->getMessage() : 'An error occurred', [
                'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            ]);
        }
    }

    private function definePage(string $path): void
    {
        $slug = null;
        $folder = null;

        if (\Illuminate\Support\Str::contains($path, '/')) {
            $segments = explode('/', $path);
            $folder = $segments[0];
            $slug = end($segments);
        } else {
            $slug = $path;
        }

        $page = Page::where('slug', $slug)
            ->with([
                'seo:title,description,robots,og_image,og_type,og_locale,twitter_card,twitter_image',
                'theme:name',
            ])
            ->when(filled($folder), function ($query) use ($folder) {
                $query->whereHas('theme', function ($q) use ($folder) {
                    $q->where('name', $folder);
                });
            });

        if ($page->exists()) {
            $states = $page->first(['id', 'is_published']);
            $this->pageId = $states->id;
            $this->isPublished = $states->is_published;
            $this->page = $page->first(['title', 'title_content', 'slug', 'is_home', 'banner']);
        }
    }

    /**
     * update device stats
     *
     * @return mixed
     */
    public function updateDeviceStats($os): void
    {
        $page = Page::find($this->pageId);
        switch ($os) {
            case 1:
                $page->ios += 1;
                $page->update();
                break;
            case 2:
                $page->android += 1;
                $page->update();
                break;
            case 3:
                $page->other += 1;
                $page->update();
                break;
            default:
                break;
        }
    }

    private function updateStats(): void
    {
        $page = Page::with('stats')->find($this->pageId);
        $stats = $page->stats()->whereDate('created_at', Carbon::today());
        $location = ClientService::getLocation();
        $ip = ClientService::getIp();

        if (blank($ip) || (filled($ip) && ! in_array($ip, $stats->pluck('ip')->toArray()))) {
            $page->stats()->create([
                'ip' => $ip,
                'country' => $location->status == 'success' ? $location->country : 'N/A',
                'city' => $location->status == 'success' ? $location->city : 'N/A',
            ]);
            $this->updateDeviceStats(ClientService::getOS());
        }
    }
}
