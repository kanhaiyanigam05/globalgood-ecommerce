@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Breadcrumb start -->
                <div class="row m-1">
                    <div class="col-12">
                        <h4 class="main-title">Customers</h4>
                        <ul class="app-line-breadcrumbs mb-3">
                            <li>
                                <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                                    <span><i class="ph-duotone ph-house f-s-16"></i> Home</span>
                                </a>
                            </li>
                            <li>
                                <a class="f-s-14 f-w-500" href="{{ route('admin.customers.index') }}">
                                    <span><i class="ph-duotone ph-users f-s-16"></i> Customers</span>
                                </a>
                            </li>
                            <li class="active">
                                <a class="f-s-14 f-w-500" href="#">Edit Customer</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Breadcrumb end -->

                <!-- Main Customer Form -->
                <x-forms.form :action="route('admin.customers.update', Crypt::encryptString($customer->id))" method="put" varient="reactive" class="row">
                    <div class="col-lg-8 col-md-7">
                        <!-- Customer Overview Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ph-duotone ph-user-circle"></i> Customer overview</h5>
                            </div>
                            <div class="card-body">
                                <div class="app-form">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-forms.input name="first_name" label="First name"
                                                placeholder="Enter First Name" :value="old('first_name', $customer->first_name)" :error="$errors->first('first_name')"
                                                required />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-forms.input name="last_name" label="Last name"
                                                placeholder="Enter Last Name" :value="old('last_name', $customer->last_name)" :error="$errors->first('last_name')"
                                                required />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Language</label>
                                            <select name="language" class="form-select">
                                                <option value="en" {{ old('language', $customer->language) == 'en' ? 'selected' : '' }}>English [Default]</option>
                                                <option value="hi" {{ old('language', $customer->language) == 'hi' ? 'selected' : '' }}>Hindi</option>
                                                <option value="es" {{ old('language', $customer->language) == 'es' ? 'selected' : '' }}>Spanish</option>
                                            </select>
                                            <small class="text-muted">This customer will receive notifications in this language.</small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.input type="email" name="email" label="Email"
                                                placeholder="email@example.com" :value="old('email', $customer->email)" :error="$errors->first('email')"
                                                required />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.input name="phone" label="Phone number"
                                                placeholder="Enter Phone Number" :value="old('phone', $customer->phone)" :error="$errors->first('phone')" />
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="email_marketing_consent" id="email_marketing_consent" value="1" {{ old('email_marketing_consent', $customer->email_marketing_consent) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_marketing_consent">
                                                    Customer agreed to receive marketing emails.
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="sms_marketing_consent" id="sms_marketing_consent" value="1" {{ old('sms_marketing_consent', $customer->sms_marketing_consent) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sms_marketing_consent">
                                                    Customer agreed to receive SMS marketing text messages.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Default Address Card -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5><i class="ph-duotone ph-map-pin"></i> Default address</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">The primary address of this customer</p>
                                @php $defaultAddress = $customer->defaultAddress; @endphp
                                
                                <div id="address_summary_container" {!! !$defaultAddress ? 'style="display: none;"' : '' !!}>
                                    <div class="border rounded p-3 position-relative">
                                        <div id="address_summary_text">
                                            @if($defaultAddress)
                                                <div class="f-w-500">{{ $defaultAddress->first_name }} {{ $defaultAddress->last_name }}</div>
                                                @if($defaultAddress->company) <div>{{ $defaultAddress->company }}</div> @endif
                                                <div>{{ $defaultAddress->address1 }}</div>
                                                @if($defaultAddress->address2) <div>{{ $defaultAddress->address2 }}</div> @endif
                                                <div>{{ $defaultAddress->zip }} {{ $defaultAddress->city }}</div>
                                                <div>{{ $defaultAddress->province ? $defaultAddress->province . ', ' : '' }}{{ $defaultAddress->country }}</div>
                                                <div>{{ $defaultAddress->phone }}</div>
                                            @endif
                                        </div>
                                        <i class="ph ph-pencil position-absolute" style="right: 15px; top: 15px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#addAddressModal"></i>
                                    </div>
                                </div>

                                <div id="add_address_placeholder" class="border rounded p-3 text-center d-flex justify-content-between align-items-center cursor-pointer" style="border-style: dashed !important; {!! $defaultAddress ? 'display: none !important;' : '' !!}" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <span><i class="ph ph-plus-circle"></i> Add address</span>
                                    <i class="ph ph-caret-right"></i>
                                </div>
                                <small class="text-muted mt-2 d-block">You can add multiple addresses after creating the customer.</small>
                                
                                <!-- Hidden inputs for address data -->
                                <div id="address_hidden_inputs">
                                    @if($defaultAddress)
                                        <input type="hidden" name="address[id]" value="{{ $defaultAddress->id }}">
                                        <input type="hidden" name="address[country]" value="{{ $defaultAddress->country }}">
                                        <input type="hidden" name="address[first_name]" value="{{ $defaultAddress->first_name }}">
                                        <input type="hidden" name="address[last_name]" value="{{ $defaultAddress->last_name }}">
                                        <input type="hidden" name="address[company]" value="{{ $defaultAddress->company }}">
                                        <input type="hidden" name="address[address1]" value="{{ $defaultAddress->address1 }}">
                                        <input type="hidden" name="address[address2]" value="{{ $defaultAddress->address2 }}">
                                        <input type="hidden" name="address[city]" value="{{ $defaultAddress->city }}">
                                        <input type="hidden" name="address[province]" value="{{ $defaultAddress->province }}">
                                        <input type="hidden" name="address[zip]" value="{{ $defaultAddress->zip }}">
                                        <input type="hidden" name="address[phone]" value="{{ $defaultAddress->phone }}">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tax Details Card -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5><i class="ph-duotone ph-receipt"></i> Tax details</h5>
                            </div>
                            <div class="card-body">
                                <label class="form-label">Tax settings</label>
                                <select name="tax_setting" class="form-select">
                                    <option value="collect" {{ old('tax_setting', $customer->tax_setting) == 'collect' ? 'selected' : '' }}>Collect tax</option>
                                    <option value="exempt" {{ old('tax_setting', $customer->tax_setting) == 'exempt' ? 'selected' : '' }}>Tax exempt</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-5">
                        <!-- Notes Card -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>Notes</h5>
                                <i class="ph ph-pencil"></i>
                            </div>
                            <div class="card-body">
                                <x-forms.textarea name="notes" placeholder="Notes are private and won't be shared with the customer." :value="old('notes', $customer->notes)" />
                            </div>
                        </div>

                        <!-- Tags Card -->
                        <div class="card mt-3">
                            <div class="card-header d-flex justify-content-between">
                                <h5>Tags</h5>
                                <i class="ph ph-pencil"></i>
                            </div>
                            <div class="card-body">
                                <x-forms.input name="tags" placeholder="Search or create tags" :value="old('tags', $customer->tags)" />
                                <small class="text-muted mt-2 d-block">Tags can be used to categorize customers.</small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="ph-duotone ph-check-circle"></i> Update Customer
                                </button>
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary text-center">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>

    @include('admin.customers.partials.address-modal')
@endsection

@push('scripts:after')
<script>
    $(document).ready(function() {
        const modal = new bootstrap.Modal(document.getElementById('addAddressModal'));

        // Handle Pencil Icon Click (Edit)
        $(document).on('click', '.ph-pencil[data-bs-target="#addAddressModal"]', function() {
            // Pre-fill modal with existing hidden input values
            $('#modal_country').val($('input[name="address[country]"]').val() || 'India');
            $('#modal_first_name').val($('input[name="address[first_name]"]').val());
            $('#modal_last_name').val($('input[name="address[last_name]"]').val());
            $('#modal_company').val($('input[name="address[company]"]').val());
            $('#modal_address1').val($('input[name="address[address1]"]').val());
            $('#modal_address2').val($('input[name="address[address2]"]').val());
            $('#modal_city').val($('input[name="address[city]"]').val());
            $('#modal_province').val($('input[name="address[province]"]').val());
            $('#modal_zip').val($('input[name="address[zip]"]').val());
            $('#modal_phone').val($('input[name="address[phone]"]').val());
        });

        $('#saveAddressBtn').on('click', function() {
            const addressData = {
                id: $('input[name="address[id]"]').val(), // Keep ID if editing existing
                country: $('#modal_country').val(),
                first_name: $('#modal_first_name').val(),
                last_name: $('#modal_last_name').val(),
                company: $('#modal_company').val(),
                address1: $('#modal_address1').val(),
                address2: $('#modal_address2').val(),
                city: $('#modal_city').val(),
                province: $('#modal_province').val(),
                zip: $('#modal_zip').val(),
                phone: $('#modal_phone').val()
            };

            // Basic validation
            if (!addressData.address1 || !addressData.city || !addressData.country) {
                window.toast.error('Please fill in required address fields (Address, City, Country)');
                return;
            }

            // Update Summary UI
            let summaryHtml = `
                <div class="f-w-500">${addressData.first_name} ${addressData.last_name}</div>
                ${addressData.company ? `<div>${addressData.company}</div>` : ''}
                <div>${addressData.address1}</div>
                ${addressData.address2 ? `<div>${addressData.address2}</div>` : ''}
                <div>${addressData.zip} ${addressData.city}</div>
                <div>${addressData.province ? addressData.province + ', ' : ''}${addressData.country}</div>
                <div>${addressData.phone}</div>
            `;
            
            $('#address_summary_text').html(summaryHtml);
            $('#add_address_placeholder').hide();
            $('#address_summary_container').show();

            // Create/Update Hidden Inputs
            let hiddenInputsHtml = '';
            for (const [key, value] of Object.entries(addressData)) {
                if (value) {
                    hiddenInputsHtml += `<input type="hidden" name="address[${key}]" value="${value}">`;
                }
            }
            $('#address_hidden_inputs').html(hiddenInputsHtml);

            // Close Modal
            bootstrap.Modal.getInstance(document.getElementById('addAddressModal')).hide();
        });
    });
</script>
@endpush

