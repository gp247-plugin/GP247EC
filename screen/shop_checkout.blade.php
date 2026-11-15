{{--
    Checkout page: wraps the CheckoutWizard Livewire component (address →
    shipping → payment → confirm). The confirm step submits a normal form POST.
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full max-w-4xl mx-auto">
    <p class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-ink-500">
        <svg class="w-4 h-4 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        {{ trans('Templates/GP247EC::lang.ec.secure_checkout') }}
    </p>
    @livewire('gp247-shop-front::checkout-wizard')
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
