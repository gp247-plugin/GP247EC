{{--
    Account section layout: customer sidebar (shop_nav_customer) + content panel.
    Overrides the layout's `block_main_content`; account screens fill
    `block_main_profile`.

    Variables:
    - $title: page title, shown in the content panel header
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full grid grid-cols-1 md:grid-cols-[250px_minmax(0,1fr)] gap-4 items-start">
    <aside class="card overflow-hidden">
        @php
            $ecCustomer = function_exists('customer') ? customer()->user() : null;
        @endphp
        <div class="flex items-center gap-3 ec-bar px-4 py-4 text-ink-900">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-500 text-lg font-bold uppercase">
                {{ mb_substr((string) ($ecCustomer->first_name ?? '?'), 0, 1) }}
            </span>
            <span class="min-w-0 leading-tight">
                <span class="block text-[11px] uppercase tracking-wide text-brand-700">{{ trans('Templates/GP247EC::lang.ec.customer_center') }}</span>
                <span class="block truncate text-sm font-semibold">{{ trim(($ecCustomer->first_name ?? '').' '.($ecCustomer->last_name ?? '')) }}</span>
            </span>
        </div>
        @php
            $view = gp247_shop_process_view($GP247TemplatePath, 'account.shop_nav_customer');
        @endphp
        @include($view)
    </aside>
    <div class="card overflow-hidden">
        <div class="border-b border-ink-100 px-5 py-4">
            <h1 class="section-title">{{ $title }}</h1>
        </div>
        <div class="p-4 sm:p-5">
            @section('block_main_profile')
            @show
        </div>
    </div>
</div>
@endsection
