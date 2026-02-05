<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Variant;
use App\Enums\Scope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $vendor = auth('vendor')->user();

        if ($request->ajax()) {
            $products = Product::where('vendor_id', $vendor->id)
                ->with(['category', 'images'])
                ->select('products.*');

            return DataTables::of($products)
                ->addIndexColumn()
                ->editColumn('title', fn ($row) => Str::limit($row->title, 30))
                ->addColumn('image', function ($row) {
                    $image = $row->firstImage();
                    $src = $image ? $image->file_url : "https://placehold.co/100x100?text={$row->title}";
                    return '<img src="'.$src.'" class="img-thumbnail" width="100">';
                })
                ->addColumn('category', fn ($row) => $row->category?->title ?? '-')
                ->editColumn('price', fn ($row) => '$'.$row->price_formatted)
                ->editColumn('quantity', fn ($row) => $row->quantity)
                ->addColumn('status', function ($row) {
                    $statusHtml = view('components.forms.switch', [
                        'name' => 'status',
                        'id' => "status-{$row->id}",
                        'value' => $row->status,
                        'checked' => $row->status,
                        'class' => 'toggle-status',
                        'attributes' => new ComponentAttributeBag([
                            'data-id' => Crypt::encryptString($row->id),
                        ]),
                    ])->render();
                    return $statusHtml;
                })
                ->addColumn('approval_status', function ($row) {
                    if ($row->is_approved) {
                        return '<span class="badge bg-success">Approved</span>';
                    }
                    return '<span class="badge bg-warning">Pending Approval</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('vendor.products.edit', $encryptedId);
                    $deleteUrl = route('vendor.products.destroy', $encryptedId);

                    $deleteForm = view('components.forms.form', [
                        'action' => $deleteUrl,
                        'method' => 'DELETE',
                        'type' => 'delete',
                        'varient' => 'reactive',
                        'confirm' => true,
                        'confirm-title' => 'Delete Product',
                        'confirm-message' => 'This action cannot be undone',
                        'slot' => new HtmlString(
                            '<button class="btn btn-light-danger icon-btn b-r-4" type="submit">
                                <i class="far fa-trash-alt text-danger"></i>
                            </button>'
                        ),
                    ])->render();

                    $actions = '<div class="d-flex gap-2">';
                    $actions .= "<a href=\"$editUrl\" class=\"btn btn-light-warning icon-btn b-r-4\" type=\"button\"><i class=\"far fa-edit text-warning\"></i></a>";
                    $actions .= $deleteForm;
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['image', 'status', 'approval_status', 'action'])
                ->make(true);
        }

        return view('vendor.products.index');
    }

    public function create()
    {
        $categories = $this->getHierarchicalCategories();
        $productAttributes = Attribute::where('scope', Scope::PRODUCT)->with('values')->get();
        $variantAttributes = Attribute::where('scope', Scope::VARIANT)->with('values')->get();
        
        $initialOptions = [];
        $initialVariants = [];

        return view('vendor.products.create', compact('categories', 'productAttributes', 'variantAttributes', 'initialOptions', 'initialVariants'));
    }

    private function getHierarchicalCategories()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('title')
            ->get();

        return $this->formatCategoriesRecursive($categories);
    }

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

    public function store(Request $request)
    {
        $vendor = auth('vendor')->user();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return DB::transaction(function () use ($request, $vendor) {
            $data = $request->except(['images', 'variant_options', 'variants', 'product_attributes']);
            
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            } else {
                $data['slug'] = Str::slug($data['slug']);
            }

            $data['status'] = $request->has('status') ? 1 : 0;
            $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
            $data['is_approved'] = false;
            $data['vendor_id'] = $vendor->id;

            $product = Product::create($data);

            // Handle variants and attributes (simplified for now to match admin if needed)
            // Reusing logic from admin controller
             if ($request->has('variant_options')) {
                foreach ($request->variant_options as $option) {
                    $attribute = null;
                    if (!empty($option['attribute_id'])) {
                        $attribute = Attribute::find($option['attribute_id']);
                    } else {
                        $attribute = Attribute::firstOrCreate(
                            ['name' => $option['name'], 'scope' => Scope::VARIANT]
                        );
                    }

                    if ($attribute) {
                        if (!$attribute->categories()->where('category_id', $product->category_id)->exists()) {
                            $attribute->categories()->attach($product->category_id);
                        }
                        if (!empty($option['values'])) {
                            foreach ($option['values'] as $value) {
                                AttributeValue::firstOrCreate([
                                    'attribute_id' => $attribute->id,
                                    'value' => $value
                                ]);
                            }
                        }
                    }
                }
            }

            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'] ?? Str::upper(Str::random(8)),
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'] ?? 0,
                        'status' => true,
                    ]);

                    if (!empty($variantData['attributes'])) {
                        $attributesJson = is_string($variantData['attributes']) 
                            ? json_decode($variantData['attributes'], true) 
                            : $variantData['attributes'];

                        foreach ($attributesJson as $attr) {
                            $dbAttr = Attribute::where('name', $attr['name'])
                                ->where('scope', Scope::VARIANT)
                                ->first();
                            
                            if ($dbAttr) {
                                $variant->attributes()->attach($dbAttr->id, ['value' => $attr['value']]);
                            }
                        }
                    }
                }
            }

            if ($request->has('product_attributes')) {
                $syncData = [];
                foreach ($request->product_attributes as $id => $value) {
                    if (!empty($value)) {
                        $syncData[$id] = ['value' => $value];
                    }
                }
                $product->attributes()->sync($syncData);
            }

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

            if ($request->has('media_ids')) {
                foreach ($request->media_ids as $mediaId) {
                    $media = \App\Models\Media::find($mediaId);
                    if ($media) {
                        $product->images()->create([
                            'media_id' => $media->id,
                            'file' => $media->getPath(),
                            'alt' => $product->title,
                            'title' => $product->title,
                        ]);
                    }
                }
            }

            return redirect()->route('vendor.products.index')
                ->with('success', 'Product created successfully.');
        });
    }

    public function edit(string $id)
    {
        $vendor = auth('vendor')->user();
        $product = Product::where('vendor_id', $vendor->id)
            ->with(['images', 'attributes', 'variants.attributes'])
            ->findOrFail(Crypt::decryptString($id));
        
        $categories = $this->getHierarchicalCategories();
        $images = $product->images->map(fn ($img) => [
            'path' => $img->file,
            'name' => basename($img->file),
            'type' => 'image',
        ])->toArray();

        $productAttributes = Attribute::where('scope', Scope::PRODUCT)->with(['values', 'categories'])->get();
        $variantAttributes = Attribute::where('scope', Scope::VARIANT)->with(['values', 'categories'])->get();

        // Reusing logic from admin edit to prepare initial options and variants
        $initialOptions = [];
        $tempOptions = [];
        foreach ($product->variants as $variant) {
            foreach ($variant->attributes as $attr) {
                if (!isset($tempOptions[$attr->id])) {
                    $fullAttr = $variantAttributes->find($attr->id) ?: Attribute::with('values')->find($attr->id);
                    $tempOptions[$attr->id] = [
                        'attribute_id' => $attr->id,
                        'name' => $attr->name,
                        'type' => $fullAttr ? $fullAttr->type : 'text',
                        'isDbAttribute' => true,
                        'isDone' => true,
                        'values' => [],
                        'availableValues' => $fullAttr ? $fullAttr->values->map(fn($v) => ['name' => $v->value, 'value' => $v->value, 'code' => $v->code])->toArray() : []
                    ];
                }
                if (!in_array($attr->pivot->value, $tempOptions[$attr->id]['values'])) {
                    $tempOptions[$attr->id]['values'][] = $attr->pivot->value;
                }
            }
        }
        $initialOptions = array_values($tempOptions);

        $initialVariants = $product->variants->map(fn ($variant) => [
            'id' => $variant->id,
            'sku' => $variant->sku,
            'price' => $variant->price_formatted,
            'compare_at_price' => $variant->compare_at_price_formatted,
            'quantity' => $variant->quantity,
            'status' => $variant->status,
            'attributes' => $variant->attributes->map(fn ($attr) => ['attribute_id' => $attr->id, 'name' => $attr->name, 'value' => $attr->pivot->value])
        ]);

        return view('vendor.products.edit', compact('product', 'categories', 'images', 'productAttributes', 'variantAttributes', 'initialOptions', 'initialVariants'));
    }

    public function update(Request $request, string $id)
    {
        $vendor = auth('vendor')->user();
        $product = Product::where('vendor_id', $vendor->id)->findOrFail(Crypt::decryptString($id));

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
            return redirect()->back()->withErrors($validator)->withInput();
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
            $data['is_approved'] = false;

            $product->update($data);

            // Reusing variants sync logic
            if ($request->has('variant_options')) {
                foreach ($request->variant_options as $option) {
                    $attribute = null;
                    if (!empty($option['attribute_id'])) {
                        $attribute = Attribute::find($option['attribute_id']);
                    } else {
                        $attribute = Attribute::firstOrCreate(['name' => $option['name'], 'scope' => Scope::VARIANT]);
                    }
                    if ($attribute) {
                        if (!$attribute->categories()->where('category_id', $product->category_id)->exists()) {
                            $attribute->categories()->attach($product->category_id);
                        }
                        if (!empty($option['values'])) {
                            foreach ($option['values'] as $value) {
                                AttributeValue::firstOrCreate(['attribute_id' => $attribute->id, 'value' => $value]);
                            }
                        }
                    }
                }
            }

            if ($request->has('variants')) {
                $product->variants()->delete(); 
                foreach ($request->variants as $variantData) {
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'] ?? Str::upper(Str::random(8)),
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'] ?? 0,
                        'status' => true,
                    ]);

                    if (!empty($variantData['attributes'])) {
                        $attributesJson = is_string($variantData['attributes']) ? json_decode($variantData['attributes'], true) : $variantData['attributes'];
                        foreach ($attributesJson as $attr) {
                            $dbAttr = Attribute::where('name', $attr['name'])->where('scope', Scope::VARIANT)->first();
                            if ($dbAttr) {
                                $variant->attributes()->attach($dbAttr->id, ['value' => $attr['value']]);
                            }
                        }
                    }
                }
            }

            if ($request->has('product_attributes')) {
                $syncData = [];
                foreach ($request->product_attributes as $id => $value) {
                    if (!empty($value)) {
                        $syncData[$id] = ['value' => $value];
                    }
                }
                $product->attributes()->sync($syncData);
            }

            $existingFiles = $request->input('existing_files', []);
            foreach ($product->images as $img) {
                if (!in_array($img->file, $existingFiles)) {
                    $img->delete();
                }
            }

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
                            ]);
                        }
                    }
                }
            }

            return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully.');
        });
    }

    public function destroy(string $id)
    {
        $vendor = auth('vendor')->user();
        $product = Product::where('vendor_id', $vendor->id)->findOrFail(Crypt::decryptString($id));

        foreach ($product->images as $image) {
            $image->delete();
        }

        $product->delete();

        return redirect()->route('vendor.products.index')->with('success', 'Product deleted successfully.');
    }

    public function status(string $id)
    {
        $vendor = auth('vendor')->user();
        $product = Product::where('vendor_id', $vendor->id)->findOrFail(Crypt::decryptString($id));

        $product->status = !$product->status;
        $product->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'status' => $product->status,
            ]);
        }

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
