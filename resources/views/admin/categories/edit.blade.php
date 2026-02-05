@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Categories</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> Home
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.categories.index') }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> Categories
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Edit</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- ready to use form start -->


        <x-forms.form :action="route('admin.categories.update', Crypt::encryptString($category->id))" method="put" enctype="multipart/form-data" varient="reactive" class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>Category Detail</h5>
                    </div>
                    <div class="card-body">
                        <div class="app-form">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <x-forms.hierarchical-select id="parent_id" name="parent_id" label="Parent Category"
                                        placeholder="Select a category" searchPlaceholder="Search categories..."
                                        :options="$categories" apiUrl="{{ route('admin.categories.hierarchical_data') }}"
                                        :value="old('parent_id', $category->parent_id)" :error="$errors->first('parent_id')" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.input id="title" name="title" label="Title"
                                        placeholder="Enter Category Title" :value="old('title', $category->title)" :error="$errors->first('title')" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.input id="slug" name="slug" label="Slug"
                                        placeholder="Enter Category Slug" :value="old('slug', $category->slug)" :error="$errors->first('slug')" required />
                                </div>

                                <div class="col-12 mb-3">
                                    <x-forms.file id="image" name="image" label="Category Image"
                                        :value="$category->media ?: $category->image"
                                        :error="$errors->first('image')" :useMediaLibrary="true" directory="categories" />
                                </div>
                                <div class="col-12 mb-3">
                                    <x-forms.editor id="description" rows="4" name="description" label="Description"
                                        placeholder="Enter Category Description" :value="old('description', $category->description)" :error="$errors->first('description')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Meta Detail</h5>
                    </div>
                    <div class="card-body">
                        <div class="app-form">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <x-forms.input name="meta_title" label="Meta Title" placeholder="Enter meta title"
                                        :value="old('meta_title', $category->meta_title)" :error="$errors->first('meta_title')" />
                                </div>
                                <div class="col-12 mb-3">
                                    <x-forms.input name="meta_keywords" label="Meta Keywords"
                                        placeholder="Enter meta keywords" :value="old('meta_keywords', $category->meta_keywords)" :error="$errors->first('meta_keywords')" />
                                </div>
                                <div class="col-12 mb-3">
                                    <x-forms.textarea rows="3" name="meta_description" label="Meta Description"
                                        placeholder="Enter meta description" :value="old('meta_description', $category->meta_description)" :error="$errors->first('meta_description')" />
                                </div>
                                <div class="col-12">
                                    <div class="text-end">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                            </d>
                        </div>
                    </div>
                </div>
        </x-forms.form>
        <!-- ready to use form and -->
    </div>
@endsection
@push('scripts:after')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (!titleInput || !slugInput) return;

            let slugManuallyEdited = false;

            const slugify = (text) => {
                return text
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/\s+/g, '-') // spaces to hyphens
                    .replace(/[^\w\-]+/g, '') // remove non-word chars
                    .replace(/\-\-+/g, '-'); // collapse multiple hyphens
            };

            // Detect manual slug change
            slugInput.addEventListener('input', () => {
                slugManuallyEdited = slugInput.value.length > 0;
            });

            // Auto-generate slug from title
            titleInput.addEventListener('input', () => {
                if (!slugManuallyEdited) {
                    slugInput.value = slugify(titleInput.value);
                }
            });

            // Optional: resume auto-sync if slug cleared
            slugInput.addEventListener('blur', () => {
                if (slugInput.value.trim() === '') {
                    slugManuallyEdited = false;
                    slugInput.value = slugify(titleInput.value);
                }
            });
        });
    </script>
@endpush
