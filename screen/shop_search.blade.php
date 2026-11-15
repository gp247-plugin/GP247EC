{{--
    Shop search page: the ProductFilter Livewire component seeded with the keyword.

    Variables:
    - $keyword
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content')
<div class="lg:col-span-12 w-full">
    {{-- Product filter + grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-[240px_minmax(0,1fr)] lg:grid-rows-[auto_1fr] gap-4 items-start">
    @livewire('gp247-shop-front::product-filter', ['initialKeyword' => $keyword ?? ''])
        @php
            $ecLeftBlocks = gp247_render_block('left', $layout_page ?? null);
        @endphp
        @if (trim($ecLeftBlocks) !== '')
            <div class="space-y-4 lg:col-start-1 lg:row-start-2" data-testid="storefront-listing-left-blocks">{!! $ecLeftBlocks !!}</div>
        @endif
    </div>

    @include($GP247TemplatePath.'.layout.extra_blocks', ['ecSkipLeft' => true])
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
