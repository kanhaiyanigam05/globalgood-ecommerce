<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Scope;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class VariantController extends Controller
{
    public function index(Request $request, string $productId)
    {
        $product = Product::findOrFail(Crypt::decryptString($productId));

        if ($request->ajax()) {
            $variants = $product->variants()->with(['attributes.values'])->get();

            return DataTables::of($variants)
                ->addIndexColumn()
                ->editColumn('sku', fn ($row) => $row->sku ?? '-')
                ->editColumn('price', fn ($row) => '$'.$row->price_formatted)
                ->editColumn('compare_at_price', fn ($row) => $row->compare_at_price ? '$'.$row->compare_at_price_formatted : '-')
                ->editColumn('quantity', fn ($row) => $row->quantity)
                ->addColumn('attributes', function ($row) {
                    $attrs = $row->attributes->map(function ($attr) {
                        return "<span class='badge bg-primary'>{$attr->name}: {$attr->pivot->value}</span>";
                    })->implode(' ');

                    return $attrs ?: '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) use ($product) {
                    $encryptedProductId = Crypt::encryptString($product->id);
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('admin.products.variants.edit', [$encryptedProductId, $encryptedId]);
                    $deleteUrl = route('admin.products.variants.destroy', [$encryptedProductId, $encryptedId]);

                    $actions = '<div class="d-flex gap-2">';
                    $actions .= "<a href=\"$editUrl\" class=\"btn btn-light-warning icon-btn b-r-4\"><i class=\"far fa-edit text-warning\"></i></a>";
                    $actions .= "<button class=\"btn btn-light-danger icon-btn b-r-4 delete-variant\" data-url=\"$deleteUrl\"><i class=\"far fa-trash-alt text-danger\"></i></button>";
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['attributes', 'status', 'action'])
                ->make(true);
        }
    }

    /**
     * Display a listing of all variants across all products
     */
    public function all(Request $request)
    {
        if ($request->ajax()) {
            $variants = Variant::with(['product', 'attributes'])->get();

            return DataTables::of($variants)
                ->addIndexColumn()
                ->addColumn('product', fn ($row) => $row->product->title ?? '-')
                ->editColumn('sku', fn ($row) => $row->sku ?? '-')
                ->editColumn('price', fn ($row) => '$'.$row->price_formatted)
                ->editColumn('quantity', fn ($row) => $row->quantity)
                ->addColumn('attributes', function ($row) {
                    return $row->attributes->map(function ($attr) {
                        return "<span class='badge bg-light-primary text-primary'>{$attr->name}: {$attr->pivot->value}</span>";
                    })->implode(' ');
                })
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedProductId = Crypt::encryptString($row->product_id);
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('admin.products.variants.edit', [$encryptedProductId, $encryptedId]);
                    
                    return "<a href=\"$editUrl\" class=\"btn btn-light-warning icon-btn b-r-4\"><i class=\"far fa-edit text-warning\"></i></a>";
                })
                ->rawColumns(['attributes', 'status', 'action'])
                ->make(true);
        }

        return view('admin.variants.index');
    }

    /**
     * Show the form for creating a new variant
     */
    public function create(string $productId)
    {
        $product = Product::findOrFail(Crypt::decryptString($productId));

        // Get variant-scope attributes with their values
        $attributes = Attribute::where('scope', Scope::VARIANT)
            ->with('values')
            ->get();

        return view('admin.variants.create', compact('product', 'attributes'));
    }

    /**
     * Store a newly created variant
     */
    public function store(Request $request, string $productId)
    {
        $product = Product::findOrFail(Crypt::decryptString($productId));

        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:255|unique:variants,sku',
            'status' => 'nullable|boolean',
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('attributes');
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['product_id'] = $product->id;

        $variant = Variant::create($data);

        // Attach attributes to variant
        if ($request->has('attributes')) {
            $syncData = [];
            foreach ($request->attributes as $attr) {
                $syncData[$attr['attribute_id']] = ['value' => $attr['value']];
            }
            $variant->attributes()->sync($syncData);
        }

        return redirect()->route('admin.products.edit', Crypt::encryptString($product->id))
            ->with('success', 'Variant created successfully.');
    }

    /**
     * Show the form for editing a variant
     */
    public function edit(string $productId, string $variantId)
    {
        $product = Product::with(['variants.attributes'])->findOrFail(Crypt::decryptString($productId));
        $variant = Variant::with(['attributes.values'])->findOrFail(Crypt::decryptString($variantId));

        // Only show attributes that are already assigned to this variant
        $attributes = $variant->attributes;

        return view('admin.variants.edit', compact('product', 'variant', 'attributes'));
    }

    /**
     * Update a variant
     */
    public function update(Request $request, string $productId, string $variantId)
    {
        $product = Product::findOrFail(Crypt::decryptString($productId));
        $variant = Variant::findOrFail(Crypt::decryptString($variantId));

        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'sku' => ['nullable','string','max:255',Rule::unique('variants', 'sku')->ignore($variant->id)],
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('attributes');
        $data['status'] = $request->has('status') ? 1 : 0;

        $variant->update($data);

        // Sync attributes
        if ($request->has('attributes')) {
            $syncData = [];
            foreach ($request->attributes as $attr) {
                $syncData[$attr['attribute_id']] = ['value' => $attr['value']];
            }
            $variant->attributes()->sync($syncData);
        } else {
            $variant->attributes()->detach();
        }

        return redirect()->back()
            ->with('success', 'Variant updated successfully.');
    }

    /**
     * Delete a variant
     */
    public function destroy(string $productId, string $variantId)
    {
        $product = Product::findOrFail(Crypt::decryptString($productId));
        $variant = Variant::findOrFail(Crypt::decryptString($variantId));

        $variant->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Variant deleted successfully.',
            ]);
        }

        return redirect()->route('admin.products.edit', Crypt::encryptString($product->id))
            ->with('success', 'Variant deleted successfully.');
    }

    /**
     * Bulk save variants from generator
     */
    public function bulkSave(Request $request, string $productId)
    {
        $product = Product::findOrFail(Crypt::decryptString($productId));
        Log::info('Product ID: '.$productId, $request->all());

        $validator = Validator::make($request->all(), [
            'variants' => 'required|array|min:1',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.compare_at_price' => 'nullable|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.status' => 'required|in:true,false,1,0',
            'variants.*.attributes' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $created = 0;
        $skippedDuplicates = 0;

        foreach ($request->variants as $variantData) {
            // Check for duplicate SKU if provided
            if (! empty($variantData['sku'])) {
                if (Variant::where('sku', $variantData['sku'])->exists()) {
                    $skippedDuplicates++;

                    continue;
                }
            }

            $data = [
                'product_id' => $product->id,
                'price' => $variantData['price'],
                'compare_at_price' => $variantData['compare_at_price'] ?? null,
                'quantity' => $variantData['quantity'],
                'sku' => $variantData['sku'] ?? null,
                'status' => (bool) $variantData['status'],
            ];

            $variant = Variant::create($data);

            // Attach attributes
            if (! empty($variantData['attributes'])) {
                $syncData = [];
                foreach ($variantData['attributes'] as $attr) {
                    $syncData[$attr['attribute_id']] = ['value' => $attr['value']];
                }
                $variant->attributes()->sync($syncData);
            }

            $created++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$created} variant(s) created successfully.".($skippedDuplicates > 0 ? " {$skippedDuplicates} skipped (duplicate SKUs)." : ''),
            'created' => $created,
            'skipped' => $skippedDuplicates,
        ]);
    }
}
