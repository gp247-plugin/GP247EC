{{--
    Shared <head> block for GP247\Front\TemplateComponents\MetaHead: title,
    description, canonical, hreflang, Open Graph and the site-wide JSON-LD.
    Public properties of MetaHead are bound to this view automatically.

    Prints every indexing signal MetaHead decides:
      $robots (string|null) · $canonical (string) · $ogUrl (string) ·
      $hreflang (array locale => url) + $hreflangDefault (string|null) ·
      $verification (array: google|bing => token)
    WHY each one has a fallback: on an older gp247/front MetaHead does not hand
    these variables over; there the page keeps its previous output (no robots
    tag, og:url = current URL, x-default = first locale).
--}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="{{ config('app.name') }}">
@if(!empty($robots))
<meta name="robots" content="{{ $robots }}">
@endif
<link rel="canonical" href="{{ $canonical ?? \GP247\Front\Library\SeoMeta::canonicalUrl() }}" />
<meta name="description" content="{{ $description ?? gp247_store_info('description') }}">
<meta name="keywords" content="{{ $keyword ?? gp247_store_info('keyword') }}">
<title>{{ $title ?? gp247_store_info('name') }}</title>
<link rel="icon" href="{{ gp247_file(gp247_store_info(key: 'icon', default: 'GP247/Core/logo/icon.png')) }}" type="image/png" sizes="16x16">
@if(!empty($verification['google']))
<meta name="google-site-verification" content="{{ $verification['google'] }}">
@endif
@if(!empty($verification['bing']))
<meta name="msvalidate.01" content="{{ $verification['bing'] }}">
@endif
@php
    // No bundled default image: page image, else the store's og_image, else no tag.
    $shareImage = !empty($og_image) ? $og_image : trim((string) gp247_store_info(key: 'og_image'));
@endphp
@if($shareImage !== '')
<meta property="og:image" content="{{ gp247_file($shareImage) }}" />
@endif
<meta property="og:url" content="{{ $ogUrl ?? \Request::fullUrl() }}" />
<meta property="og:type" content="{{ $ogType ?? 'website' }}" />
<meta property="og:title" content="{{ $title ?? gp247_store_info('name') }}" />
<meta property="og:description" content="{{ $description ?? gp247_store_info('description') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- hreflang (caller's map, or built from the current route; none on a noindex page) --}}
@if(!empty($hreflang))
    @foreach($hreflang as $locale => $url)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}" />
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $hreflangDefault ?? reset($hreflang) }}" />
@endif

{{-- Twitter card --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title ?? gp247_store_info('name') }}" />
<meta name="twitter:description" content="{{ $description ?? gp247_store_info('description') }}" />
@if(!empty($og_image))
    <meta name="twitter:image" content="{{ gp247_file($og_image) }}" />
@endif

{{-- JSON-LD: Organization (site-wide, no per-page data needed) — only when SEO JSON-LD is enabled --}}
@if(\GP247\Front\Library\SeoMeta::jsonldEnabled())
@php
    $gp247OrgJsonLd = \GP247\Front\Library\SeoMeta::buildOrganizationJsonLd(
        name:    gp247_store_info('name') ?: config('app.name'),
        url:     url('/'),
        logoUrl: !empty(gp247_store_info('logo')) ? gp247_file(gp247_store_info('logo')) : null,
    );
@endphp
<script type="application/ld+json">{!! $gp247OrgJsonLd !!}</script>
@endif
