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
                                            <x-forms.input name="last_name" label="Last name" placeholder="Enter Last Name"
                                                :value="old('last_name', $customer->last_name)" :error="$errors->first('last_name')" required />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Language</label>
                                            <select name="language" class="form-select">
                                                <option value="en"
                                                    {{ old('language', $customer->language) == 'en' ? 'selected' : '' }}>
                                                    English [Default]</option>
                                                <option value="hi"
                                                    {{ old('language', $customer->language) == 'hi' ? 'selected' : '' }}>
                                                    Hindi</option>
                                                <option value="es"
                                                    {{ old('language', $customer->language) == 'es' ? 'selected' : '' }}>
                                                    Spanish</option>
                                            </select>
                                            <small class="text-muted">This customer will receive notifications in this
                                                language.</small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.input type="email" name="email" label="Email"
                                                placeholder="email@example.com" :value="old('email', $customer->email)" :error="$errors->first('email')"
                                                required />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Phone number</label>
                                            <div class="input-group">
                                                <div class="position-relative d-flex align-items-center"
                                                    style="width: 80px;">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center w-100 h-100 bg-white border rounded-start px-2">
                                                        <img src="{{ asset('flags/untitle.svg') }}" id="main_phone_flag"
                                                            width="24" alt="Flag" style="object-fit: contain;">
                                                        <i class="ph ph-caret-down ms-2 f-s-12 text-muted"></i>
                                                    </div>
                                                    <select
                                                        class="form-select position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                                        name="phone_code" id="phone_code_select"
                                                        style="cursor: pointer; z-index: 10;">
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->telcode }}"
                                                                data-flag="{{ $country->flag_url }}"
                                                                @selected($country->telcode == $customer->tel)>
                                                                {{ $country->name }} ({{ $country->telcode }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- Initialize with full phone, user might need to adjust --}}
                                                <input type="text" class="form-control" name="phone"
                                                    id="phone_number_input" placeholder="+1 123 456 7890"
                                                    value="{{ old('phone', $customer->phone) }}">
                                            </div>
                                            @if ($errors->has('phone'))
                                                <div class="invalid-feedback d-block">
                                                    {{ $errors->first('phone') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox"
                                                    name="email_marketing_consent" id="email_marketing_consent"
                                                    value="1"
                                                    {{ old('email_marketing_consent', $customer->email_marketing_consent) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_marketing_consent">
                                                    Customer agreed to receive marketing emails.
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="sms_marketing_consent"
                                                    id="sms_marketing_consent" value="1"
                                                    {{ old('sms_marketing_consent', $customer->sms_marketing_consent) ? 'checked' : '' }}>
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
                                            @if ($defaultAddress)
                                                <div class="f-w-500">{{ $defaultAddress->first_name }}
                                                    {{ $defaultAddress->last_name }}</div>
                                                @if ($defaultAddress->company)
                                                    <div>{{ $defaultAddress->company }}</div>
                                                @endif
                                                <div>{{ $defaultAddress->address1 }}</div>
                                                @if ($defaultAddress->address2)
                                                    <div>{{ $defaultAddress->address2 }}</div>
                                                @endif
                                                <div>{{ $defaultAddress->zip }} {{ $defaultAddress->city }}</div>
                                                <div>
                                                    {{ $defaultAddress->province ? $defaultAddress->province . ', ' : '' }}{{ $defaultAddress->country }}
                                                </div>
                                                <div>+{{ $defaultAddress->tel }} {{ $defaultAddress->phone }}</div>
                                            @endif
                                        </div>
                                        <i class="ph ph-pencil position-absolute"
                                            style="right: 15px; top: 15px; cursor: pointer;" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal"></i>
                                    </div>
                                </div>

                                <div id="add_address_placeholder"
                                    class="border rounded p-3 text-center d-flex justify-content-between align-items-center cursor-pointer"
                                    style="border-style: dashed !important; {!! $defaultAddress ? 'display: none !important;' : '' !!}"
                                    data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <span><i class="ph ph-plus-circle"></i> Add address</span>
                                    <i class="ph ph-caret-right"></i>
                                </div>
                                <small class="text-muted mt-2 d-block">You can add multiple addresses after creating the
                                    customer.</small>

                                <!-- Hidden inputs for address data -->
                                <div id="address_hidden_inputs">
                                    @if ($defaultAddress)
                                        <input type="hidden" name="address[id]" value="{{ $defaultAddress->id }}">
                                        <input type="hidden" name="address[country]"
                                            value="{{ $defaultAddress->country }}">
                                        <input type="hidden" name="address[first_name]"
                                            value="{{ $defaultAddress->first_name }}">
                                        <input type="hidden" name="address[last_name]"
                                            value="{{ $defaultAddress->last_name }}">
                                        <input type="hidden" name="address[company]"
                                            value="{{ $defaultAddress->company }}">
                                        <input type="hidden" name="address[address1]"
                                            value="{{ $defaultAddress->address1 }}">
                                        <input type="hidden" name="address[address2]"
                                            value="{{ $defaultAddress->address2 }}">
                                        <input type="hidden" name="address[city]" value="{{ $defaultAddress->city }}">
                                        <input type="hidden" name="address[province]"
                                            value="{{ $defaultAddress->province }}">
                                        <input type="hidden" name="address[zip]" value="{{ $defaultAddress->zip }}">
                                        <input type="hidden" name="address[phone]"
                                            value="{{ $defaultAddress->phone }}">
                                        <input type="hidden" name="address[tel]" value="{{ $defaultAddress->tel }}">
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
                                    <option value="collect"
                                        {{ old('tax_setting', $customer->tax_setting) == 'collect' ? 'selected' : '' }}>
                                        Collect tax</option>
                                    <option value="exempt"
                                        {{ old('tax_setting', $customer->tax_setting) == 'exempt' ? 'selected' : '' }}>Tax
                                        exempt</option>
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
                                <x-forms.textarea name="notes"
                                    placeholder="Notes are private and won't be shared with the customer."
                                    :value="old('notes', $customer->notes)" />
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

            // Function to fetch zones
            function fetchZones(countryId, selectedProvince = null) {
                if (!countryId) {
                    $('#modal_province').html('<option value="" disabled selected>Select a state</option>');
                    $('#state_container').hide();
                    return;
                }

                // Get data from selected option
                const selectedOption = $(`#modal_country option[value="${countryId}"]`);
                const postalCodeName = selectedOption.data('postalcode');
                const zoneName = selectedOption.data('zone');
                const flagUrl = selectedOption.data('flag');
                const telCode = selectedOption.data('telcode'); // Added telCode extraction

                // PIPELINE: Update Labels/Visibility based on Country Data

                // 0. Flag & Tel Code
                if (flagUrl) {
                    $('#modal_phone_flag').attr('src', flagUrl);
                }
                if (telCode) {
                    $('#modal_phone_code').val(telCode);
                }

                // 1. Postal Code
                if (postalCodeName) {
                    $('#postal_code_label').text(postalCodeName);
                    $('#postal_code_container').show();
                } else {
                    $('#postal_code_container').hide();
                    $('#modal_zip').val(''); // Clear value
                }

                // 2. Zone/State Label
                if (zoneName) {
                    $('#state_label').text(zoneName);
                } else {
                    $('#state_label').text('State'); // Default
                }

                $('#modal_province').html('<option value="" disabled selected>Loading...</option>');
                $.ajax({
                    url: "{{ route('admin.customers.get-zones') }}",
                    type: "GET",
                    data: {
                        country_id: countryId
                    },
                    success: function(data) {
                        let options = '<option value="" disabled selected>Select a state</option>';
                        if (data.length > 0) {
                            data.forEach(function(zone) {
                                const isSelected = selectedProvince && zone.name ===
                                    selectedProvince ? 'selected' : '';
                                options +=
                                    `<option value="${zone.name}" ${isSelected}>${zone.name}</option>`;
                            });
                            $('#state_container').show();
                        } else {
                            // If no zones, hide the state container
                            $('#state_container').hide();
                            $('#modal_province').val(''); // Clear value
                        }
                        $('#modal_province').html(options);
                    },
                    error: function() {
                        $('#modal_province').html(
                            '<option value="" disabled selected>Select a state</option>');
                        $('#state_container').hide();
                    }
                });
            }

            $('#modal_country').change(function() {
                fetchZones($(this).val());
                const flag = $(this).find('option:selected').data('flag');
                if (flag) $('#modal_phone_flag').attr('src', flag);
            });

            // Handle Pencil Icon Click (Edit)
            $(document).on('click', '.ph-pencil[data-bs-target="#addAddressModal"]', function() {
                // Pre-fill modal with existing hidden input values
                const currentCountryName = $('input[name="address[country]"]').val();
                const currentProvince = $('input[name="address[province]"]').val();

                // Set Country
                if (currentCountryName) {
                    const option = $(`#modal_country option[data-name="${currentCountryName}"]`);
                    if (option.length) {
                        $('#modal_country').val(option.val());
                        fetchZones(option.val(), currentProvince);
                    } else {
                        $('#modal_country').val('');
                        $('#modal_province').html(
                            '<option value="" disabled selected>Select a state</option>');
                        $('#state_container').hide();
                    }
                } else {
                    $('#modal_country').val('');
                    $('#modal_province').html('<option value="" disabled selected>Select a state</option>');
                    $('#state_container').hide();
                }

                $('#modal_first_name').val($('input[name="address[first_name]"]').val());
                $('#modal_last_name').val($('input[name="address[last_name]"]').val());
                $('#modal_company').val($('input[name="address[company]"]').val());
                $('#modal_address1').val($('input[name="address[address1]"]').val());
                $('#modal_address2').val($('input[name="address[address2]"]').val());
                $('#modal_city').val($('input[name="address[city]"]').val());
                // province handled by fetchZones
                $('#modal_zip').val($('input[name="address[zip]"]').val());
                $('#modal_phone').val($('input[name="address[phone]"]').val());
                $('#modal_phone_code').val($('input[name="address[tel]"]').val());

            });

            $('#saveAddressBtn').on('click', function() {
                // Check visibility before validating
                const isPostalCodeVisible = $('#postal_code_container').is(':visible');
                const isStateVisible = $('#state_container').is(':visible');

                const addressData = {
                    id: $('input[name="address[id]"]').val(), // Keep ID if editing existing
                    country: $('#modal_country option:selected').data('name'),
                    first_name: $('#modal_first_name').val(),
                    last_name: $('#modal_last_name').val(),
                    company: $('#modal_company').val(),
                    address1: $('#modal_address1').val(),
                    address2: $('#modal_address2').val(),
                    city: $('#modal_city').val(),
                    province: isStateVisible ? $('#modal_province').val() : '',
                    zip: isPostalCodeVisible ? $('#modal_zip').val() : '',
                    phone: $('#modal_phone').val(),
                    tel: $('#modal_phone_code').val()
                };

                // Basic validation
                if (!addressData.address1 || !addressData.city || !addressData.country) {
                    if (window.toast) {
                        window.toast.error(
                            'Please fill in required address fields (Address, City, Country)');
                    } else {
                        alert('Please fill in required address fields (Address, City, Country)');
                    }
                    return;
                }

                // Update Summary UI
                let summaryHtml = `
                <div class="f-w-500">${addressData.first_name} ${addressData.last_name}</div>
                ${addressData.company ? `<div>${addressData.company}</div>` : ''}
                <div>${addressData.address1}</div>
                ${addressData.address2 ? `<div>${addressData.address2}</div>` : ''}
                <div>${addressData.zip ? addressData.zip + ' ' : ''}${addressData.city}</div>
                <div>${addressData.province ? addressData.province + ', ' : ''}${addressData.country}</div>
                <div>${addressData.tel} ${addressData.phone}</div>
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



            // One-time initialization logic
            updateMainFlag();

            function updateMainFlag() {
                const flag = $('#phone_code_select option:selected').data('flag');
                if (flag) {
                    $('#main_phone_flag').attr('src', flag);
                }
            }

            $('#phone_code_select').change(function() {
                updateMainFlag();
            });


        });
    </script>
@endpush
