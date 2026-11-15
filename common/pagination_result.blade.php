{{-- GP247-EC result counter ("Showing x–y of z"); inherits colour from its container. --}}
<span class="text-xs">
    {!! gp247_language_render('front.result_item', ['item_from' => $items->firstItem(), 'item_to' => $items->lastItem(), 'total' => $items->total()]) !!}
</span>
