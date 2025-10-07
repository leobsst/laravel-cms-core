@php
        $description = mb_strimwidth($page->seo->description ?: Setting::get('website_description'), 0, 150, '...');
        $keywords = implode(', ', array_merge(Setting::websiteTags()->toArray(), $page->seo->tags_formatted->toArray()));
        $websiteName = Setting::get('website_name');
        $image = $page->banner ? optimized($page->banner, 'uploads'): asset('assets/img/main_bg_min.webp');
        $uri = route('core.pages.show', ['fallbackPlaceholder' => $page->full_path]);
@endphp
@props(['page'])
@section('head_seo')
<!-- GENERAL -->
        <meta name="description" content="{{$description}}">
        <meta name="keywords" content="{{$keywords}}">
        <meta name="author" content="{{$websiteName}}">
        <link rel="canonical" href="{{$uri}}">
        <meta name="publisher" content="{{$websiteName}}">
        <meta name="generator" content="{{$websiteName}}">
        <meta property="robots" content="{{$page->seo->robots}}">
        <!-- OPENGRAPH -->
        <meta property="og:title" content="{{$page->title}}">
        <meta property="og:description" content="{{$description}}">
        <meta property="og:url" content="{{$uri}}">
        <meta property="og:site_name" content="{{$websiteName}}">
        <meta property="og:locale" content="{{$page->seo->og_locale}}">
        <meta property="og:type" content="{{$page->banner ?: $page->seo->og_type}}">
        <meta property="og:image" content="{{$image}}">
        <meta property="og:image:secure_url" content="{{$image}}">
        <!-- TWITTER -->
        <meta property="twitter:card" content="{{$page->seo->twitter_card}}">
        <meta property="twitter:title" content="{{$page->title}}">
        <meta property="twitter:description" content="{{$description}}">
        <meta property="twitter:site" content="{{$websiteName}}">
        <meta property="twitter:creator" content="{{$websiteName}}">
        <meta property="twitter:image" content="{{$image}}">
@if(Setting::get('facebook'))
        <!-- FACEBOOK -->
        <meta property="article:publisher" content="{{Setting::get('facebook')}}">
@endif
@endsection
