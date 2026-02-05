<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Scope;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\ComponentAttributeBag;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with(['category', 'images', 'vendor.profile'])->select('products.*');

            if ($request->has('vendor_id') && $request->vendor_id) {
                $products->where('vendor_id', $request->vendor_id);
            }

            // Status Filtering
            if ($request->has('status_filter') && $request->status_filter !== 'all') {
                if ($request->status_filter === 'active') {
                    $products->where('status', 1);
                } elseif ($request->status_filter === 'out_of_stock') {
                    $products->where('quantity', '<=', 0);
                } elseif ($request->status_filter === 'on_hold') {
                    $products->where('status', 0);
                } elseif ($request->status_filter === 'vendor_only') {
                    $products->whereNotNull('vendor_id');
                }
            }

            return DataTables::of($products)
                ->addIndexColumn()
                ->editColumn('title', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('admin.products.edit', $encryptedId);
                    return '<a href="'.$editUrl.'" class="f-w-600 text-dark hover-primary">'.Str::limit($row->title, 45).'</a>';
                })
                ->addColumn('image', function ($row) {
                    $image = $row->firstImage();
                    $src = $image ? $image->thumb(50, 50) : "https://placehold.co/100x100?text={$row->title}";

                    return '<div class="product-img-wrapper" style="width: 42px; height: 42px; overflow: hidden; border-radius: 4px; border: 1px solid #eef2f6; background: #fff; display: flex; align-items: center; justify-content: center;">
                                <img src="'.$src.'" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                            </div>';
                })
                ->addColumn('category', fn ($row) => '<span class="text-muted f-s-12">'.($row->category?->title ?? '-').'</span>')
                ->addColumn('vendor_name', fn ($row) => '<span class="text-secondary f-w-500 f-s-12">'.($row->vendor?->legal_name ?? 'GlobalGood').'</span>')
                ->editColumn('price', fn ($row) => '<span class="f-w-600 text-dark">$'.$row->price_formatted.'</span>')
                ->editColumn('quantity', function ($row) {
                    $textClass = $row->quantity > 0 ? 'text-dark' : 'text-danger';
                    return '<div class="d-flex flex-column" style="line-height: 1.2;">
                                <span class="'.$textClass.' f-w-600">'.$row->quantity.'</span>
                                <small class="text-muted" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.3px;">In Stock</small>
                            </div>';
                })
                ->addColumn('status', function ($row) {
                    $statusClass = $row->status ? 'success' : 'secondary';
                    $statusText = $row->status ? 'Active' : 'Inactive';

                    if ($row->quantity <= 0) {
                        $statusClass = 'danger';
                        $statusText = 'Out of Stock';
                    }

                    $encryptedId = Crypt::encryptString($row->id);
                    $btnClass = $row->status ? 'btn-light-success' : 'btn-light-secondary';

                    return '<button type="button" class="btn '.$btnClass.' btn-sm py-1 px-3 b-r-20 toggle-status-btn"
                                data-id="'.$encryptedId.'" id="status-btn-'.$row->id.'">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="dot dot-'.$statusClass.'"></span>
                                    <span class="f-w-600">'.$statusText.'</span>
                                </span>
                            </button>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('admin.products.edit', $encryptedId);
                    $deleteUrl = route('admin.products.destroy', $encryptedId);

                    $deleteForm = view('components.forms.form', [
                        'action' => $deleteUrl,
                        'method' => 'DELETE',
                        'type' => 'delete',
                        'varient' => 'reactive',
                        'confirm' => true,
                        'confirm-title' => 'Delete Product',
                        'confirm-message' => 'This action cannot be undone',
                        'slot' => new HtmlString(
                            '<button class="btn btn-icon btn-light-danger btn-sm b-r-6" type="submit" title="Delete">
                                <i class="ph ph-trash f-s-16"></i>
                            </button>'
                        ),
                    ])->render();

                    $actions = '<div class="action-btn-group">';
                    $actions .= "<a href=\"$editUrl\" class=\"btn btn-icon btn-light-primary btn-sm b-r-6\" title=\"Edit\"><i class=\"ph ph-pencil-simple f-s-16\"></i></a>";
                    $actions .= $deleteForm;
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['title', 'image', 'category', 'vendor_name', 'price', 'quantity', 'status', 'approval_status', 'action'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->getHierarchicalCategories();

        // Get product-scoped attributes with values
        $productAttributes = \App\Models\Attribute::where('scope', \App\Enums\Scope::PRODUCT)
            ->with('values')
            ->get();

        // Get variant-scoped attributes with values for variant generator
        $variantAttributes = \App\Models\Attribute::where('scope', \App\Enums\Scope::VARIANT)
            ->with('values')
            ->get();

        return view('admin.products.create', compact('categories', 'productAttributes', 'variantAttributes'));
    }

    /**
     * Get categories in hierarchical structure for the hierarchical select component
     */
    private function getHierarchicalCategories()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('title')
            ->get();

        return $this->formatCategoriesRecursive($categories);
    }

    /**
     * Format categories recursively to match hierarchical select structure
     */
    private function formatCategoriesRecursive($categories)
    {
        return $categories->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->title,
            'children' => $category->children->isNotEmpty()
                ? $this->formatCategoriesRecursive($category->children)
                : [],
        ])->toArray();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'media_ids' => 'nullable|array',
            'media_ids.*' => 'integer|exists:media,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'variant_options' => 'nullable|array',
            'variants' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        return DB::transaction(function () use ($request) {
            $data = $request->except(['images', 'variant_options', 'variants', 'product_attributes']);

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            } else {
                $data['slug'] = Str::slug($data['slug']);
            }

            $data['status'] = $request->has('status') ? 1 : 0;
            $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

            $product = Product::create($data);

            // 1. Process Attributes and Categories connection
            if ($request->has('variant_options')) {
                foreach ($request->variant_options as $option) {
                    $attribute = null;
                    if (! empty($option['attribute_id'])) {
                        $attribute = Attribute::find($option['attribute_id']);
                    } else {
                        $attribute = Attribute::firstOrCreate(
                            ['name' => $option['name'], 'scope' => Scope::VARIANT]
                        );
                    }

                    if ($attribute) {
                        // Connect attribute to category if not already connected
                        if (! $attribute->categories()->where('category_id', $product->category_id)->exists()) {
                            $attribute->categories()->attach($product->category_id);
                        }

                        // Handle new values for this attribute
                        if (! empty($option['values'])) {
                            foreach ($option['values'] as $value) {
                                AttributeValue::firstOrCreate([
                                    'attribute_id' => $attribute->id,
                                    'value' => $value,
                                ]);
                            }
                        }
                    }
                }
            }

            // 2. Handle Variants
            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'] ?? Str::upper(Str::random(8)),
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'] ?? 0,
                        'status' => true,
                    ]);

                    // Attach attributes to this variant
                    if (! empty($variantData['attributes'])) {
                        $attributesJson = is_string($variantData['attributes'])
                            ? json_decode($variantData['attributes'], true)
                            : $variantData['attributes'];

                        foreach ($attributesJson as $attr) {
                            $dbAttr = Attribute::where('name', $attr['name'])
                                ->where('scope', Scope::VARIANT)
                                ->first();

                            if ($dbAttr) {
                                $variant->attributes()->attach($dbAttr->id, [
                                    'value' => $attr['value'],
                                ]);
                            }
                        }
                    }
                }
            }

            // Sync product attributes (non-variants)
            if ($request->has('product_attributes')) {
                $syncData = [];
                foreach ($request->product_attributes as $id => $value) {
                    if (! empty($value)) {
                        $syncData[$id] = ['value' => $value];
                    }
                }
                $product->attributes()->sync($syncData);
            }

            // Handle Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = ImageHelper::store($file, 'products');
                    $product->images()->create([
                        'file' => $path,
                        'alt' => $product->title,
                        'title' => $product->title,
                    ]);
                }
            }

            // Handle Media library selections
            if ($request->has('media_ids')) {
                foreach ($request->media_ids as $mediaId) {
                    $media = \App\Models\Media::find($mediaId);
                    if ($media) {
                        $product->images()->create([
                            'media_id' => $media->id,
                            'file' => $media->getPath(), // Fallback path
                            'alt' => $product->title,
                            'title' => $product->title,
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully with '.$product->variants()->count().' variants.');
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with(['attributes', 'variants.attributes', 'images'])->findOrFail(Crypt::decryptString($id));
        $categories = $this->getHierarchicalCategories();

        // Get product-scoped attributes with values
        $productAttributes = \App\Models\Attribute::where('scope', \App\Enums\Scope::PRODUCT)
            ->with(['values', 'categories'])
            ->get();

        // Get variant-scoped attributes with values for variant generator
        $variantAttributes = \App\Models\Attribute::where('scope', \App\Enums\Scope::VARIANT)
            ->with(['values', 'categories'])
            ->get();

        // Prepare INITIAL OPTIONS from existing variants
        $initialOptions = [];
        $tempOptions = [];

        foreach ($product->variants as $variant) {
            foreach ($variant->attributes as $attr) {
                if (! isset($tempOptions[$attr->id])) {
                    $fullAttr = $variantAttributes->find($attr->id) ?: \App\Models\Attribute::with('values')->find($attr->id);
                    $tempOptions[$attr->id] = [
                        'attribute_id' => $attr->id,
                        'name' => $attr->name,
                        'type' => $fullAttr ? $fullAttr->type : 'text',
                        'isDbAttribute' => true,
                        'isDone' => true,
                        'values' => [],
                        'availableValues' => $fullAttr ? $fullAttr->values->map(fn ($v) => [
                            'name' => $v->value,
                            'value' => $v->value,
                            'code' => $v->code,
                        ])->toArray() : [],
                    ];
                }
                if (! in_array($attr->pivot->value, $tempOptions[$attr->id]['values'])) {
                    $tempOptions[$attr->id]['values'][] = $attr->pivot->value;
                }
            }
        }
        $initialOptions = array_values($tempOptions);

        // Prepare INITIAL VARIANTS
        $initialVariants = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->price_formatted,
                'compare_at_price' => $variant->compare_at_price_formatted,
                'quantity' => $variant->quantity,
                'status' => $variant->status,
                'attributes' => $variant->attributes->map(function ($attr) {
                    return [
                        'attribute_id' => $attr->id,
                        'name' => $attr->name,
                        'value' => $attr->pivot->value,
                    ];
                }),
            ];
        });

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'productAttributes',
            'variantAttributes',
            'initialOptions',
            'initialVariants'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail(Crypt::decryptString($id));

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'media_ids' => 'nullable|array',
            'media_ids.*' => 'integer|exists:media,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'variant_options' => 'nullable|array',
            'variants' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        return DB::transaction(function () use ($request, $product) {
            $data = $request->except(['images', 'existing_files', 'variant_options', 'variants', 'product_attributes']);

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            } else {
                $data['slug'] = Str::slug($data['slug']);
            }

            $data['status'] = $request->has('status') ? 1 : 0;
            $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

            $product->update($data);

            // 1. Process Attributes and Categories connection
            if ($request->has('variant_options')) {
                foreach ($request->variant_options as $option) {
                    $attribute = null;
                    if (! empty($option['attribute_id'])) {
                        $attribute = Attribute::find($option['attribute_id']);
                    } else {
                        $attribute = Attribute::firstOrCreate(
                            ['name' => $option['name'], 'scope' => Scope::VARIANT]
                        );
                    }

                    if ($attribute) {
                        if (! $attribute->categories()->where('category_id', $product->category_id)->exists()) {
                            $attribute->categories()->attach($product->category_id);
                        }

                        if (! empty($option['values'])) {
                            foreach ($option['values'] as $value) {
                                AttributeValue::firstOrCreate([
                                    'attribute_id' => $attribute->id,
                                    'value' => $value,
                                ]);
                            }
                        }
                    }
                }
            }

            // 2. Handle Variants (Synchronize)
            if ($request->has('variants')) {
                // For simplicity in this implementation, we recreate variants.
                // In a production app, you might want to match by SKU or attributes to preserve IDs.
                $product->variants()->delete();

                foreach ($request->variants as $variantData) {
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'] ?? Str::upper(Str::random(8)),
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'] ?? 0,
                        'status' => true,
                    ]);

                    if (! empty($variantData['attributes'])) {
                        $attributesJson = is_string($variantData['attributes'])
                            ? json_decode($variantData['attributes'], true)
                            : $variantData['attributes'];

                        foreach ($attributesJson as $attr) {
                            $dbAttr = Attribute::where('name', $attr['name'])
                                ->where('scope', Scope::VARIANT)
                                ->first();

                            if ($dbAttr) {
                                $variant->attributes()->attach($dbAttr->id, [
                                    'value' => $attr['value'],
                                ]);
                            }
                        }
                    }
                }
            }

            // Sync product attributes
            if ($request->has('product_attributes')) {
                $syncData = [];
                foreach ($request->product_attributes as $id => $value) {
                    if (! empty($value)) {
                        $syncData[$id] = ['value' => $value];
                    }
                }
                $product->attributes()->sync($syncData);
            }

            // Handle existing files deletion
            $existingImageIds = $request->input('existing_image_ids', []);
            $product->images()->whereNotIn('id', $existingImageIds)->delete();

            // Handle new images with position
            $position = count($existingImageIds);
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = ImageHelper::store($file, 'products');
                    $product->images()->create([
                        'file' => $path,
                        'alt' => $product->title,
                        'title' => $product->title,
                        'position' => $position++,
                    ]);
                }
            }

            // Handle Media library selections with position
            if ($request->has('media_ids')) {
                foreach ($request->media_ids as $mediaId) {
                    if (!$product->images()->where('media_id', $mediaId)->exists()) {
                        $media = \App\Models\Media::find($mediaId);
                        if ($media) {
                            $product->images()->create([
                                'media_id' => $media->id,
                                'file' => $media->getPath(),
                                'alt' => $product->title,
                                'title' => $product->title,
                                'position' => $position++,
                            ]);
                        }
                    }
                }
            }

            // Update positions for existing images
            if ($request->has('image_positions')) {
                foreach ($request->image_positions as $imageId => $pos) {
                    $product->images()->where('id', $imageId)->update(['position' => $pos]);
                }
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail(Crypt::decryptString($id));

        // Deleting the product should cascade delete images if set up in DB,
        // but since we are using code logic, let's delete images explicitly first to trigger the model event.
        // Actually, if we use $product->images()->delete() it runs a query.
        // We need to iterate to trigger model events.
        foreach ($product->images as $image) {
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Status update
     */
    public function status(Request $request)
    {
        try {
            $id = $request->id;
            $product = Product::findOrFail(Crypt::decryptString($id));

            $product->status = ! $product->status;
            $product->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully.',
                    'status' => $product->status,
                ]);
            }

            return redirect()->back()
                ->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Product status update failed: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update status.',
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update status.');
        }
    }

    /**
     * Approval status update
     */
    public function approve(string $id)
    {
        $product = Product::findOrFail(Crypt::decryptString($id));

        $product->is_approved = ! $product->is_approved;
        $product->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Approval status updated successfully.',
                'status' => $product->is_approved,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Approval status updated successfully.');
    }

    /**
     * Generate variants based on selected attribute combinations
     */
    public function generateVariants(Request $request, string $productId)
    {
        $product = Product::findOrFail(Crypt::decryptString($productId));
        Log::info($request->all());

        $validator = Validator::make($request->all(), [
            'attributes' => 'required|array|min:1',
            'attributes.*.attribute_id' => 'required|exists:attributes,id',
            'attributes.*.values' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Generate all combinations
        $combinations = $this->generateCombinations($request->input('attributes'));

        $variants = [];
        foreach ($combinations as $combination) {
            $variants[] = [
                'attributes' => $combination,
                'price' => $product->price_formatted,
                'compare_at_price' => $product->compare_at_price_formatted ?? '',
                'quantity' => 0,
                'sku' => '',
                'status' => true,
            ];
        }

        return response()->json([
            'success' => true,
            'variants' => $variants,
            'count' => count($variants),
        ]);
    }

    /**
     * Helper method to generate all attribute combinations
     */
    private function generateCombinations($attributes)
    {
        if (empty($attributes)) {
            return [[]];
        }

        $first = array_shift($attributes);
        $remainingCombinations = $this->generateCombinations($attributes);
        $combinations = [];

        foreach ($first['values'] as $value) {
            foreach ($remainingCombinations as $combination) {
                $combinations[] = array_merge([
                    [
                        'attribute_id' => $first['attribute_id'],
                        'attribute_name' => $first['attribute_name'] ?? '',
                        'value' => $value,
                    ],
                ], $combination);
            }
        }

        return $combinations;
    }
}