<?php

namespace Leobsst\LaravelCmsCore\Livewire\Page;

use Carbon\Carbon;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;
use Leobsst\LaravelCmsCore\Models\Log;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Livewire\Component;

class Show extends Component
{
    public ?Page $page = null;

    public function mount(): void
    {
        $this->getPage();

        try {
            if (is_null($this->page) || ! $this->page->is_published) {
                throw new \Error('Page not found');
            }

            $this->updateStats();
            session()->put('current_page', $this->page->id);
        } catch (\Throwable $e) {
            Log::create(attributes: [
                'type' => LogType::ERROR->value,
                'message' => 'Accessing page ' . $this->page?->title . ' failed',
                'data' => $e->getMessage(),
                'reference_id' => $this->page?->id,
                'status' => LogStatus::SUCCESS->value,
                'ip_address' => ClientService::getIp(),
            ]);
            session()->forget('current_page');
            abort(404, $e instanceof \Error ? $e->getMessage() : 'An error occurred', [
                'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            ]);
        }
    }

    /**
     * get the page from the url
     */
    public function getPage(): void
    {
        $path = request()->path();
        $slug = null;
        $folder = null;

        if (\Illuminate\Support\Str::contains($path, '/')) {
            $segments = explode('/', $path);
            $folder = $segments[0];
            $slug = end($segments);
        } else {
            $slug = $path;
        }

        if (blank($slug)) {
            $slug = null;
        }

        $this->page = Page::where('slug', $slug)
            ->with([
                'seo:page_id,title,description,robots,og_image,og_type,og_locale,twitter_card,twitter_image',
                'theme:id,name',
                'options:page_id,name,value',
                'galleries:id,page_id,identifier,orientation',
            ])
            ->when(
                value: filled(value: $folder),
                callback: function ($query) use ($folder): void {
                    $query->whereHas('theme', function ($q) use ($folder) {
                        $q->where('name', $folder);
                    });
                },
                default: fn ($query) => $query->whereNull('theme_id')
            )
            ->first();

        if ($this->page->galleries->count() > 0) {
            $this->page->galleries->load('media:id,model_id,collection_name,name,file_name,mime_type,disk,size,manipulations,custom_properties,order_column');
        }
    }

    /**
     * update device stats
     */
    public function updateDeviceStats($os): void
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

    /**
     * update page stats
     */
    private function updateStats(): void
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
     * Render the component.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view(config('core.features.pages.router_view', 'laravel-cms-core::livewire.page.show'));
    }
}
