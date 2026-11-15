{{--
    Reset-password form (reached from the e-mailed link). The controller passes no
    e-mail address, so the field starts empty.

    Variables:
    - $token: password reset token
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full py-2 sm:py-6">
<div class="card mx-auto grid max-w-4xl overflow-hidden md:grid-cols-2">
    @include($GP247TemplatePath.'.layout.auth_aside')
    <div class="p-6 sm:p-10">
    <h1 class="mb-6 border-b-2 border-brand-500 pb-3 text-xl font-extrabold uppercase tracking-wide text-ink-900">{{ gp247_language_render('customer.password_reset') }}</h1>

    <form method="POST" action="{{ gp247_route_front('customer.password_request') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label class="label">{{ gp247_language_render('customer.email') }}</label>
            <input type="email" class="input @error('email') input-error @enderror" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="label">{{ gp247_language_render('customer.password_new') }}</label>
            <input type="password" class="input @error('password') input-error @enderror" name="password" required>
            @error('password')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="label">{{ gp247_language_render('customer.password_confirm') }}</label>
            <input type="password" class="input @error('password_confirmation') input-error @enderror" name="password_confirmation" required>
            @error('password_confirmation')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn-primary btn-lg w-full">{{ gp247_language_render('customer.password_reset') }}</button>
    </form>
</div>
</div>
</div>
@endsection
