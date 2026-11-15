{{-- GP247-EC generic tile (CMS pages, brands, categories): image + title, used inside a gap-px grid panel. --}}
@php
    $thumb = $item['thumb'] ?? '';
    $url = $item['url'] ?? '';
    $title = $item['title'] ?? '';
@endphp
<article class="product-card group h-full">
    <a href="{{ $url }}" class="block relative aspect-[4/3] overflow-hidden bg-white">
        <img src="{{ gp247_file($thumb) }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition duration-300" loading="lazy">
    </a>
    <div class="border-t border-ink-100 px-3 py-2.5">
        <a href="{{ $url }}" class="text-[13px] font-semibold text-ink-800 clamp-2 hover:text-brand-600">{{ $title }}</a>
    </div>
</article>
