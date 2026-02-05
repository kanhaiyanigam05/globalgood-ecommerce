<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class MediaController extends Controller
{
    /**
     * Display a listing of the media files
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $media = Media::with('vendor')->latest();

            // Vendor Isolation
            if (auth()->guard('vendor')->check()) {
                $media->where('vendor_id', auth()->guard('vendor')->id());
            } elseif (!auth()->guard('admin')->check()) {
                // If not admin and not vendor (unexpected for admin area), show nothing or fixed logic
                $media->where('id', 0);
            }

            // Filter by type
            if ($request->has('type')) {
                switch ($request->type) {
                    case 'images':
                        $media->images();
                        break;
                    case 'videos':
                        $media->videos();
                        break;
                    case 'documents':
                        $media->documents();
                        break;
                }
            }

            // Search
            if ($request->has('search') && $request->search) {
                $media->search($request->search);
            }

            $dt = DataTables::of($media)
                ->addIndexColumn()
                ->addColumn('preview', function ($row) {
                    if ($row->is_image) {
                        return '<img src="' . $row->getSize(50, 50) . '" class="img-thumbnail" width="50" height="50" style="object-fit: cover;">';
                    }
                    return '<div class="file-icon-preview"><i class="fa-solid fa-file"></i></div>';
                })
                ->editColumn('file_name', fn ($row) => Str::limit($row->file_name, 30))
                ->editColumn('size', fn ($row) => $row->human_readable_size)
                ->editColumn('mime_type', fn ($row) => $row->mime_type ?? 'Unknown')
                ->editColumn('created_at', fn ($row) => $row->created_at->format('M d, Y'));

            if (auth()->guard('admin')->check()) {
                $dt->addColumn('vendor', function ($row) {
                    return $row->vendor ? $row->vendor->legal_name : '<span class="badge bg-light-info text-dark">System</span>';
                });
            }

            $dt->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $deleteUrl = route('admin.media.destroy', $encryptedId);

                    $actions = '<div class="d-flex gap-2">';
                    $actions .= '<button class="btn btn-light-primary icon-btn b-r-4 edit-media-btn" data-id="' . $row->id . '" type="button"><i class="far fa-edit text-primary"></i></button>';
                    $actions .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline delete-form">';
                    $actions .= csrf_field();
                    $actions .= method_field('DELETE');
                    $actions .= '<button class="btn btn-light-danger icon-btn b-r-4" type="submit"><i class="far fa-trash-alt text-danger"></i></button>';
                    $actions .= '</form>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['preview', 'action', 'vendor']);

            return $dt->make(true);
        }

        return view('admin.media.index');
    }

    /**
     * Get media grid for modal (AJAX)
     */
    public function grid(Request $request)
    {
        $media = Media::query()->latest();

        // Vendor Isolation
        if (auth()->guard('vendor')->check()) {
            $media->where('vendor_id', auth()->guard('vendor')->id());
        } elseif (!auth()->guard('admin')->check()) {
            $media->where('id', 0);
        }

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            switch ($request->type) {
                case 'images':
                    $media->images();
                    break;
                case 'videos':
                    $media->videos();
                    break;
                case 'documents':
                    $media->documents();
                    break;
            }
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $media->search($request->search);
        }

        // Pagination
        $perPage = $request->get('per_page', 24);
        $media = $media->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $media->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'file_name' => $item->file_name,
                    'mime_type' => $item->mime_type,
                    'size' => $item->human_readable_size,
                    'url' => $item->url,
                    'thumb' => $item->is_image ? $item->getCachedSize(480) : null,
                    'is_image' => $item->is_image,
                    'created_at' => $item->created_at->format('M d, Y'),
                    'custom_properties' => $item->custom_properties,
                ];
            }),
            'pagination' => [
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
                'per_page' => $media->perPage(),
                'total' => $media->total(),
            ],
        ]);
    }

    /**
     * Store a newly uploaded media file
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $uploadedMedia = [];
        $directoryParam = $request->get('directory', 'media');

        foreach ($request->file('files') as $file) {
            // Create media record first to get ID
            $media = Media::create([
                'uuid' => Str::uuid(),
                'collection_name' => $directoryParam,
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'disk' => config('filesystems.default'),
                'size' => $file->getSize(),
                'manipulations' => [],
                'custom_properties' => [],
                'generated_conversions' => [],
                'responsive_images' => [],
                'order_column' => Media::max('order_column') + 1,
                'vendor_id' => auth()->guard('vendor')->check() ? auth()->guard('vendor')->id() : null,
            ]);

            // Store file in subdirectory using directory param and media ID
            $storagePath = $directoryParam;
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs($storagePath, $filename, config('filesystems.default'));

            // Update file_name if needed
            $media->file_name = $filename;
            $media->save();

            // Generate cached sizes for images
            if ($media->is_image) {
                $media->generateCachedSizes();
            }

            $uploadedMedia[] = [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'url' => $media->url,
                'thumb' => $media->is_image ? $media->getCachedSize(480) : null,
                'is_image' => $media->is_image,
                'size' => $media->human_readable_size,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully',
            'data' => $uploadedMedia,
        ]);
    }

    /**
     * Update media metadata
     */
    public function update(Request $request, string $id)
    {
        $media = Media::findOrFail(Crypt::decryptString($id));

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $media->name = $request->name;
        $media->setCustomProperty('alt_text', $request->alt_text);
        $media->setCustomProperty('caption', $request->caption);
        $media->save();

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'data' => [
                'id' => $media->id,
                'name' => $media->name,
                'custom_properties' => $media->custom_properties,
            ],
        ]);
    }

    /**
     * Delete media file
     */
    public function destroy(string $id)
    {
        $media = Media::findOrFail(Crypt::decryptString($id));

        // Delete file from storage
        $disk = Storage::disk($media->disk);
        if ($disk->exists($media->getPath())) {
            $disk->delete($media->getPath());
        }

        // Delete directory if empty
        $directory = dirname($media->getPath());
        if ($disk->exists($directory) && empty($disk->allFiles($directory))) {
            $disk->deleteDirectory($directory);
        }

        $media->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully',
            ]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'Media deleted successfully');
    }

    /**
     * Get single media details
     */
    public function show(string $id)
    {
        $media = Media::findOrFail(Crypt::decryptString($id));

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->human_readable_size,
                'url' => $media->url,
                'thumb' => $media->is_image ? $media->getCachedSize(480) : null,
                'is_image' => $media->is_image,
                'created_at' => $media->created_at->format('M d, Y H:i'),
                'custom_properties' => $media->custom_properties,
                'alt_text' => $media->getCustomProperty('alt_text', ''),
                'caption' => $media->getCustomProperty('caption', ''),
            ],
        ]);
    }
}
