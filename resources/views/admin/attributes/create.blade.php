@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Attributes</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> Home
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.attributes.index') }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> Attributes
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
        <x-forms.form :action="route('admin.attributes.store')" method="post" varient="reactive" class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Attribute Detail</h5>
                    </div>
                    <div class="card-body">
                        <div class="app-form">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <x-forms.input id="name" varient="floating" name="name" label="Name"
                                        placeholder="Enter Attribute Name (e.g., Color, Size)" :value="old('name')"
                                        :error="$errors->first('name')" required />
                                </div>
                                <div class="col-12 mb-3">
                                    <x-forms.select varient="floating" name="scope" label="Scope" :options="$scopes"
                                        placeholder="Select Scope" :error="$errors->first('scope')" required />
                                    <small class="text-muted">Product: Applied at product level. Variant: Applied at variant
                                        level (e.g., Size, Color for variants)</small>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Categories (Optional)</label>
                                    <select class="form-select select2" name="categories[]" multiple>
                                        @foreach ($categories as $id => $title)
                                            <option value="{{ $id }}">{{ $title }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Leave empty to make this attribute available for all
                                        categories.</small>
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
