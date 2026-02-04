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
                            <label class="form-label">Country/region</label>
                            <select id="modal_country" class="form-select">
                                <option value="India" selected>India</option>
                                <option value="United States">United States</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
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
                                <i class="ph ph-magnifying-glass position-absolute" style="right: 10px; top: 12px; color: #ccc;"></i>
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
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <select id="modal_province" class="form-select">
                                <option value="" disabled selected>Select a state</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="West Bengal">West Bengal</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">PIN code</label>
                            <input type="text" id="modal_zip" class="form-control" placeholder="">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <img src="https://flagcdn.com/w20/in.png" width="20" alt="India">
                                    <i class="ph ph-caret-down ms-1 f-s-10"></i>
                                </span>
                                <input type="text" id="modal_phone" class="form-control" placeholder="">
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
