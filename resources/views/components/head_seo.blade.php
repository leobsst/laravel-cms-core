@props(['page'])
@section('head_seo')
<meta name="description" content="{{mb_strimwidth($page->seo->description ?: Setting::get('website_description'), 0, 150, '...')}}">
        <meta name="keywords" content="{{ implode(', ', array_merge(Setting::websiteTags()->toArray(), $page->seo->tags_formatted->toArray())) }}">
        <meta name="author" content="{{Setting::get('website_name')}}">
        <link rel="canonical" href="{{route('core.pages.show', ['fallbackPlaceholder' => $page->full_path])}}">
        <meta name="publisher" content="{{Setting::get('website_name')}}">
        <meta name="generator" content="{{Setting::get('website_name')}}">
        <meta property="robots" content="{{$page->seo->robots}}">
        <meta property="og:title" content="{{$page->title}}">
        <meta property="og:description" content="{{mb_strimwidth($page->seo->description ?: Setting::get('website_description'), 0, 150, '...')}}">
        <meta property="og:url" content="{{route('core.pages.show', ['fallbackPlaceholder' => $page->full_path])}}">
        <meta property="og:site_name" content="{{Setting::get('website_name')}}">
        <meta property="og:locale" content="{{$page->seo->og_locale}}">
        <meta property="og:type" content="{{$page->banner ?: $page->seo->og_type}}">
        <meta property="og:image" content="{{$page->banner ? optimized($page->banner, 'uploads'): asset('assets/img/main_bg_min.webp')}}">
        <meta property="twitter:card" content="{{$page->seo->twitter_card}}">
        <meta property="twitter:title" content="{{$page->title}}">
        <meta property="twitter:description" content="{{mb_strimwidth($page->seo->description ?: Setting::get('website_description'), 0, 150, '...')}}">
        <meta property="twitter:site" content="{{Setting::get('website_name')}}">
        <meta property="twitter:creator" content="{{Setting::get('website_name')}}">
        <meta property="twitter:image" content="{{$page->banner ? optimized($page->banner, 'uploads'): asset('assets/img/main_bg_min.webp')}}">
@endsection
