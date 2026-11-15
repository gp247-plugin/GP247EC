{{--
    "Item not found" page (RootFrontController::itemNotFound()): shown when a
    product, category or page does not exist.
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <h2 class="text-2xl font-semibold text-ink-600">{{ gp247_language_render('front.notfound') }}</h2>
        <p class="text-ink-500 mt-4 mb-8">{{ $msg ?? '' }}</p>
        <a href="{{ gp247_route_front('front.home') }}" class="btn-primary">
            {{ gp247_language_render('front.backhome') }}
        </a>
    </div>
</div>
@endsection
