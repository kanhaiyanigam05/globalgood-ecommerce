
<div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title font-weight-bold" id="mediaModalLabel">Media Library</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <ul class="nav nav-tabs px-3" id="mediaTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="gallery-tab" data-bs-toggle="tab" href="#gallery" role="tab"
                            aria-selected="true">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="upload-tab" data-bs-toggle="tab" href="#upload" role="tab"
                            aria-selected="false">Upload</a>
                    </li>
                </ul>
                <div class="tab-content" id="mediaTabContent">
                    {{-- Gallery Tab --}}
                    <div class="tab-pane fade show active" id="gallery" role="tabpanel">
                        <div class="row no-gutters">
                            <div class="col-md-9 border-right">
                                <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light">
                                    <div class="input-group" style="max-width: 300px;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0">
                                                <i class="fa fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="mediaSearch" class="form-control border-left-0"
                                            placeholder="Search media...">
                                    </div>
                                    <div class="text-muted small" id="mediaCount">Showing 0 files</div>
                                </div>
                                <div class="media-gallery-container p-3" style="min-height: 500px; max-height: 70vh; overflow-y: auto;">
                                    <div class="row g-2" id="mediaGalleryItems">
                                        {{-- Loaded via JS --}}
                                    </div>
                                    <div id="mediaLoading" class="text-center py-5 d-none">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    <div id="mediaLoadMore" class="text-center py-3 d-none">
                                        <button class="btn btn-outline-primary btn-sm px-4">Load More</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 bg-light" id="mediaSidebar">
                                <div class="p-3 h-100 d-flex flex-column">
                                    <div id="sidebarEmptyState" class="text-center py-5 text-muted">
                                        <i class="fa-regular fa-image fa-3x mb-3 opacity-50"></i>
                                        <p>Select an image to view details</p>
                                    </div>
                                    <div id="sidebarDetails" class="d-none flex-grow-1">
                                        <h6 class="font-weight-bold mb-3">Attachment Details</h6>
                                        <div class="text-center mb-3 bg-white p-2 border rounded">
                                            <img id="sidebarPreview" src="" class="img-fluid rounded shadow-sm" style="max-height: 150px; object-fit: contain;">
                                        </div>
                                        <div class="small text-muted mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span>File size:</span>
                                                <span id="sidebarFileSize" class="font-weight-bold"></span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>File type:</span>
                                                <span id="sidebarFileType" class="font-weight-bold"></span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group mb-3">
                                            <label class="small font-weight-bold">Title</label>
                                            <input type="text" id="sidebarTitle" class="form-control form-control-sm">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="small font-weight-bold">Alt Text</label>
                                            <textarea id="sidebarAlt" class="form-control form-control-sm" rows="2"></textarea>
                                        </div>
                                        <button type="button" id="sidebarUpdateBtn" class="btn btn-primary btn-sm btn-block">Update Details</button>
                                        
                                        <div class="mt-auto pt-3">
                                            <button type="button" id="sidebarDeleteBtn" class="btn btn-link text-danger btn-sm p-0">Delete Permanently</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Upload Tab --}}
                    <div class="tab-pane fade" id="upload" role="tabpanel">
                        <div class="p-5 text-center">
                            <div class="upload-dropzone border-primary py-5 rounded bg-light" id="mediaUploadZone"
                                style="border: 2px dashed #007bff; cursor: pointer;">
                                <i class="fa-solid fa-cloud-arrow-up fa-3x text-primary mb-3"></i>
                                <h4>Drop files anywhere to upload</h4>
                                <p class="text-muted">or click to browse from your computer</p>
                                <input type="file" id="mediaFileInput" multiple class="d-none">
                            </div>
                            <div id="uploadProgressContainer" class="mt-4 mx-auto" style="max-width: 500px;">
                                {{-- Upload items and progress bars --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between bg-light">
                <div id="selectedCount" class="text-muted small">0 items selected</div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" id="mediaSelectBtn" disabled>Insert
                        Media</button>
                </div>
            </div>
        </div>
    </div>
</div>

@pushOnce('styles:after', 'media-library-styles')
    <style>
        :root {
            --gallery-gap: 1rem;
            --gallery-item-radius: 12px;
            --transition-speed: 0.3s;
        }

        body {
            background-color: #f8f9fa;
            padding: 2rem 0;
        }

        #mediaGalleryItems {
            gap: var(--gallery-gap) !important;
        }

        .media-item {
            cursor: pointer;
        }

        .media-item-inner {
            position: relative;
            border-radius: var(--gallery-item-radius);
            overflow: hidden;
            border: 2px solid transparent;
            transition: all var(--transition-speed) ease;
            background: white;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .media-item:hover .media-item-inner {
            border-color: #dee2e6;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        }

        .media-item.selected .media-item-inner {
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2) !important;
        }

        .media-item-inner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 50%);
            opacity: 0;
            transition: opacity var(--transition-speed) ease;
            z-index: 1;
            pointer-events: none;
        }

        .media-item:hover .media-item-inner::before {
            opacity: 1;
        }

        .media-item-inner img {
            flex: 1;
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .media-item-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0.75rem;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
            z-index: 2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .media-item.selected .media-item-inner::after {
            content: '\ea30';
            font-family: 'Phosphor';
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 28px;
            height: 28px;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            z-index: 3;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.4);
        }

        .media-size-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            z-index: 2;
            opacity: 0;
        }

        /* Grid responsiveness */
        @media (max-width: 576px) {
            .media-item {
                flex: 0 0 calc(50% - var(--gallery-gap) / 2);
                max-width: calc(50% - var(--gallery-gap) / 2);
            }
        }

        @media (min-width: 577px) and (max-width: 768px) {
            .media-item {
                flex: 0 0 calc(33.333% - var(--gallery-gap) * 2 / 3);
                max-width: calc(33.333% - var(--gallery-gap) * 2 / 3);
            }
        }

        @media (min-width: 769px) and (max-width: 992px) {
            .media-item {
                flex: 0 0 calc(25% - var(--gallery-gap) * 3 / 4);
                max-width: calc(25% - var(--gallery-gap) * 3 / 4);
            }
        }

        @media (min-width: 993px) {
            .media-item {
                flex: 0 0 calc(16.666% - var(--gallery-gap) * 5 / 6);
                max-width: calc(16.666% - var(--gallery-gap) * 5 / 6);
            }
        }

        .upload-dropzone:hover {
            background-color: #e9ecef !important;
        }
    </style>
@endPushOnce

@pushOnce('scripts:after', 'media-library-scripts')
    <script>
        const MediaLibrary = {
            currentPage: 1,
            lastPage: 1,
            isLoading: false,
            selectedItems: [],
            multiple: false,
            callback: null,
            baseUrl: "{{ Auth::guard('admin')->check() ? route('admin.media.index') : (Auth::guard('vendor')->check() ? route('vendor.media.index') : '') }}",

            init() {
                this.galleryItems = document.getElementById('mediaGalleryItems');
                this.loading = document.getElementById('mediaLoading');
                this.loadMore = document.getElementById('mediaLoadMore');
                this.search = document.getElementById('mediaSearch');
                this.countDisplay = document.getElementById('mediaCount');
                this.selectBtn = document.getElementById('mediaSelectBtn');
                this.selectedCountDisplay = document.getElementById('selectedCount');
                this.uploadZone = document.getElementById('mediaUploadZone');
                this.fileInput = document.getElementById('mediaFileInput');

                // Sidebar elements
                this.sidebarEmpty = document.getElementById('sidebarEmptyState');
                this.sidebarDetails = document.getElementById('sidebarDetails');
                this.sidebarPreview = document.getElementById('sidebarPreview');
                this.sidebarFileSize = document.getElementById('sidebarFileSize');
                this.sidebarFileType = document.getElementById('sidebarFileType');
                this.sidebarTitle = document.getElementById('sidebarTitle');
                this.sidebarAlt = document.getElementById('sidebarAlt');
                this.sidebarUpdateBtn = document.getElementById('sidebarUpdateBtn');
                this.sidebarDeleteBtn = document.getElementById('sidebarDeleteBtn');

                this.activeItem = null;
                this.bindEvents();
            },

            bindEvents() {
                this.search.addEventListener('input', this.debounce(() => {
                    this.currentPage = 1;
                    this.fetchMedia();
                }, 500));

                this.loadMore.querySelector('button').onclick = () => {
                    this.currentPage++;
                    this.fetchMedia(true);
                };

                this.galleryItems.addEventListener('click', (e) => {
                    const item = e.target.closest('.media-item');
                    if (!item) return;
                    this.handleItemClick(item);
                });

                this.selectBtn.onclick = () => {
                    if (this.callback) {
                        this.callback(this.selectedItems);
                    }
                    bootstrap.Modal.getInstance(document.getElementById('mediaModal')).hide();
                };

                this.uploadZone.onclick = () => this.fileInput.click();
                this.fileInput.onchange = (e) => this.handleUpload(e.target.files);

                this.uploadZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    this.uploadZone.classList.add('bg-white');
                });

                this.uploadZone.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    this.uploadZone.classList.remove('bg-white');
                });

                this.uploadZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    this.uploadZone.classList.remove('bg-white');
                    this.handleUpload(e.dataTransfer.files);
                });

                this.sidebarUpdateBtn.onclick = () => this.updateDetails();
                this.sidebarDeleteBtn.onclick = () => this.deleteMedia();

                document.getElementById('mediaModal').addEventListener('show.bs.modal', (e) => {
                    const button = $(e.relatedTarget);
                    // Only set multiple from button if not already explicitly set via open()
                    if (button.length && typeof button.data('multiple') !== 'undefined') {
                        this.multiple = button.data('multiple');
                    }
                    this.updateUI();
                    this.resetSidebar();
                    // We don't clear selectedItems here to allow pre-selection in future
                    // fetchMedia will be called by open() if needed, or by this event
                });
            },

            fetchMedia(append = false) {
                if (this.isLoading) return;
                this.isLoading = true;
                this.loading.classList.remove('d-none');
                if (!append) {
                    this.galleryItems.innerHTML = '';
                    this.loadMore.classList.add('d-none');
                }

                const url = new URL(this.baseUrl, window.location.origin);
                url.searchParams.append('page', this.currentPage);
                if (this.search.value) url.searchParams.append('search', this.search.value);

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        this.lastPage = data.last_page;
                        this.renderItems(data.data, append);
                        this.countDisplay.textContent = `Showing ${data.to || 0} of ${data.total} files`;
                        
                        if (data.current_page < data.last_page) {
                            this.loadMore.classList.remove('d-none');
                        } else {
                            this.loadMore.classList.add('d-none');
                        }
                    })
                    .finally(() => {
                        this.isLoading = false;
                        this.loading.classList.add('d-none');
                    });
            },

            renderItems(items, append) {
                if (!append) this.galleryItems.innerHTML = '';
                
                items.forEach(item => {
                    this.appendItem(item);
                });
            },

            appendItem(item, prepend = false) {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-3 col-lg-2 media-item';
                col.dataset.id = item.id;
                col.dataset.url = item.url;
                col.dataset.thumb = item.thumb || item.url;
                col.dataset.name = item.name;
                col.dataset.size = item.human_readable_size || item.size || '';
                col.dataset.mime = item.mime_type || '';
                col.dataset.alt = (item.custom_properties && item.custom_properties.alt) || '';

                col.innerHTML = `
                    <div class="media-item-inner">
                        <span class="media-size-badge">${item.human_readable_size || item.size || ''}</span>
                        <img src="${item.thumb || item.url}" loading="lazy" alt="${item.name}">
                        <div class="media-item-info">${item.name}</div>
                    </div>
                `;
                
                if (prepend) {
                    this.galleryItems.prepend(col);
                } else {
                    this.galleryItems.appendChild(col);
                }
            },

            handleItemClick(element) {
                // Clicking an item ALWAYS makes it the active item for the sidebar
                this.setActive(element);

                // For selection (checkbox style)
                this.toggleSelect(element);
            },

            setActive(element) {
                this.activeItem = element;
                this.showDetails(element);
            },

            showDetails(element) {
                this.sidebarEmpty.classList.add('d-none');
                this.sidebarDetails.classList.remove('d-none');

                this.sidebarPreview.src = element.dataset.thumb;
                this.sidebarFileSize.textContent = element.dataset.size;
                this.sidebarFileType.textContent = element.dataset.mime;
                this.sidebarTitle.value = element.dataset.name;
                this.sidebarAlt.value = element.dataset.alt;
            },

            resetSidebar() {
                this.sidebarEmpty.classList.remove('d-none');
                this.sidebarDetails.classList.add('d-none');
                this.activeItem = null;
            },

            toggleSelect(element) {
                const id = element.dataset.id;
                const index = this.selectedItems.findIndex(i => i.id == id);

                if (index > -1) {
                    this.selectedItems.splice(index, 1);
                    element.classList.remove('selected');
                } else {
                    if (!this.multiple) {
                        this.selectedItems = [];
                        this.galleryItems.querySelectorAll('.media-item.selected').forEach(el => el.classList.remove('selected'));
                    }
                    this.selectedItems.push({
                        id: id,
                        url: element.dataset.url,
                        thumb: element.dataset.thumb,
                        name: element.dataset.name
                    });
                    element.classList.add('selected');
                }

                this.updateUI();
            },

            updateUI() {
                this.selectedCountDisplay.textContent = `${this.selectedItems.length} items selected`;
                this.selectBtn.disabled = this.selectedItems.length === 0;
            },

            updateDetails() {
                if (!this.activeItem) return;

                const id = this.activeItem.dataset.id;
                const name = this.sidebarTitle.value;
                const alt = this.sidebarAlt.value;

                this.sidebarUpdateBtn.disabled = true;
                this.sidebarUpdateBtn.textContent = 'Updating...';

                const url = this.baseUrl.endsWith('/') ? this.baseUrl.slice(0, -1) : this.baseUrl;

                fetch(`${url}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ name, alt_text: alt })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.activeItem.dataset.name = data.media.name;
                        this.activeItem.dataset.alt = data.media.alt || '';
                        this.activeItem.querySelector('.media-item-info').textContent = data.media.name;
                        window.toast.success('Media details updated');
                    }
                })
                .finally(() => {
                    this.sidebarUpdateBtn.disabled = false;
                    this.sidebarUpdateBtn.textContent = 'Update Details';
                });
            },

            deleteMedia() {
                if (!this.activeItem || !confirm('Are you sure you want to permanently delete this file?')) return;

                const id = this.activeItem.dataset.id;
                const url = this.baseUrl.endsWith('/') ? this.baseUrl.slice(0, -1) : this.baseUrl;

                fetch(`${url}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const nextItem = this.activeItem.nextElementSibling || this.activeItem.previousElementSibling;
                        this.activeItem.remove();
                        if (nextItem) {
                            this.setActive(nextItem);
                        } else {
                            this.resetSidebar();
                        }
                        window.toast.success('Media deleted successfully');
                    }
                });
            },

            handleUpload(files) {
                if (!files.length) return;

                const progressContainer = document.getElementById('uploadProgressContainer');
                progressContainer.classList.remove('d-none');

                Array.from(files).forEach(file => {
                    const row = document.createElement('div');
                    row.className = 'mb-2 p-2 border rounded bg-white text-left shadow-sm';
                    row.innerHTML = `
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small font-weight-bold text-truncate" style="max-width: 250px;">${file.name}</span>
                            <span class="small progress-percent">0%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    `;
                    progressContainer.prepend(row);

                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', "{{ csrf_token() }}");
                    if (this.model_type) formData.append('model_type', this.model_type);
                    if (this.model_id) formData.append('model_id', this.model_id);
                    if (this.collection) formData.append('collection', this.collection);

                    const storeUrl = this.baseUrl.endsWith('/') ? this.baseUrl.slice(0, -1) : this.baseUrl;
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', storeUrl, true);
                    
                    xhr.upload.onprogress = (e) => {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            row.querySelector('.progress-bar').style.width = percent + '%';
                            row.querySelector('.progress-percent').textContent = percent + '%';
                        }
                    };

                    xhr.onload = () => {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            row.classList.add('border-success');
                            row.querySelector('.progress-bar').classList.replace('progress-bar-animated', 'bg-success');
                            
                            // Auto-append to gallery
                            this.appendItem(response, true);
                            
                            // Auto-select for editing if nothing active
                            if (!this.activeItem) {
                                this.setActive(this.galleryItems.firstElementChild);
                            }
                            
                            setTimeout(() => {
                                row.classList.add('animate__animated', 'animate__fadeOut');
                                setTimeout(() => row.remove(), 500);
                            }, 2000);
                        } else {
                            row.classList.add('border-danger');
                            row.querySelector('.progress-bar').classList.add('bg-danger');
                            row.querySelector('.progress-percent').textContent = 'Error';
                        }
                    };

                    xhr.send(formData);
                });
            },

            debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            },

            open(options = {}) {
                this.multiple = options.multiple || false;
                this.callback = options.callback || null;
                this.model_type = options.model_type || '';
                this.model_id = options.model_id || '';
                this.collection = options.collection || '';
                
                // Clear state for new open
                this.selectedItems = [];
                this.activeItem = null;
                this.currentPage = 1;
                this.galleryItems.innerHTML = ''; 
                
                const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
                modal.show();
                
                // Fetch after show
                this.fetchMedia();
            }
        };

        window.MediaLibrary = MediaLibrary;
        document.addEventListener('DOMContentLoaded', () => MediaLibrary.init());
    </script>
@endPushOnce
