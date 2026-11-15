{{--
    Breadcrumb component (GP247\Front\TemplateComponents\Breadcrumb).
    Framework-neutral markup with the fixed `gp247-breadcrumb*` class contract;
    styled by common/gp247-components-css. Page spacing is the layout's job.
--}}
@if (count($items))
<nav class="gp247-breadcrumb">
    <ul class="gp247-breadcrumb__path">
        <li class="gp247-breadcrumb__item"><a class="gp247-breadcrumb__link" href="{{ gp247_route_front('front.home') }}">{{ gp247_language_render('front.home') }}</a></li>
        @foreach ($items as $item)
            @if ($item['active'])
            <li class="gp247-breadcrumb__item gp247-breadcrumb__item--active">{{ $item['title'] }}</li>
            @else
            <li class="gp247-breadcrumb__item"><a class="gp247-breadcrumb__link" href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
            @endif
        @endforeach
    </ul>
</nav>
@endif
