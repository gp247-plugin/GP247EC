@php
/*
$layout_page = front_search
**Variables:**
- $itemsList: paginate
Use paginate: $itemsList->appends(request()->except(['page','_token']))->links()
*/
@endphp

{{--
    Site search results (CMS pages / news). Overrides block_main_content_center.

    Variables:
    - $itemsList: paginator
    - $title
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full">
    <div class="mb-3 flex items-center justify-between gap-3 rounded border border-brand-200 border-b-2 border-b-brand-500 bg-gradient-to-r from-brand-50 to-white px-4 py-3 text-ink-900">
        <h1 class="text-sm sm:text-base font-bold uppercase tracking-wide">{{ $title }}</h1>
        @if ($itemsList->count())<span class="text-xs text-ink-500">@include($GP247TemplatePath.'.common.pagination_result', ['items' => $itemsList])</span>@endif
    </div>

    @if ($itemsList->count())
        <div class="overflow-hidden rounded border border-ink-200/70 bg-ink-100">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-px">
            @foreach ($itemsList as $item)
                @php
                    $item['thumb'] = $item->getThumb();
                    $item['url'] = $item->getUrl();
                    $item['title'] = $item->name;
                @endphp
                <div class="bg-white">@include($GP247TemplatePath.'.common.item_single', ['item' => $item])</div>
            @endforeach
        </div>
        </div>

        @include($GP247TemplatePath.'.common.pagination', ['items' => $itemsList])
    @else
        <div class="card py-20 text-center">
            <p class="text-sm font-semibold text-ink-500">{!! gp247_language_render('front.no_item') !!}</p>
        </div>
    @endif
</div>
@endsection

@push('styles')
{{-- Your css style --}}
@endpush

@push('scripts')
{{-- //script here --}}
@endpush
