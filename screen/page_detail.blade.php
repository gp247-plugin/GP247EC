@php
/*
$layout_page = front_page_detail
**Variables:**
- $page: no paginate
*/
@endphp

{{--
    CMS page detail: page title and content in one panel.

    Variables:
    - $page, $title
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main')
<main class="container-x py-4 sm:py-5">
    <article class="card overflow-hidden">
        <div class="border-b border-ink-100 px-5 py-4">
            <h1 class="section-title">{{ $title ?? ($page->name ?? "") }}</h1>
        </div>
        <div class="px-5 py-6 sm:px-8 text-ink-700 leading-relaxed space-y-3 [&_a]:text-brand-600 [&_img]:max-w-full [&_img]:rounded-sm">
            {!! gp247_html_render($page->content ?? '') !!}
        </div>
    </article>
</main>
@endsection

@push('styles')
{{-- Your css style --}}
@endpush

@push('scripts')
{{-- //script here --}}
@endpush
