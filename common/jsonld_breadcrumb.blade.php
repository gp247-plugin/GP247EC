{{--
    JSON-LD — BreadcrumbList schema.

    Expects $breadcrumbItems: array of ['name' => string, 'url' => string|null]
    Pass via @push('jsonld') from individual screen views.
--}}
@if(!empty($breadcrumbItems))
@php
    $breadcrumbJsonLd = \GP247\Front\Library\SeoMeta::buildBreadcrumbJsonLd($breadcrumbItems);
@endphp
@if($breadcrumbJsonLd)
<script type="application/ld+json">{!! $breadcrumbJsonLd !!}</script>
@endif
@endif
