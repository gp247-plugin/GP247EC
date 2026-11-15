{{--
    GP247-EC: pages whose main area is one full-width component (product listing,
    search, product detail) still render the layout blocks the site owner filed
    under left / center / right for that page — below the main content, as shelves.
--}}
@php
    $ecExtra = array_filter([
        gp247_render_block('left', $layout_page ?? null),
        gp247_render_block('center', $layout_page ?? null),
        gp247_render_block('right', $layout_page ?? null),
    ], fn ($html) => trim($html) !== '');
@endphp
@if (count($ecExtra))
    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-start">
        @foreach ($ecExtra as $ecHtml)
            <div>{!! $ecHtml !!}</div>
        @endforeach
    </div>
@endif
