{{--
    GP247-EC listing (Livewire ProductFilter): a filter sidebar (keyword, price
    range, brands as a check list) and the results (sort bar + dense grid).
    WHY the root is `display: contents`: the page (screen/shop_product_list,
    shop_search) owns the two-column grid, so its left-position layout blocks can
    sit under this sidebar in the same column. The aside and the results are the
    grid items; loading feedback is set on each of them.
    All wire: bindings and data-testid hooks of the component contract are kept.
--}}
<div class="contents" x-data="{ filtersOpen: false }">
    @php($currencySymbol = gp247_currency_info()['symbol'] ?? gp247_currency_code())
    @php($ecFiltered = $sort !== '' || $price !== '' || $brand !== '' || $keyword !== '')

        {{-- Filter sidebar (drawer on phones) --}}
        <aside wire:loading.class="opacity-60 pointer-events-none" class="card overflow-hidden transition-opacity lg:col-start-1 lg:row-start-1" :class="filtersOpen ? '' : 'hidden lg:block'" data-testid="shop-product-filter">
            <div class="flex items-center justify-between gap-2 ec-bar px-4 py-3 text-ink-900">
                <span class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide">
                    <svg class="w-4 h-4 text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>
                    {{ trans('Templates/GP247EC::lang.ec.filter') }}
                </span>
                @if ($ecFiltered)
                <button type="button" wire:click="clearFilters" class="text-xs font-semibold text-brand-600 hover:text-brand-700" data-testid="shop-product-filter-clear">
                    {{ gp247_language_render('filter_sort.clear') }}
                </button>
                @endif
            </div>

            <div class="border-b border-ink-100 p-4">
                <div class="relative">
                    <svg class="absolute start-3 top-1/2 -translate-y-1/2 h-4 w-4 text-ink-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
                    <input type="search" wire:model.live.debounce.500ms="keyword" placeholder="{{ gp247_language_render('front.search') }}" class="input ps-9" data-testid="shop-product-filter-keyword">
                </div>
            </div>

            <div class="border-b border-ink-100 p-4">
                <p class="mb-3 text-xs font-bold uppercase tracking-wide text-ink-900">
                    {{ gp247_language_render('filter_sort.price_range') }} <span class="font-normal text-ink-400">({{ $currencySymbol }})</span>
                </p>
                <div class="flex items-center gap-2">
                    <input type="number" wire:model="priceMin" wire:keydown.enter="applyPrice" min="0" inputmode="decimal" placeholder="{{ gp247_language_render('filter_sort.price_min') }}" aria-label="{{ gp247_language_render('filter_sort.price_min') }}" class="input h-9 py-1.5 text-xs" data-testid="shop-product-filter-price-min">
                    <span class="text-ink-400">–</span>
                    <input type="number" wire:model="priceMax" wire:keydown.enter="applyPrice" min="0" inputmode="decimal" placeholder="{{ gp247_language_render('filter_sort.price_max') }}" aria-label="{{ gp247_language_render('filter_sort.price_max') }}" class="input h-9 py-1.5 text-xs" data-testid="shop-product-filter-price-max">
                </div>
                <button type="button" wire:click="applyPrice" class="btn-primary btn-sm mt-3 w-full" data-testid="shop-product-filter-price-apply">{{ gp247_language_render('filter_sort.apply') }}</button>
            </div>

            @if ($brands->count())
            <div class="p-4">
                <p class="mb-2 text-xs font-bold uppercase tracking-wide text-ink-900">{{ gp247_language_render('front.brands') }}</p>
                <ul class="space-y-0.5">
                    @foreach ($brands as $brandItem)
                    @php($brandActive = in_array($brandItem->alias, $selectedBrandAliases))
                    <li>
                        <button type="button"
                            wire:click="toggleBrand('{{ e($brandItem->alias) }}')"
                            aria-pressed="{{ $brandActive ? 'true' : 'false' }}"
                            class="flex w-full items-center gap-2.5 rounded-sm px-1 py-1.5 text-sm text-start transition {{ $brandActive ? 'font-semibold text-brand-700' : 'text-ink-700 hover:text-brand-600' }}"
                            data-testid="shop-product-filter-brand">
                            <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-sm border {{ $brandActive ? 'border-brand-500 bg-brand-500 text-white' : 'border-ink-300 bg-white' }}">
                                @if ($brandActive)
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </span>
                            {{ $brandItem->name }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </aside>

        {{-- Results --}}
        <div wire:loading.class="opacity-60 pointer-events-none" class="min-w-0 transition-opacity lg:col-start-2 lg:row-start-1 lg:row-span-2">
            <div class="mb-3 flex flex-wrap items-center gap-3 rounded border border-ink-200/70 bg-white px-3 py-2 text-ink-800">
                <button type="button" @click="filtersOpen = !filtersOpen" class="lg:hidden flex items-center gap-1.5 rounded-sm border border-ink-300 px-3 py-1.5 text-xs font-semibold">
                    <svg class="w-3.5 h-3.5 text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>
                    {{ trans('Templates/GP247EC::lang.ec.filter') }}
                </button>
                <span class="hidden sm:block text-xs text-ink-500">
                    @if ($products->total() > 0)
                        @include(gp247_shop_process_view($GP247TemplatePath, 'common.pagination_result'), ['items' => $products])
                    @endif
                </span>
                {{-- Shopper-facing orders only (ShopProduct::storefrontSort); the empty value
                     is the admin's own arrangement, labelled "Featured". --}}
                <label class="ms-auto flex items-center gap-2">
                    <span class="text-xs text-ink-500">{{ trans('Templates/GP247EC::lang.ec.sort_by') }}</span>
                    <select wire:model.live="sort" class="h-8 rounded-sm border border-ink-300 bg-white px-2 pe-7 text-xs font-semibold text-ink-900 outline-none focus:ring-2 focus:ring-brand-500" data-testid="shop-product-filter-sort">
                        <option value="">{{ gp247_language_render('filter_sort.featured') }}</option>
                        <option value="id_desc">{{ gp247_language_render('filter_sort.id_desc') }}</option>
                        <option value="sold_desc">{{ gp247_language_render('filter_sort.best_selling') }}</option>
                        <option value="price_asc">{{ gp247_language_render('filter_sort.price_asc') }}</option>
                        <option value="price_desc">{{ gp247_language_render('filter_sort.price_desc') }}</option>
                        <option value="name_asc">{{ gp247_language_render('filter_sort.name_asc') }}</option>
                    </select>
                </label>
            </div>

            @if ($products->count())
            <div class="overflow-hidden rounded border border-ink-200/70 bg-ink-100">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-px">
                    @foreach ($products as $product)
                    <div class="bg-white">
                        @livewire('gp247-shop-front::product-card', ['productId' => $product->id], key('product-card-'.$product->id))
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-5">
                {{ $products->links() }}
            </div>
            @else
            <div class="card flex flex-col items-center gap-3 py-16 text-center">
                <svg class="w-12 h-12 text-ink-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
                <p class="text-sm text-ink-500">{{ gp247_language_render('front.no_item') }}</p>
            </div>
            @endif
        </div>

    <div wire:loading class="fixed inset-0 z-40 pointer-events-none"></div>
</div>
