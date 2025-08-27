<x-laravel-cms-core::head_seo :page="$this->page" />
@if($this->page->is_home == 0)
@section('title')
{{ $this->page->title }}
@endsection
@endif
@if($this->page->title_content)
@section('title-content')
{{ $this->page->title_content }}
@endsection
@endif
<div>
    <div class="mb-8 w-full h-fit overflow-y-hidden overflow-x-hidden content p-2 mt-16" wire:key='page_{{uniqid()}}'>
        {!! str(string: $this->page->content)->sanitizeHtml() !!}
    </div>
</div>
