{{--
    Customer account home: greeting plus shortcuts to order history, addresses
    and profile. The controller only passes $customer (no order statistics).

    Variables:
    - $customer: the signed-in customer
--}}
@php
    $view = gp247_shop_process_view($GP247TemplatePath, 'account.shop_layout');
@endphp
@extends($view)

@section('block_main_profile')
<div class="flex flex-col sm:flex-row sm:items-center gap-4 rounded-sm bg-gradient-to-r from-brand-50 to-white p-5">
    <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-brand-500 text-2xl font-bold uppercase text-white">{{ mb_substr((string) $customer['first_name'], 0, 1) }}</span>
    <div>
        <p class="text-lg font-bold text-ink-900">{{ gp247_language_quickly('customer.welcome', 'Welcome') }}, {{ $customer['first_name'] }} {{ $customer['last_name'] }}!</p>
        <p class="text-sm text-ink-500">{{ $customer['email'] }}</p>
    </div>
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-px overflow-hidden rounded-sm border border-ink-200 bg-ink-200">
    @foreach ([
        ['customer.order_list', 'customer.order_history', '<path d="M3 3h2l2.6 12.4a2 2 0 002 1.6h8.8a2 2 0 002-1.6L21 8H6"/>'],
        ['customer.address_list', 'customer.address_list', '<path d="M12 21s-6-5.2-6-10a6 6 0 1112 0c0 4.8-6 10-6 10z"/><circle cx="12" cy="11" r="2"/>'],
        ['customer.change_infomation', 'customer.change_infomation', '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>'],
    ] as [$ecRoute, $ecLabel, $ecIcon])
        <a href="{{ gp247_route_front($ecRoute) }}" class="group flex items-center gap-3 bg-white px-5 py-5 hover:bg-brand-50">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-ink-100 text-ink-700 group-hover:bg-brand-500 group-hover:text-white transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $ecIcon !!}</svg>
            </span>
            <span class="text-sm font-semibold text-ink-800 group-hover:text-brand-800">{{ gp247_language_render($ecLabel) }}</span>
        </a>
    @endforeach
</div>
@endsection
