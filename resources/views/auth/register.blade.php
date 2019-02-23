@extends('layouts.app-register')

@section('content')
<div class="row align-items-center h-100">
    <div class="col-sm-10 col-md-8 col-lg-6 mx-auto my-4">

        <div class="text-center">
            <h1 class="h3">Get started</h1>
            <p class="lead">Create an account to continue</p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="m-sm-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <label for="username">{{ __('Username') }}</label>
                            <input id="username" class="form-control form-control-lg{{ $errors->has('username') ? ' is-invalid' : '' }}" type="text" name="username" placeholder="Enter your username" value="{{ old('username') }}" required/>

                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input id="email" class="form-control form-control-lg{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required/>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" class="form-control form-control-lg{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="Enter your password" required/>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation ">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" class="form-control form-control-lg" type="password" name="password_confirmation" placeholder="Confirm your password" required/>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-lg btn-primary btn-block mb-2 mt-4">{{ __('Sign up') }}</button>
                        </div>
                        <div class="text-center">
                            Already have an account? <a href="{{ route('login') }}">Sign in</a>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.card -->
    </div>
</div><!-- /.row -->
@endsection



