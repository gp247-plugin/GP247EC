{{--
    Product listing (category / all products / brand / tag): sub-categories shelf
    plus the ProductFilter Livewire component (filters + grid).

    Variables:
    - $subCategory (optional), $categoryId, $tag, $brand, $title
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content')
<div class="lg:col-span-12 w-full">
    {{-- sub category (server-rendered — crawlable, SEO safe) --}}
    @isset ($subCategory)
        @if ($subCategory->count())
        <div class="shelf mb-4">
            <div class="shelf-head"><h2 class="section-title">{{ gp247_language_render('front.sub_categories') }}</h2></div>
            <div class="flex gap-px overflow-x-auto no-scrollbar bg-ink-100">
                @foreach ($subCategory as $item)
                <a href="{{ $item->getUrl() }}" class="group flex w-28 shrink-0 flex-col items-center gap-2 bg-white px-2 py-4 text-center hover:text-brand-600">
                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-ink-100 group-hover:bg-brand-100">
                        <img src="{{ gp247_file($item->getThumb()) }}" alt="{{ $item->name }}" class="h-9 w-9 object-contain" loading="lazy">
                    </span>
                    <span class="text-xs text-ink-800 clamp-2">{{ $item->name }}</span>
                </a>
                @endforeach
            </div>
        </div>

        @include($GP247TemplatePath.'.common.pagination', ['items' => $subCategory])
        @endif
    @endisset
    {{-- //sub category --}}

    {{-- Product filter + grid.
         The grid builds its own query and ignores $products, so every filter this page
         stands for has to be handed over here — a missing prop turns the page into a
         full-catalogue listing without any error. --}}
    <div class="grid grid-cols-1 lg:grid-cols-[240px_minmax(0,1fr)] lg:grid-rows-[auto_1fr] gap-4 items-start">
    @livewire('gp247-shop-front::product-filter', [
        'initialCategory' => $categoryId ?? null,
        'initialTag'      => $tag->alias ?? null,
        'initialBrand'    => $brand->alias ?? null,
    ])
        @php
            $ecLeftBlocks = gp247_render_block('left', $layout_page ?? null);
        @endphp
        @if (trim($ecLeftBlocks) !== '')
            <div class="space-y-4 lg:col-start-1 lg:row-start-2" data-testid="storefront-listing-left-blocks">{!! $ecLeftBlocks !!}</div>
        @endif
    </div>

    @include($GP247TemplatePath.'.layout.extra_blocks', ['ecSkipLeft' => true])
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
