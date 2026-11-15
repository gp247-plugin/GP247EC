{{--
    Shop search page: the ProductFilter Livewire component seeded with the keyword.

    Variables:
    - $keyword
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content')
<div class="lg:col-span-12 w-full">
    {{-- Product filter + grid --}}
    @livewire('gp247-shop-front::product-filter', ['initialKeyword' => $keyword ?? ''])

    @include($GP247TemplatePath.'.layout.extra_blocks')
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
