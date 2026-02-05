@props([
    'id' => 'mediaPicker',
    'multiple' => false,
    'accept' => 'all', // all, images, videos, documents
])

<div class="modal fade media-picker-modal" id="{{ $id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">
                    <i class="ph-duotone ph-images"></i> Media Library
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Media Grid Section -->
                    <div class="col-lg-9 border-end" id="{{ $id }}GridSection">
                        <!-- Toolbar -->
                        <div class="p-3 border-bottom bg-light">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ph ph-magnifying-glass"></i></span>
                                        <input type="text" class="form-control media-search" placeholder="Search media files...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select media-type-filter">
                                        <option value="all">All Files</option>
                                        <option value="images">Images</option>
                                        <option value="videos">Videos</option>
                                        <option value="documents">Documents</option>
                                    </select>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button type="button" class="btn btn-outline-primary upload-toggle-btn">
                                        <i class="ph ph-upload-simple"></i> Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Area (Collapsible) -->
                        <div class="upload-area-container border-bottom bg-white" style="display: none;">
                            <div class="p-4 text-center">
                                <div class="upload-dropzone p-5 border-dashed rounded-3">
                                    <i class="ph-duotone ph-cloud-arrow-up display-4 text-primary mb-2"></i>
                                    <h5>Click to upload or drag and drop</h5>
                                    <p class="text-muted small">PNG, JPG, GIF up to 10MB</p>
                                    <button type="button" class="btn btn-primary mt-2 browse-btn">Browse Files</button>
                                    <input type="file" class="media-upload-input d-none" multiple>
                                </div>
                                <div class="upload-progress-container mt-3" style="display: none;">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <p class="small text-muted mt-2 uploading-status text-start">Uploading...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Media Grid -->
                        <div class="p-3" style="min-height: 400px; max-height: 500px; overflow-y: auto;" id="{{ $id }}GridContainer">
                            <div class="row g-3 media-grid">
                                <div class="col-12 text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="p-3 border-top bg-light media-pagination"></div>
                    </div>

                    <!-- Sidebar for File Details -->
                    <div class="col-lg-3 bg-light media-sidebar" style="display: none;">
                        <div class="p-3">
                            <h6 class="mb-3">File Details</h6>
                            
                            <!-- Preview -->
                            <div class="mb-3 text-center">
                                <img src="" class="img-fluid rounded sidebar-preview" style="max-height: 200px;">
                            </div>

                            <!-- File Info -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">File Name</label>
                                <p class="text-muted small mb-0 sidebar-file-name"></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">File Size</label>
                                <p class="text-muted small mb-0 sidebar-file-size"></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Uploaded</label>
                                <p class="text-muted small mb-0 sidebar-created-at"></p>
                            </div>

                            <!-- Editable Fields -->
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control form-control-sm sidebar-name">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alt Text</label>
                                <input type="text" class="form-control form-control-sm sidebar-alt-text">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Caption</label>
                                <textarea class="form-control form-control-sm sidebar-caption" rows="3"></textarea>
                            </div>

                            <button type="button" class="btn btn-sm btn-primary w-100 save-media-details">
                                <i class="ph ph-floppy-disk"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top">
                <div class="me-auto">
                    <span class="selected-count">0 selected</span>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary select-media-btn">Select</button>
            </div>
        </div>
    </div>
</div>

@pushOnce('styles:after')
<style>
    .media-item {
        position: relative;
        border: 2px solid transparent;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s;
    }

    .media-item:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .media-item.selected {
        border-color: #667eea;
        background: #f0f4ff;
    }

    .media-item-checkbox {
        position: absolute;
        top: 8px;
        left: 8px;
        z-index: 10;
        cursor: pointer;
    }

    .media-item-preview {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .media-item-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-item-name {
        padding: 8px;
        font-size: 12px;
        text-align: center;
        background: white;
        border-top: 1px solid #e5e7eb;
    }

    .border-dashed {
        border: 2px dashed #dee2e6 !important;
        transition: all 0.3s;
        cursor: pointer;
    }

    .border-dashed:hover {
        border-color: #667eea !important;
        background-color: #f8faff;
    }

    .upload-area-container {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endPushOnce

@pushOnce('scripts:after')
<script>
    class MediaPicker {
        constructor(modalId, options = {}) {
            this.modalElement = document.getElementById(modalId);
            if (!this.modalElement) return;

            this.modal = new bootstrap.Modal(this.modalElement);
            this.id = modalId;
            this.multiple = options.multiple || false;
            this.accept = options.accept || 'all';
            this.directory = options.directory || 'media';
            this.selectedMedia = [];
            this.currentPage = 1;
            this.selectedMediaForEdit = null;
            this.onSelect = options.onSelect || null;

            this.init();
        }

        init() {
            this.modalElement.addEventListener('shown.bs.modal', () => this.loadGrid(1));
            this.modalElement.addEventListener('hidden.bs.modal', () => this.reset());

            // Toolbar elements
            this.searchField = this.modalElement.querySelector('.media-search');
            this.typeFilter = this.modalElement.querySelector('.media-type-filter');
            this.uploadToggleBtn = this.modalElement.querySelector('.upload-toggle-btn');
            
            // Upload Area elements
            this.uploadArea = this.modalElement.querySelector('.upload-area-container');
            this.dropzone = this.modalElement.querySelector('.upload-dropzone');
            this.browseBtn = this.modalElement.querySelector('.browse-btn');
            this.uploadInput = this.modalElement.querySelector('.media-upload-input');
            this.progressContainer = this.modalElement.querySelector('.upload-progress-container');
            this.progressBar = this.modalElement.querySelector('.progress-bar');
            this.uploadingStatus = this.modalElement.querySelector('.uploading-status');
            
            // Containers
            this.grid = this.modalElement.querySelector('.media-grid');
            this.pagination = this.modalElement.querySelector('.media-pagination');
            this.sidebar = this.modalElement.querySelector('.media-sidebar');
            this.selectedCountLabel = this.modalElement.querySelector('.selected-count');
            this.selectBtn = this.modalElement.querySelector('.select-media-btn');
            this.saveDetailsBtn = this.modalElement.querySelector('.save-media-details');

            // Attach listeners
            let searchTimeout;
            this.searchField.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => this.loadGrid(1), 500);
            });

            this.typeFilter.addEventListener('change', () => this.loadGrid(1));
            
            // Upload Toggle
            this.uploadToggleBtn.addEventListener('click', () => this.toggleUploadArea());
            
            // Drag & Drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                this.dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                this.dropzone.addEventListener(eventName, () => this.dropzone.classList.add('bg-light'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                this.dropzone.addEventListener(eventName, () => this.dropzone.classList.remove('bg-light'), false);
            });

            this.dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                this.handleFiles(files);
            });

            this.dropzone.addEventListener('click', (e) => {
                if (!e.target.closest('.browse-btn')) {
                    this.uploadInput.click();
                }
            });

            this.browseBtn.addEventListener('click', () => this.uploadInput.click());
            this.uploadInput.addEventListener('change', (e) => this.handleFiles(e.target.files));

            this.saveDetailsBtn.addEventListener('click', () => this.saveDetails());
            this.selectBtn.addEventListener('click', () => this.handleSelect());
        }

        toggleUploadArea() {
            const isVisible = this.uploadArea.style.display !== 'none';
            this.uploadArea.style.display = isVisible ? 'none' : 'block';
            this.uploadToggleBtn.classList.toggle('active', !isVisible);
        }

        handleFiles(files) {
            if (files.length === 0) return;
            this.handleUpload(Array.from(files));
        }

        async handleUpload(files) {
            this.progressContainer.style.display = 'block';
            this.progressBar.style.width = '0%';
            this.uploadingStatus.textContent = `Uploading ${files.length} file(s)...`;

            const formData = new FormData();
            files.forEach(f => formData.append('files[]', f));
            formData.append('directory', this.directory);

            try {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.media.store') }}', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        this.progressBar.style.width = percentComplete + '%';
                        this.uploadingStatus.textContent = `Uploading... ${Math.round(percentComplete)}%`;
                    }
                };

                xhr.onload = () => {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        window.toast?.success('Files uploaded successfully');
                        this.loadGrid(1);
                        setTimeout(() => {
                            this.progressContainer.style.display = 'none';
                            this.uploadArea.style.display = 'none';
                            this.uploadToggleBtn.classList.remove('active');
                        }, 1000);
                    }
                };

                xhr.onerror = () => {
                    window.toast?.error('Upload failed');
                    this.progressContainer.style.display = 'none';
                };

                xhr.send(formData);
            } catch (err) {
                console.error('Upload error:', err);
                window.toast?.error('Upload failed');
                this.progressContainer.style.display = 'none';
            }
            this.uploadInput.value = '';
        }

        async loadGrid(page = 1) {
            this.currentPage = page;
            this.grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>';

            try {
                const params = new URLSearchParams({
                    page,
                    per_page: 24,
                    search: this.searchField.value,
                    type: this.accept !== 'all' ? this.accept : this.typeFilter.value
                });

                const response = await fetch(`{{ route('admin.media.grid') }}?${params}`);
                const data = await response.json();

                if (data.success) {
                    this.renderGrid(data.data);
                    this.renderPagination(data.pagination);
                }
            } catch (error) {
                console.error('Error loading media:', error);
                this.grid.innerHTML = '<div class="col-12 text-center py-5 text-danger">Failed to load media</div>';
            }
        }

        renderGrid(items) {
            if (items.length === 0) {
                this.grid.innerHTML = '<div class="col-12 text-center py-5 text-muted">No media files found</div>';
                return;
            }

            this.grid.innerHTML = items.map(item => {
                const isSelected = this.selectedMedia.some(m => m.id === item.id);
                return `
                    <div class="col-md-3 col-sm-4 col-6">
                        <div class="media-item ${isSelected ? 'selected' : ''}" data-id="${item.id}" data-item='${JSON.stringify(item).replace(/'/g, "&apos;")}'>
                            ${this.multiple ? `<input type="checkbox" class="form-check-input media-item-checkbox" ${isSelected ? 'checked' : ''}>` : ''}
                            <div class="media-item-preview">
                                ${item.is_image 
                                    ? `<img src="${item.thumb}" alt="${item.name}">`
                                    : `<i class="ph-duotone ph-file" style="font-size: 48px;"></i>`
                                }
                            </div>
                            <div class="media-item-name text-truncate">${item.file_name}</div>
                        </div>
                    </div>
                `;
            }).join('');

            // Attach handlers to grid items
            this.grid.querySelectorAll('.media-item').forEach(el => {
                el.addEventListener('click', (e) => {
                    if (e.target.classList.contains('media-item-checkbox')) return;
                    this.handleItemClick(el);
                });

                if (this.multiple) {
                    const cb = el.querySelector('.media-item-checkbox');
                    cb.addEventListener('change', (e) => {
                        e.stopPropagation();
                        this.toggleSelection(el, cb.checked);
                    });
                }
            });
        }

        handleItemClick(el) {
            const data = JSON.parse(el.dataset.item);
            
            if (this.multiple) {
                const cb = el.querySelector('.media-item-checkbox');
                cb.checked = !cb.checked;
                this.toggleSelection(el, cb.checked);
            } else {
                this.grid.querySelectorAll('.media-item').forEach(i => i.classList.remove('selected'));
                el.classList.add('selected');
                this.selectedMedia = [data];
                this.showDetails(data);
                this.updateCount();
            }
        }

        toggleSelection(el, isSelected) {
            const data = JSON.parse(el.dataset.item);
            if (isSelected) {
                el.classList.add('selected');
                if (!this.selectedMedia.some(m => m.id === data.id)) {
                    this.selectedMedia.push(data);
                }
            } else {
                el.classList.remove('selected');
                this.selectedMedia = this.selectedMedia.filter(m => m.id !== data.id);
            }
            this.updateCount();
        }

        showDetails(item) {
            this.selectedMediaForEdit = item;
            this.sidebar.style.display = 'block';
            this.sidebar.querySelector('.sidebar-preview').src = item.thumb || item.url;
            this.sidebar.querySelector('.sidebar-file-name').textContent = item.file_name;
            this.sidebar.querySelector('.sidebar-file-size').textContent = item.size;
            this.sidebar.querySelector('.sidebar-created-at').textContent = item.created_at;
            this.sidebar.querySelector('.sidebar-name').value = item.name || '';
            this.sidebar.querySelector('.sidebar-alt-text').value = item.custom_properties?.alt_text || '';
            this.sidebar.querySelector('.sidebar-caption').value = item.custom_properties?.caption || '';
        }

        updateCount() {
            this.selectedCountLabel.textContent = `${this.selectedMedia.length} selected`;
        }

        renderPagination(pag) {
            if (pag.last_page <= 1) {
                this.pagination.innerHTML = '';
                return;
            }

            let html = '<nav><ul class="pagination pagination-sm mb-0 justify-content-center">';
            for (let i = 1; i <= pag.last_page; i++) {
                html += `<li class="page-item ${i === pag.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }
            html += '</ul></nav>';
            this.pagination.innerHTML = html;

            this.pagination.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.loadGrid(parseInt(link.dataset.page));
                });
            });
        }

        async saveDetails() {
            if (!this.selectedMediaForEdit) return;
            const id = this.selectedMediaForEdit.id;
            
            try {
                // We need to handle the encrypted ID for the update route if needed, 
                // but the controller expects an ID. Let's send a standard PUT.
                const response = await fetch(`/admin/media/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: this.sidebar.querySelector('.sidebar-name').value,
                        alt_text: this.sidebar.querySelector('.sidebar-alt-text').value,
                        caption: this.sidebar.querySelector('.sidebar-caption').value
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.toast?.success('Media updated successfully');
                    this.loadGrid(this.currentPage);
                }
            } catch (err) {
                console.error('Update error:', err);
                window.toast?.error('Update failed');
            }
        }

        handleSelect() {
            if (this.selectedMedia.length === 0) {
                window.toast?.warning('Please select at least one file');
                return;
            }

            if (this.onSelect) {
                this.onSelect(this.selectedMedia);
            } else {
                window.dispatchEvent(new CustomEvent(`mediaSelected_${this.id}`, { detail: this.selectedMedia }));
            }

            this.modal.hide();
        }

        reset() {
            this.selectedMedia = [];
            this.selectedMediaForEdit = null;
            this.updateCount();
            this.sidebar.style.display = 'none';
        }

        show() {
            this.modal.show();
        }
    }

    // Initialize globally for convenience
    window.initMediaPicker = (id, options) => {
        return new MediaPicker(id, options);
    };
</script>
@endPushOnce
