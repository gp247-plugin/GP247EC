{{-- GP247-EC category shelf: white panel, round icon tiles in a dense grid (two rows scroll sideways on phones). --}}
@if (class_exists(\GP247\Shop\Models\ShopCategory::class))
@php
    $topCategories = $modelCategory->start()->getCategoryRoot()->getCategoryTop()->getData();
@endphp
@if ($topCategories->count())
<section class="container-x pt-3 sm:pt-4">
    <div class="shelf">
        <div class="shelf-head">
            <h2 class="section-title">{{ gp247_language_quickly('front.shop_by_category', 'Shop by category') }}</h2>
        </div>
        <div class="grid grid-flow-col auto-cols-[25%] grid-rows-2 overflow-x-auto no-scrollbar sm:grid-flow-row sm:grid-rows-none sm:grid-cols-6 lg:grid-cols-10">
            @foreach ($topCategories as $category)
                <a href="{{ $category->getUrl() }}" class="group flex flex-col items-center gap-2 border-e border-b border-ink-100 px-2 py-4 hover:shadow-soft hover:relative hover:z-10 transition" data-testid="storefront-home-category">
                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-ink-100 group-hover:bg-brand-100 transition">
                        <img src="{{ gp247_file($category->getThumb()) }}" alt="{{ $category->name }}" class="h-9 w-9 object-contain" loading="lazy">
                    </span>
                    <span class="text-xs text-center text-ink-800 clamp-2 group-hover:text-brand-600">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif
