{{--
    Customer login. Extends the top-level layout directly (not the account layout).
    A social-login plugin, when active, renders its buttons below the form.

    Variables: none beyond $errors / old().
--}}
@extends($GP247TemplatePath.'.layout')

@section('block_main_content_center')
<div class="lg:col-span-12 w-full py-2 sm:py-6">
<div class="card mx-auto grid max-w-4xl overflow-hidden md:grid-cols-2">
    @include($GP247TemplatePath.'.layout.auth_aside')
    <div class="p-6 sm:p-10">
    <h1 class="mb-6 border-b-2 border-brand-500 pb-3 text-xl font-extrabold uppercase tracking-wide text-ink-900">{{ gp247_language_render('customer.title_login') }}</h1>

    <form method="POST" action="{{ gp247_route_front('customer.postLogin') }}" class="space-y-4">
        @csrf
        <div>
            <label class="label">{{ gp247_language_render('customer.email') }}</label>
            <input type="email" class="input @error('email') input-error @enderror" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="label">{{ gp247_language_render('customer.password') }}</label>
            <input type="password" class="input @error('password') input-error @enderror" name="password" required>
            @error('password')<p class="text-xs text-accent-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" class="rounded">
            {{ gp247_language_render('customer.remember_me') }}
        </label>

        <button type="submit" class="btn-primary btn-lg w-full">{{ gp247_language_render('front.login') }}</button>
    </form>

    @if (gp247_extension_check_active('Plugins', 'LoginSocial'))
        <div class="divider my-6"></div>
        @include('Plugins/LoginSocial::render', ['type' => 'front'])
    @endif

    <div class="divider my-6"></div>

    <div class="flex items-center justify-between text-sm">
        <a href="{{ gp247_route_front('customer.forgot') }}" class="font-semibold text-brand-700 hover:text-brand-800">{{ gp247_language_render('customer.password_forgot') }}</a>
        <a href="{{ gp247_route_front('customer.register') }}" class="font-semibold text-brand-700 hover:text-brand-800">{{ gp247_language_render('customer.title_register') }}</a>
    </div>
</div>
</div>
</div>
@endsection
