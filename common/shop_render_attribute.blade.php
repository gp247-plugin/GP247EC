{{--
    Product attribute option list — Tailwind port of
    vendor/gp247/shop/.../front/common/shop_render_attribute.blade.php. Called
    by $product->renderAttributeDetails() (ShopProduct.php, unchanged) — used
    by screen/shop_product_detail.blade.php.
--}}
@if (!empty($details) && count($details))
<div class="space-y-4">
    @foreach ($details as $groupId => $detailsGroup)
    <div>
        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-ink-500">{{ $groups[$groupId] ?? 'Not found' }}</p>
        <div class="flex flex-wrap gap-2" role="radiogroup">
            @foreach ($detailsGroup as $k => $detail)
            @php
                $valueOption = $detail->name.'__'.$detail->add_price;
            @endphp
            <label class="relative cursor-pointer rounded-sm border border-ink-300 bg-white px-3 py-1.5 text-sm text-ink-800 hover:border-brand-500 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:font-semibold has-[:checked]:text-brand-700">
                <input class="sr-only" type="radio" name="form_attr[{{ $groupId }}]" value="{{ $valueOption }}" {{ ($k == 0) ? 'checked' : '' }}>
                {!! gp247_render_option_price($valueOption) !!}
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endif
