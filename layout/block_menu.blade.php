{{--
    GP247-EC header — marketplace layout:
      1. dark-orange top strip: hotline · account link · currency · language
      2. orange main bar: logo · wide search with a dark-orange button · wishlist / cart / account
      3. category bar: "All categories" mega menu + top categories + admin menu links
      4. mobile: hamburger + logo + cart on one row, full-width search below, drawer menu

    Data sources are the same as the default template (gp247_link_collection['menu'],
    ShopCategory root/top, gp247_cart counts) so admin menus and the Livewire
    'cart-updated' badge sync keep working. The badge spans keep the
.gp247-cart /.gp247-wishlist classes the sync script targets.
--}}
@php
    $ecHasShop = class_exists(\GP247\Shop\Models\ShopCategory::class) && isset($modelCategory);
    $ecTopCategories = $ecHasShop ? $modelCategory->start()->getCategoryRoot()->getCategoryTop()->getData() : collect();
    $ecMenu = gp247_link_collection()['menu'] ?? [];
    $ecLoggedIn = function_exists('customer') && customer()->user();
@endphp
<div x-data="gp247ecHeader">

    {{-- 1. Top strip --}}
    <div class="ec-topbar hidden md:block bg-brand-700 text-brand-100 text-xs">
        <div class="container-x flex items-center justify-between h-8">
            <div class="flex items-center gap-4">
                @if (gp247_store_info(key: 'long_phone', default: null))
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/></svg>
                        {{ gp247_language_render('store.hotline') }}: <strong class="text-white font-semibold">{{ gp247_store_info(key: 'long_phone', default: null) }}</strong>
                    </span>
                @endif
                @if (gp247_store_info(key: 'email', default: null))
                    <span class="hidden lg:inline">{{ gp247_store_info(key: 'email', default: null) }}</span>
                @endif
            </div>
            <ul class="flex items-center gap-4 list-none">
                @if (function_exists('gp247_cart') && gp247_config('link_account', null, 1))
                    <li>
                        @if ($ecLoggedIn)
                            <a href="{{ gp247_route_front('customer.index') }}" class="hover:text-white transition">{{ gp247_language_render('customer.my_profile') }}</a>
                        @else
                            <a href="{{ gp247_route_front('customer.login') }}" class="hover:text-white transition">{{ gp247_language_render('front.login') }}</a>
                            <span class="mx-1 text-brand-300">|</span>
                            <a href="{{ gp247_route_front('customer.register') }}" class="hover:text-white transition">{{ gp247_language_quickly('front.register', gp247_language_render('customer.title_register')) }}</a>
                        @endif
                    </li>
                @endif
                <x-gp247-front::currency-switcher />
                <x-gp247-front::language-switcher />
            </ul>
        </div>
    </div>

    {{-- 2 + 3. Sticky header: main bar and category bar --}}
    <header role="banner" class="sticky top-0 z-30 bg-gradient-to-b from-brand-500 to-brand-600 text-white transition-shadow" :class="scrolled && 'shadow-lift'">
        <div class="container-x flex items-center gap-3 lg:gap-8 h-14 md:h-[72px]">

            <button type="button" @click="mobileMenuOpen = true" class="lg:hidden -ms-1 p-2 text-white" aria-label="{{ gp247_language_quickly('front.menu', 'Menu') }}" data-testid="storefront-header-menu">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <a href="{{ gp247_route_front('front.home') }}" class="flex items-center gap-2.5 shrink-0 min-w-0">
                <span class="flex h-9 w-9 md:h-11 md:w-11 items-center justify-center rounded bg-white p-1">
                    <img src="{{ gp247_file(gp247_store_info(key: 'logo', default: null)) }}" alt="{{ gp247_store_info(key: 'name', default: null) }}" class="max-h-full w-auto" />
                </span>
                <span class="hidden sm:block text-lg md:text-xl font-extrabold tracking-tight text-white clamp-1 max-w-[220px]">{{ gp247_store_info(key: 'name', default: null) }}</span>
            </a>

            <form class="hidden md:flex flex-1 min-w-0" action="{{ gp247_route_front('front.search') }}" method="GET" role="search">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="{{ gp247_language_render('search.placeholder') }}"
                       class="w-full min-w-0 h-11 rounded-s border-2 border-e-0 border-white bg-white px-4 text-sm text-ink-900 placeholder:text-ink-400 outline-none"
                       aria-label="{{ gp247_language_render('search.placeholder') }}" data-testid="storefront-header-search-input" />
                <button type="submit" class="h-11 shrink-0 rounded-e border-2 border-white bg-brand-700 px-6 text-sm font-bold text-white hover:bg-brand-800 transition flex items-center gap-2" data-testid="storefront-header-search-submit">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
                    <span class="hidden lg:inline">{{ gp247_language_quickly('front.search', 'Search') }}</span>
                </button>
            </form>

            <div class="flex items-center gap-1 md:gap-5 shrink-0 ms-auto md:ms-0">
                @if (function_exists('gp247_cart'))
                    @if (gp247_config('link_account', null, 1))
                        <a href="{{ $ecLoggedIn ? gp247_route_front('customer.index') : gp247_route_front('customer.login') }}" class="hidden md:flex items-center gap-2 text-white hover:text-brand-100 transition" aria-label="{{ $ecLoggedIn ? gp247_language_render('customer.my_profile') : gp247_language_render('front.login') }}">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                            <span class="hidden xl:flex flex-col leading-tight text-xs">
                                <span class="text-brand-100">{{ gp247_language_quickly('front.account', 'Account') }}</span>
                                <span class="font-semibold text-white">{{ $ecLoggedIn ? gp247_language_render('customer.my_profile') : gp247_language_render('front.login') }}</span>
                            </span>
                        </a>
                        <a href="{{ gp247_route_front('cart.wishlist') }}" class="relative p-2 text-white hover:text-brand-100 transition" aria-label="{{ gp247_language_render('front.wishlist') }}">
                            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 3 5 6.5 5c1.9 0 3.4 1 5.5 3 2.1-2 3.6-3 5.5-3C21 5 23.5 8.5 21.5 12.5 19 16.65 12 21 12 21z"/></svg>
                            <span class="gp247-wishlist absolute top-0 end-0 min-w-[18px] h-[18px] px-1 rounded-full bg-white text-brand-700 text-[10px] font-bold flex items-center justify-center ring-2 ring-brand-600">{{ gp247_cart()->instance('wishlist')->count() }}</span>
                        </a>
                    @endif
                    @if (gp247_config('link_cart', null, 1))
                        <a href="{{ gp247_route_front('cart') }}" class="relative flex items-center gap-2 p-2 md:rounded md:border md:border-white/50 md:px-3 text-white hover:bg-white/10 transition" aria-label="{{ gp247_language_render('front.cart') }}" data-testid="storefront-header-cart">
                            <span class="relative">
                                <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.5 3h2l2.7 12.4a2 2 0 002 1.6h8.6a2 2 0 002-1.6L21 8H6"/></svg>
                                <span class="gp247-cart absolute -top-2 -end-2 min-w-[18px] h-[18px] px-1 rounded-full bg-white text-accent-600 text-[10px] font-bold flex items-center justify-center ring-2 ring-brand-600">{{ gp247_cart()->instance('default')->count() }}</span>
                            </span>
                            <span class="hidden lg:inline text-sm font-semibold text-white">{{ gp247_language_render('front.cart') }}</span>
                        </a>
                    @endif
                @endif
            </div>
        </div>

        {{-- Mobile search row --}}
        <form class="md:hidden container-x pb-2.5 flex" action="{{ gp247_route_front('front.search') }}" method="GET" role="search">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="{{ gp247_language_render('search.placeholder') }}"
                   class="w-full min-w-0 h-10 rounded-s bg-white px-3 text-sm text-ink-900 placeholder:text-ink-400 outline-none" aria-label="{{ gp247_language_render('search.placeholder') }}" />
            <button type="submit" class="h-10 shrink-0 rounded-e bg-brand-700 px-4 text-white" aria-label="{{ gp247_language_quickly('front.search', 'Search') }}">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
            </button>
        </form>

        {{-- 3. Category bar --}}
        <div class="hidden lg:block border-t border-white/15 bg-brand-700">
            <div class="container-x flex items-center gap-1">
                @if ($ecTopCategories->count())
                    <div class="relative shrink-0" x-data="{ open: false }" @mouseleave="open = false" @keydown.escape.window="open = false" @click.outside="open = false">
                        <button type="button" @click="open = !open" @mouseenter="open = true" class="flex items-center gap-2 bg-brand-900 px-4 py-2.5 text-[13px] font-bold uppercase tracking-wide text-white hover:bg-brand-800 transition" :aria-expanded="open.toString()" data-testid="storefront-header-categories">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                            {{ gp247_language_quickly('front.all_categories', 'All categories') }}
                        </button>
                        <div x-show="open" x-cloak x-transition.opacity class="absolute start-0 top-full z-40 flex w-[min(92vw,56rem)] bg-white text-ink-800 shadow-lift border-t-2 border-brand-500">
                            <ul class="w-60 shrink-0 border-e border-ink-100 py-2">
                                @foreach ($ecTopCategories as $ecCat)
                                    <li>
                                        <a href="{{ $ecCat->getUrl() }}" class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-800">
                                            <img src="{{ gp247_file($ecCat->getThumb()) }}" alt="" class="w-6 h-6 object-contain shrink-0">
                                            <span class="clamp-1">{{ $ecCat->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="flex-1 grid grid-cols-3 gap-x-6 gap-y-5 p-5 content-start">
                                @foreach ($ecTopCategories as $ecCat)
                                    @php $ecChildren = $modelCategory->start()->setParent($ecCat->id)->getData(); @endphp
                                    @if ($ecChildren->count())
                                        <div>
                                            <a href="{{ $ecCat->getUrl() }}" class="block text-xs font-bold uppercase tracking-wide text-ink-900 hover:text-brand-700 mb-2">{{ $ecCat->name }}</a>
                                            <ul class="space-y-1.5">
                                                @foreach ($ecChildren as $ecChild)
                                                    <li><a href="{{ $ecChild->getUrl() }}" class="text-sm text-ink-600 hover:text-brand-700">{{ $ecChild->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <nav class="flex min-w-0 items-center overflow-x-auto no-scrollbar" aria-label="Primary">
                    @foreach ($ecTopCategories->take(6) as $ecCat)
                        <a href="{{ $ecCat->getUrl() }}" class="catbar-link">{{ $ecCat->name }}</a>
                    @endforeach
                    @if ($ecTopCategories->count() && count($ecMenu))
                        <span class="mx-2 h-4 w-px shrink-0 bg-white/30"></span>
                    @endif
                    @foreach ($ecMenu as $url)
                        @if ($url['type'] != 'collection')
                            <a class="catbar-link" {{ ($url['data']['target'] == '_blank') ? 'target=_blank' : '' }} href="{{ gp247_url_render($url['data']['url']) }}">{{ gp247_language_render($url['data']['name']) }}</a>
                        @elseif (count($url['childs']))
                            <div class="relative group shrink-0">
                                <button type="button" class="catbar-link flex items-center gap-1">
                                    {{ $url['data']['name'] }}
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                </button>
                                <div class="hidden group-hover:block absolute start-0 top-full z-40">
                                    <div class="bg-white shadow-lift border-t-2 border-brand-500 py-1 min-w-[200px]">
                                        @foreach ($url['childs'] as $item)
                                            <a class="block px-4 py-2 text-sm text-ink-700 hover:bg-brand-50 hover:text-brand-800" {{ ($item['data']['target'] == '_blank') ? 'target=_blank' : '' }} href="{{ gp247_url_render($item['data']['url']) }}">{{ gp247_language_render($item['data']['name']) }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </nav>
            </div>
        </div>
    </header>

    {{-- 4. Mobile drawer --}}
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
        <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="drawer-overlay"></div>
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full rtl:translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full rtl:translate-x-full"
             class="drawer-panel drawer-panel-start overflow-y-auto" @keydown.escape.window="mobileMenuOpen = false">
            <div class="flex items-center justify-between bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-4 text-white">
                <a href="{{ $ecLoggedIn ? gp247_route_front('customer.index') : gp247_route_front('customer.login') }}" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-brand-600">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                    </span>
                    <span class="text-sm font-semibold">{{ $ecLoggedIn ? gp247_language_render('customer.my_profile') : gp247_language_render('front.login') }}</span>
                </a>
                <button type="button" @click="mobileMenuOpen = false" class="p-2 text-white" aria-label="Close menu">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>

            @if ($ecTopCategories->count())
                <p class="px-4 pt-4 pb-2 text-xs font-bold uppercase tracking-wide text-ink-500">{{ gp247_language_quickly('front.all_categories', 'All categories') }}</p>
                <nav class="grid grid-cols-3 gap-2 px-4 pb-4" aria-label="Categories">
                    @foreach ($ecTopCategories as $ecCat)
                        <a href="{{ $ecCat->getUrl() }}" class="flex flex-col items-center gap-1.5 rounded border border-ink-100 p-2 text-center">
                            <img src="{{ gp247_file($ecCat->getThumb()) }}" alt="" class="w-8 h-8 object-contain">
                            <span class="text-[11px] text-ink-700 clamp-2">{{ $ecCat->name }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="divider"></div>
            @endif

            @if (count($ecMenu))
                <nav class="px-4 py-2" aria-label="Primary">
                    @foreach ($ecMenu as $url)
                        @if ($url['type'] != 'collection')
                            <a class="block border-b border-ink-100 py-3 text-sm font-medium text-ink-800" {{ ($url['data']['target'] == '_blank') ? 'target=_blank' : '' }} href="{{ gp247_url_render($url['data']['url']) }}">{{ gp247_language_render($url['data']['name']) }}</a>
                        @elseif (count($url['childs']))
                            <div class="border-b border-ink-100 py-2">
                                <p class="py-1 text-sm font-semibold text-ink-900">{{ $url['data']['name'] }}</p>
                                <div class="ps-3 space-y-2 py-1">
                                    @foreach ($url['childs'] as $item)
                                        <a class="block text-sm text-ink-600" {{ ($item['data']['target'] == '_blank') ? 'target=_blank' : '' }} href="{{ gp247_url_render($item['data']['url']) }}">{{ gp247_language_render($item['data']['name']) }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </nav>
            @endif

            @if (function_exists('gp247_cart') && gp247_config('link_account', null, 1))
                <nav class="px-4 py-2" aria-label="Account">
                    <a href="{{ gp247_route_front('cart.wishlist') }}" class="block border-b border-ink-100 py-3 text-sm font-medium text-ink-800">{{ gp247_language_render('front.wishlist') }}</a>
                    <a href="{{ gp247_route_front('cart.compare') }}" class="block border-b border-ink-100 py-3 text-sm font-medium text-ink-800">{{ gp247_language_render('front.compare') }}</a>
                    @if ($ecLoggedIn)
                        <a href="{{ gp247_route_front('customer.logout') }}" rel="nofollow" onclick="event.preventDefault(); document.getElementById('gp247ec-logout-form').submit();" class="block py-3 text-sm font-semibold text-accent-600">{{ gp247_language_render('front.logout') }}</a>
                        <form id="gp247ec-logout-form" action="{{ gp247_route_front('customer.logout') }}" method="POST" class="hidden">@csrf</form>
                    @endif
                </nav>
            @endif

            <ul class="flex items-center gap-4 border-t border-ink-100 p-4 list-none">
                <x-gp247-front::currency-switcher />
                <x-gp247-front::language-switcher />
            </ul>
        </div>
    </div>

</div>

@if (function_exists('gp247_cart'))
    {{-- Sync cart/wishlist/compare badges from any Livewire component that dispatches 'cart-updated'. --}}
    <script type="text/javascript">
        document.addEventListener('livewire:init', () => {
            Livewire.on('cart-updated', ({ count, instance, message }) => {
                const badgeClass = instance === 'default' ? 'cart' : instance;
                document.querySelectorAll('.gp247-' + badgeClass).forEach((el) => { el.textContent = count; });
                if (message && typeof alertJs === 'function') alertJs('success', message);
            });
            Livewire.on('cart-error', ({ message }) => { if (typeof alertJs === 'function') alertJs('error', message); });
            Livewire.on('cart-redirect', ({ url }) => { window.location.href = url; });
        });
    </script>
@endif
