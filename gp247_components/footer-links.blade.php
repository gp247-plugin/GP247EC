{{--
    Footer links component (GP247\Front\TemplateComponents\FooterLinks).
    Renders the admin's footer link groups with the fixed `gp247-footer-links*`
    class contract; a collection link shows as "label: child, child".
--}}
<ul class="gp247-footer-links">
    @foreach ($links as $link)
    @if ($link['type'] === 'collection')
    <li class="gp247-footer-links__item gp247-footer-links__item--group">
        <span class="gp247-footer-links__label">{{ $link['name'] }}:</span>
        @foreach ($link['children'] as $child)
        <a class="gp247-footer-links__link gp247-footer-links__link--child" {{ $child['target'] === '_blank' ? 'target=_blank' : '' }} href="{{ $child['url'] }}">{{ $child['name'] }}</a>{{ !$loop->last ? ',' : '' }}
        @endforeach
    </li>
    @else
    <li class="gp247-footer-links__item">
        <a class="gp247-footer-links__link" {{ $link['target'] === '_blank' ? 'target=_blank' : '' }} href="{{ $link['url'] }}">{{ $link['name'] }}</a>
    </li>
    @endif
    @endforeach
</ul>
