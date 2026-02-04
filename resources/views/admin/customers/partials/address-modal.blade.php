<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Add default address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="app-form">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Select from existing addresses</label>
                            <select id="modal_existing_address" class="form-select">
                                <option value="" selected>New Address</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Country/region</label>
                            <select id="modal_country" class="form-select">
                                <option value="" disabled selected>Select a country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" data-name="{{ $country->name }}"
                                        data-postalcode="{{ $country->postalcode }}" data-zone="{{ $country->zone }}"
                                        data-flag="{{ $country->flag_url }}" data-telcode="{{ $country->telcode }}">
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First name</label>
                            <input type="text" id="modal_first_name" class="form-control" placeholder="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last name</label>
                            <input type="text" id="modal_last_name" class="form-control" placeholder="">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Company</label>
                            <input type="text" id="modal_company" class="form-control" placeholder="">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>
                            <div class="position-relative">
                                <input type="text" id="modal_address1" class="form-control" placeholder="">
                                <i class="ph ph-magnifying-glass position-absolute"
                                    style="right: 10px; top: 12px; color: #ccc;"></i>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Apartment, suite, etc</label>
                            <input type="text" id="modal_address2" class="form-control" placeholder="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" id="modal_city" class="form-control" placeholder="">
                        </div>
                        <div class="col-md-4 mb-3" id="state_container" style="display: none;">
                            <label class="form-label" id="state_label">State</label>
                            <select id="modal_province" class="form-select">
                                <option value="" disabled selected>Select a state</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="postal_code_container" style="display: none;">
                            <label class="form-label" id="postal_code_label">PIN code</label>
                            <input type="text" id="modal_zip" class="form-control" placeholder="">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Phone</label>
                            <div class="input-group">
                                <div class="position-relative d-flex align-items-center" style="width: 50px;">
                                    <div
                                        class="d-flex align-items-center justify-content-center w-100 h-100 bg-white border rounded-start px-2">
                                        <img src="{{ asset('flags/untitle.svg') }}" id="modal_phone_flag" width="24"
                                            alt="Flag" style="object-fit: contain;">
                                    </div>
                                    <input type="hidden" id="modal_phone_code" name="address[tel]">
                                </div>
                                <input type="text" id="modal_phone" name="address[phone]" class="form-control"
                                    placeholder="123 456 7890">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" id="saveAddressBtn">Save</button>
            </div>
        </div>
    </div>
</div>
