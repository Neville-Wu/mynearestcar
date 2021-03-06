@extends('layouts.auth')

@section('content')

    <div class="card card-info">
        <div class="card-header"><h4>{{ __('Admin Login') }}</h4></div>

        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
<!--                <input type="hidden" name="admin_redirect" value="yes">-->

                <div class="form-group row mb-2">
                    <label for="email" class="col-md-12 col-form-label">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-12">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>

                    <div class="col-md-12">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-block btn-info">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link btn-block mt-2" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="mt-5 text-muted text-center">
        <p>Don't have a staff account? <a href="{{ route('admin.register') }}">Create One</a></p>
        <p>Want to login as customer? <a href="{{ route('login') }}">Click here</a></p>
    </div>

@endsection
