@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Media Library</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li>
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span><i class="ph-duotone ph-house f-s-16"></i> Home</span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Media Library</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Media Library Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5><i class="ph-duotone ph-images"></i> All Media Files</h5>
                    <button type="button" class="btn btn-primary" id="uploadMediaBtn">
                        <i class="ph ph-upload-simple"></i> Upload Media
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="mediaTable">
                    <thead>
                        <tr>
                            <th width="80">Preview</th>
                            <th>File Name</th>
                            @if(auth()->guard('admin')->check())
                                <th>Owner</th>
                            @endif
                            <th>Type</th>
                            <th>Size</th>
                            <th>Uploaded</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Media Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="dropzone" id="mediaDropzone">
                        <div class="dz-message">
                            <i class="ph-duotone ph-upload-simple" style="font-size: 48px;"></i>
                            <h5>Drop files here or click to upload</h5>
                            <p class="text-muted">Maximum file size: 10MB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles:before')
    <!-- Data Table css-->
    <link href="{{ asset('admins/vendor/datatable/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@push('styles:after')
<style>
    .file-icon-preview {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        border-radius: 4px;
        font-size: 24px;
        color: #6b7280;
    }

    .dropzone {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .dropzone:hover {
        border-color: #667eea;
        background: #f9fafb;
    }

    .dropzone.dz-drag-hover {
        border-color: #667eea;
        background: #f0f4ff;
    }
</style>
@endpush

@push('scripts:before')
    <!-- Data Table js-->
    <script src="{{ asset('admins/vendor/datatable/jquery.dataTables.min.js') }}"></script>
@endpush
@push('scripts:after')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#mediaTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.media.index') }}',
            data: function(d) {
                d.type = $('#typeFilter').val();
                d.search = $('#searchInput').val();
            }
        },
        columns: [
            { data: 'preview', name: 'preview', orderable: false, searchable: false },
            { data: 'file_name', name: 'file_name' },
            @if(auth()->guard('admin')->check())
                { data: 'vendor', name: 'vendor' },
            @endif
            { data: 'mime_type', name: 'mime_type' },
            { data: 'size', name: 'size' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[@if(auth()->guard('admin')->check()) 5 @else 4 @endif, 'desc']]
    });

    // Upload button
    $('#uploadMediaBtn').click(function() {
        $('#uploadModal').modal('show');
    });

    // Dropzone for file upload
    let uploadedFiles = [];
    const dropzone = document.getElementById('mediaDropzone');
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.multiple = true;
    fileInput.style.display = 'none';
    document.body.appendChild(fileInput);

    dropzone.addEventListener('click', () => fileInput.click());

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dz-drag-hover');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dz-drag-hover');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dz-drag-hover');
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    async function handleFiles(files) {
        const formData = new FormData();
        Array.from(files).forEach(file => {
            formData.append('files[]', file);
        });

        try {
            const response = await fetch('{{ route('admin.media.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                window.toast?.success(data.message);
                $('#uploadModal').modal('hide');
                table.ajax.reload();
            } else {
                window.toast?.error('Upload failed');
            }
        } catch (error) {
            console.error('Upload error:', error);
            window.toast?.error('Upload failed');
        }

        fileInput.value = '';
    }

    // Delete media
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to delete this media file?')) {
            return;
        }

        const form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    window.toast?.success(response.message);
                    table.ajax.reload();
                }
            },
            error: function() {
                window.toast?.error('Failed to delete media');
            }
        });
    });
});
</script>
@endpush
