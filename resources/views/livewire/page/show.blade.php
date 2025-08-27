<x-laravel-cms-core::head_seo :seo="$seo" :page="$page" />
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
    <div class="mb-8 w-full h-fit overflow-y-hidden overflow-x-hidden content p-2 mt-16" wire:key='page_{{uniqid()}}'>
        {!! str(string: $page->content)->sanitizeHtml() !!}
    </div>
</div>
