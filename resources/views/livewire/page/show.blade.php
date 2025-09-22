<x-laravel-cms-core::head_seo :page="$page" />
@section('title-content', $page->title_content)
@section('container-class', $page->no_content ? '' : null)
@section('content-class', $page->no_content ? '' : null)
@if($page->no_footer)
@section('without-footer', true)
@endif

<div class="h-full">
@if($page->is_home)
@section('header-content')
<x-laravel-cms-core::home-page :page="$page" />
@endsection
@elseif(! $page->is_home && filled($page->banner))
@section('title', $page->title)
@section('header-content')
<x-laravel-cms-core::header-page :page="$page" />
@endsection
@elseif(! $page->is_home)
@section('title', $page->title)
@endif
@if($page->slug === 'contact')
    <livewire:laravel-cms-core::page.partials.contact
        :content="$page->content"
        :galleries="$page->galleries"
        :columns="Setting::get('lat') && Setting::get('long') ? 3 : 2"
        wire:key="page_contact_{{ uniqid() }}"
        lazy="on-load" />
@elseif(! $page->no_content)
    <livewire:laravel-cms-core::page.partials.content
        :content="$page->content"
        :galleries="$page->galleries"
        wire:key="page_content_{{ uniqid() }}"
        lazy="on-load" />
@endif
</div>

