<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($routes as $route)
    <url>
        @switch($route)
            @case('/')
                <loc>{{route('core.page.show', ['slug' => $route])}}</loc>
                <lastmod>{{now()->format('Y-m-d')}}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>1.0</priority>
                @break
            @case('contact')
                <loc>{{route('core.page.show', ['slug' => $route])}}</loc>
                <lastmod>{{now()->subDays(rand(0, 10))->format('Y-m-d')}}</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.8</priority>
                @break
            @default
                <loc>{{route('core.page.show', ['slug' => $route])}}</loc>
                <lastmod>{{now()->subDays(rand(0, 3))->format('Y-m-d')}}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.7</priority>
        @endswitch
    </url>
    @endforeach
</urlset> 