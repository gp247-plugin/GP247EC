{{--
    Order success page. The order number comes from session('orderID'), which the
    checkout sets after the order is created.
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content')
<div class="lg:col-span-12 w-full max-w-2xl mx-auto">
    <div class="card overflow-hidden text-center" data-testid="storefront-order-success">
        <div class="ec-bar px-6 py-10">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full border-4 border-brand-500 bg-white">
                <svg class="w-10 h-10 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold uppercase tracking-wide text-ink-900">{{ $title }}</h1>
            <p class="mt-2 text-sm text-ink-600">{{ gp247_language_render('checkout.order_success_msg') }}</p>
        </div>
        <div class="px-6 py-8">
            <p class="inline-block rounded-sm border border-dashed border-brand-400 bg-brand-50 px-4 py-2 text-sm font-semibold text-brand-700">{{ gp247_language_render('checkout.order_success_order_info', ['order_id' => session('orderID')]) }}</p>
            <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ gp247_route_front('front.home') }}" class="btn-primary">{{ gp247_language_quickly('action.continue_shopping', 'Continue shopping') }}</a>
                @if (function_exists('customer') && customer()->user())
                    <a href="{{ gp247_route_front('customer.order_list') }}" class="btn-outline">{{ gp247_language_render('customer.order_history') }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
