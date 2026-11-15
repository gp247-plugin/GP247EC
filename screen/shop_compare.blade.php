{{--
    Compare page: products side by side (image, name, price, description).
    Each row's product is resolved with $modelProduct->start()->getDetail().

    Variables:
    - $compare: compare list items
    - $title
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full">
    <div class="mb-3 rounded border border-brand-200 border-b-2 border-b-brand-500 bg-gradient-to-r from-brand-50 to-white px-4 py-3"><h1 class="text-sm sm:text-base font-bold uppercase tracking-wide text-ink-900">{{ $title }}</h1></div>

    @if (count($compare) == 0)
        <div class="card text-center py-20">
            <svg class="w-20 h-20 mx-auto text-ink-200 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 3l4 4-4 4M7 21l-4-4 4-4M3 7h14M21 17H7"/></svg>
            <p class="text-lg font-semibold text-ink-700">{{ gp247_language_render('front.no_item') }}</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <div class="flex gap-4 min-w-min pb-2">
                @foreach ($compare as $item)
                    @php
                        $product = $modelProduct->start()->getDetail($item->id, null, $item->storeId);
                    @endphp
                    @if ($product)
                    <div class="card p-4 w-56 shrink-0 flex flex-col">
                        <a href="{{ gp247_route_front('cart.remove', ['id' => $item->rowId, 'instance' => 'compare']) }}"
                            onclick="return confirm('{{ e(gp247_language_quickly('cart.confirm_remove', 'Remove this item from the cart?')) }}')"
                            class="self-end btn-icon h-7 w-7 mb-1" aria-label="{{ gp247_language_render('action.remove') ?? 'Remove' }}">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        </a>
                        <a href="{{ $product->getUrl() }}" class="block relative aspect-square overflow-hidden bg-ink-50 rounded-lg mb-3">
                            <img src="{{ gp247_file($product->getImage()) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </a>
                        <a href="{{ $product->getUrl() }}" class="text-sm font-medium text-ink-800 clamp-2 hover:text-brand-550">{{ $product->name }}</a>
                        <p class="text-xs text-ink-400 mt-0.5 mb-2">{{ gp247_language_render('product.sku') }}: {{ $product->sku }}</p>
                        {!! $product->showPrice() !!}
                        <div class="divider my-3"></div>
                        <p class="text-xs text-ink-500 line-clamp-6">{{ $product->description }}</p>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
