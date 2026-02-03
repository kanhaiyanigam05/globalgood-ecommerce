@extends('admin.layouts.auth')

@section('content')
    <div class="col-lg-7 image-content-box d-none d-lg-block">
        <div class="form-container">
            <div class="signup-content mt-4">
                <span>
                    <img alt="" class="img-fluid " src="{{ asset('admins/images/logo/1.png') }}">
                </span>
            </div>

            <div class="signup-bg-img">
                <img alt="" class="img-fluid" src="{{ asset('admins/images/login/01.png') }}">
            </div>
        </div>
    </div>
    <div class="col-lg-5 form-content-box">
        <div class="form-container">
            <x-forms.form class="app-form" varient="reactive" method="post" action="{{ route('vendor.authenticate') }}">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-5 text-center text-lg-start">
                            <h2 class="text-white f-w-600">Vendor <span class="text-dark">Login</span> </h2>
                            <p class="f-s-16 mt-2">Sign in to manage your store and products</p>
                        </div>
                    </div>
                    <div class="col-12 d-flex flex-column gap-3">
                        <x-forms.input varient="floating" id="email" label="Email Address" name="email"
                            placeholder="Enter your email" :required="true" :value="old('email')" :error="$errors->first('email')" />
                        <x-forms.input varient="floating" id="password" label="Password" name="password"
                            placeholder="******" type="password" :required="true" :value="old('password')" :error="$errors->first('password')" />
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <x-forms.checkbox id="remember" name="remember" label="Remember me" :value="old('remember')"
                                :error="$errors->first('remember')" />
                            <a href="#" class="text-primary f-s-14">Forgot Password?</a>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100" role="button">
                            Sign In
                        </button>
                    </div>
                    <div class="col-12 mt-3 text-center">
                        <p class="mb-0">Don't have an account? <a href="{{ route('vendor.register') }}" class="text-primary">Register functionality</a></p>
                    </div>
                </div>
            </x-forms.form>
        </div>
    </div>
@endsection
