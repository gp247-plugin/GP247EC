{{--
    Account section layout: customer sidebar (shop_nav_customer) + content panel.
    Overrides the layout's `block_main_content`; account screens fill
    `block_main_profile`.

    Variables:
    - $title: page title, shown in the content panel header

    Above the content: the "account about to be erased" notice when the customer asked to delete
    the account (shown only on platforms that have it).
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
                <span class="block text-[11px] uppercase tracking-wide text-brand-600">{{ trans('Templates/GP247EC::lang.ec.customer_center') }}</span>
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
            @php
                // WHY function_exists: EC also runs on platforms without self-service account deletion.
                $privacyErasure = function_exists('gp247_shop_privacy_pending_erasure') ? gp247_shop_privacy_pending_erasure() : null;
            @endphp
            @if ($privacyErasure)
                {{-- The account is about to be erased: say when, offer to call it off. --}}
                <div class="rounded-sm border-s-4 border-accent-600 bg-accent-50 text-accent-700 text-sm px-4 py-3 mb-4 flex flex-wrap items-center justify-between gap-3" role="status" data-testid="customer-privacy-delete-banner">
                    <span>{{ $privacyErasure['status'] === 'on_hold'
                        ? gp247_language_render('customer.privacy.delete_on_hold')
                        : gp247_language_render('customer.privacy.delete_scheduled', ['date' => (string) $privacyErasure['runs_at']]) }}</span>
                    <form method="POST" action="{{ gp247_route_front('customer.privacy_delete_cancel') }}">
                        @csrf
                        <button class="btn-outline btn-sm" type="submit" data-testid="customer-privacy-delete-banner-cancel">{{ gp247_language_render('customer.privacy.cancel_button') }}</button>
                    </form>
                </div>
            @endif
            @section('block_main_profile')
            @show
        </div>
    </div>
</div>
@endsection
