{{--
    Cart page: a thin wrapper — all cart behaviour lives in the CartManager
    Livewire component (livewire/shop_cart-manager).
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full">
    @livewire('gp247-shop-front::cart-manager')
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
