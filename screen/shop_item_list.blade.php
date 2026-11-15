@php
/*
$layout_page = shop_item_list
**Variables:**
- $itemsList: paginate (brand or category root listing — shared by
  ShopBrandController::_allBrands() and ShopCategoryController::_allCategories())
Use paginate: $itemsList->appends(request()->except(['page','_token']))->links()
*/
@endphp

{{--
    Brand / category root listing screen.
    Reuses common/item_single.blade.php, the same $item['thumb']/['url']/['title']
    contract already used by screen/front_search.blade.php, instead of
    Bootstrap's col-lg-9/row row-30 classes.

    Both ShopCategory (via the joined shop_category_description.name) and
    ShopBrand (plain `name` column) now expose the same `name` attribute, so
    this shared view no longer needs a `title`/`name` fallback.
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full">
    @if ($itemsList->count())
        <div class="mb-3 flex items-center justify-between gap-3 rounded border border-brand-200 border-b-2 border-b-brand-500 bg-gradient-to-r from-brand-50 to-white px-4 py-3 text-ink-900">
            <h1 class="text-sm sm:text-base font-bold uppercase tracking-wide">{{ $title ?? '' }}</h1>
            <span class="text-xs text-ink-500">@include($GP247TemplatePath.'.common.pagination_result', ['items' => $itemsList])</span>
        </div>

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
        <p class="card text-center text-sm text-ink-500 py-16">{{ gp247_language_render('front.no_item') }}</p>
    @endif
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
