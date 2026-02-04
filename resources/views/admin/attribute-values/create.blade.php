@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Attribute Values</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> Home
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.attribute-values.index') }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> Attribute Values
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Create</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- ready to use form start -->
        <x-forms.form :action="route('admin.attribute-values.store')" method="post" varient="reactive" class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Attribute Value Detail</h5>
                    </div>
                    <div class="card-body">
                        <div class="app-form">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <x-forms.select varient="floating" name="attribute_id" label="Attribute"
                                        :options="$attributes" placeholder="Select Attribute" :error="$errors->first('attribute_id')" required />
                                </div>
                                <div class="col-12 mb-3">
                                    <x-forms.input id="value" varient="floating" name="value" label="Value"
                                        placeholder="Enter Value (e.g., Red, Large)" :value="old('value')" :error="$errors->first('value')"
                                        required />
                                </div>
                                <div class="col-12 mb-3">
                                    <x-forms.input id="code" varient="floating" name="code" label="Code (Optional)"
                                        placeholder="Enter Code (e.g., #FF0000 for color)" :value="old('code')"
                                        :error="$errors->first('code')" />
                                    <small class="text-muted">Optional: Use for color hex codes or other reference
                                        codes</small>
                                </div>
                                <div class="col-12">
                                    <div class="text-end">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-forms.form>
        <!-- ready to use form end -->
    </div>
@endsection
