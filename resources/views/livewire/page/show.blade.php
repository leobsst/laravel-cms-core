<x-laravel-cms-core::head_seo :page="$page" />
@if($page->is_home == 0)
@section('title')
{{ $page->title }}
@endsection
@endif
@if($page->title_content)
@section('title-content')
{{ $page->title_content }}
@endsection
@endif

<div>
    @if($page->slug === 'contact')
        <livewire:laravel-cms-core::page.partials.contact
            :content="$page->content"
            :columns="Setting::get('lat') && Setting::get('long') ? 3 : 2"
            wire:key="page_contact_{{ uniqid() }}"
            lazy="on-load" />
    @else
        <livewire:laravel-cms-core::page.partials.content
            :content="$page->content"
            wire:key="page_content_{{ uniqid() }}"
            lazy="on-load" />
    @endif
</div>

