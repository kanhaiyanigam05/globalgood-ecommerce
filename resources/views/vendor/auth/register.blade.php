@extends('admin.layouts.auth')

@section('content')
    <div class="col-lg-5 image-content-box d-none d-lg-block">
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
    <div class="col-lg-7 form-content-box">
        <div class="form-container">
            <x-forms.form class="app-form" varient="reactive" method="post" action="{{ route('vendor.store') }}">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-5 text-center text-lg-start">
                            <h2 class="text-white f-w-600">Vendor <span class="text-dark">Registration</span> </h2>
                            <p class="f-s-16 mt-2">Join us and start selling your products</p>
                        </div>
                    </div>
                    <div class="col-12 d-flex flex-column gap-3">
                        <div class="row">
                            <div class="col-md-6">
                                <x-forms.input varient="floating" id="legal_name" label="Legal Name" name="legal_name"
                                    placeholder="Enter legal name" :required="true" :value="old('legal_name')" :error="$errors->first('legal_name')" />
                            </div>
                            <div class="col-md-6">
                                <x-forms.input varient="floating" id="display_name" label="Display Name (Optional)" name="display_name"
                                    placeholder="Enter display name" :required="false" :value="old('display_name')" :error="$errors->first('display_name')" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-forms.input varient="floating" id="email" label="Email Address" name="email"
                                    placeholder="Enter email" :required="true" :value="old('email')" :error="$errors->first('email')" />
                            </div>
                            <div class="col-md-6">
                                <x-forms.input varient="floating" id="phone" label="Phone Number" name="phone"
                                    placeholder="Enter phone number" :required="true" :value="old('phone')" :error="$errors->first('phone')" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-forms.input varient="floating" id="store_name" label="Store Name" name="store_name"
                                    placeholder="Enter store name" :required="true" :value="old('store_name')" :error="$errors->first('store_name')" />
                            </div>
                             <div class="col-md-6">
                                <x-forms.input varient="floating" id="password" label="Password" name="password"
                                    placeholder="******" type="password" :required="true" :value="old('password')" :error="$errors->first('password')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100" role="button">
                            Register Vendor
                        </button>
                    </div>
                    <div class="col-12 mt-3 text-center">
                        <p class="mb-0">Already have an account? <a href="{{ route('vendor.login') }}" class="text-primary">Sign In</a></p>
                    </div>
                </div>
            </x-forms.form>
        </div>
    </div>
@endsection
