<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\Country;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function selectType()
    {
        return view('admin.discounts.select-type');
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'amount_off_products');
        
        // Validate type
        if (!in_array($type, ['amount_off_products', 'buy_x_get_y', 'amount_off_order', 'free_shipping'])) {
            return redirect()->route('admin.discounts.select-type');
        }

        $products = Product::with(['images', 'variants.attributes'])->get();
        $collections = Collection::withCount('products')->get();
        $customers = Customer::all();
        $countries = Country::all();
        
        // Route to type-specific view
        $viewName = 'admin.discounts.create-' . str_replace('_', '-', $type);
        
        return view($viewName, compact('products', 'collections', 'customers', 'countries', 'type'));
    }

    public function store(Request $request)
    {
        $rules = [
            'method' => 'required|in:code,automatic',
            'type' => 'required|in:amount_off_products,buy_x_get_y,amount_off_order,free_shipping',
            'starts_at_date' => 'required|date',
            'starts_at_time' => 'required',
        ];

        if ($request->method == 'code') {
            $rules['code'] = 'required|unique:discounts,code';
        } else {
            $rules['title'] = 'required';
        }

        if (in_array($request->type, ['amount_off_products', 'amount_off_order'])) {
            $rules['value'] = 'required|numeric|min:0';
            $rules['value_type'] = 'required|in:percentage,fixed_amount';
        }

        if ($request->type == 'buy_x_get_y') {
            $rules['buy_value'] = 'required|numeric|min:1';
            $rules['get_quantity'] = 'required|numeric|min:1';
            $rules['get_type'] = 'required|in:percentage,fixed_amount,free';
            if ($request->get_type != 'free') {
                $rules['get_value'] = 'required|numeric|min:0';
            }
        }

        $request->validate($rules);

        return \DB::transaction(function () use ($request) {
            $starts_at = $request->starts_at_date . ' ' . $request->starts_at_time . ':00';
            $ends_at = $request->ends_at_date ? ($request->ends_at_date . ' ' . ($request->ends_at_time ? ($request->ends_at_time . ':00') : '23:59:59')) : null;

            $data = [
                'code' => $request->code,
                'title' => $request->title,
                'type' => $request->type ?? 'amount_off_products',
                'method' => $request->method,
                'starts_at' => $starts_at,
                'ends_at' => $ends_at,
                'is_active' => true,
                'customer_selection' => $request->customer_selection ?? 'all',
                'usage_limit_total' => $request->has('limit_usage_total') ? $request->usage_limit_total : null,
                'usage_limit_per_customer' => $request->has('usage_limit_per_customer'),
                'combinations' => $request->combinations,
                'is_featured' => $request->has('is_featured'),
            ];

            // Common fields for simple discounts
            if (in_array($data['type'], ['amount_off_products', 'amount_off_order'])) {
                $data['value_type'] = $request->value_type;
                $data['value'] = $request->value;

                $minReqValue = null;
                if ($request->min_requirement_type == 'amount') {
                    $minReqValue = $request->min_requirement_value;
                } elseif ($request->min_requirement_type == 'quantity') {
                    $minReqValue = $request->min_qty_value;
                }
                $data['min_requirement_type'] = $request->min_requirement_type;
                $data['min_requirement_value'] = $minReqValue;
            }

            // Buy X Get Y specific
            if ($data['type'] == 'buy_x_get_y') {
                $data['buy_type'] = $request->buy_type;
                $data['buy_value'] = $request->buy_value;

                // Process Buy Items (Save to discount_items) after creation
                // We will handle this AFTER creating the discount
                
                $data['get_quantity'] = $request->get_quantity;
                $data['get_type'] = $request->get_type;
                $data['get_value'] = ($request->get_type == 'fixed_amount') ? $request->get_value_fixed : $request->get_value;
                $data['max_uses_per_order'] = $request->has('limit_uses_per_order') ? $request->max_uses_per_order : null;
            }

            // Free Shipping specific
            if ($data['type'] == 'free_shipping') {
                $data['countries'] = $request->countries ?? 'all';
                $data['selected_countries'] = ($data['countries'] == 'selected') ? $request->selected_countries : null;
                $data['exclude_shipping_over'] = $request->has('exclude_shipping_rates') ? $request->exclude_shipping_over : null;
                
                // Also check for min requirements for free shipping
                if ($request->min_requirement_type == 'amount') {
                    $data['min_requirement_type'] = 'amount';
                    $data['min_requirement_value'] = $request->min_requirement_value;
                }
            }

            $discount = Discount::create($data);

            // Sync Specific Customers
            if ($request->customer_selection == 'specific' && $request->has('customer_ids')) {
                $discount->customers()->sync($request->customer_ids);
            }

            // Sync Targets (for amount_off_products)
            if ($data['type'] == 'amount_off_products' && $request->has('targets')) {
                $collections = [];
                $products = [];
                $variantsByProduct = [];

                foreach ($request->targets as $index => $targetId) {
                    $type = $request->target_types[$index];

                    if ($type == 'collection') {
                        $collections[] = $targetId;
                    } elseif ($type == 'product') {
                        $products[] = $targetId;
                    } elseif ($type == 'variant') {
                        $variant = \App\Models\Variant::find($targetId);
                        if ($variant) {
                            $variantsByProduct[$variant->product_id][] = $targetId;
                        }
                    }
                }

                foreach (array_unique($collections) as $collectionId) {
                    \App\Models\DiscountItem::create([
                        'discount_id' => $discount->id,
                        'collection_id' => $collectionId
                    ]);
                }

                foreach (array_unique($products) as $productId) {
                    \App\Models\DiscountItem::create([
                        'discount_id' => $discount->id,
                        'product_id' => $productId,
                        'variant_ids' => null
                    ]);
                }

                foreach ($variantsByProduct as $productId => $variantIds) {
                    if (in_array($productId, $products)) continue;
                    \App\Models\DiscountItem::create([
                        'discount_id' => $discount->id,
                        'product_id' => $productId,
                        'variant_ids' => array_unique($variantIds)
                    ]);
                }
            }

            // Sync Buy X Get Y Items
            if ($data['type'] == 'buy_x_get_y') {
                // Buy Items
                if ($request->has('buy_items')) {
                     $buyCollections = [];
                     $buyProducts = [];
                     $buyVariants = [];

                     foreach ($request->buy_items as $index => $id) {
                         $itemType = $request->buy_target_types[$index];
                         if ($itemType == 'collection') $buyCollections[] = $id;
                         elseif ($itemType == 'product') $buyProducts[] = $id;
                         elseif ($itemType == 'variant') {
                             $v = \App\Models\Variant::find($id);
                             if ($v) $buyVariants[$v->product_id][] = $id;
                         }
                     }
                     
                     foreach (array_unique($buyCollections) as $cid) {
                         \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'collection_id' => $cid]);
                     }
                     foreach (array_unique($buyProducts) as $pid) {
                         \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'product_id' => $pid, 'variant_ids' => null]);
                     }
                     foreach ($buyVariants as $pid => $vids) {
                         if (!in_array($pid, $buyProducts)) {
                             \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'product_id' => $pid, 'variant_ids' => array_unique($vids)]);
                         }
                     }
                }

                // Get Items
                if ($request->has('get_items')) {
                     $getCollections = [];
                     $getProducts = [];
                     $getVariants = [];

                     foreach ($request->get_items as $index => $id) {
                         $itemType = $request->get_target_types[$index];
                         if ($itemType == 'collection') $getCollections[] = $id;
                         elseif ($itemType == 'product') $getProducts[] = $id;
                         elseif ($itemType == 'variant') {
                             $v = \App\Models\Variant::find($id);
                             if ($v) $getVariants[$v->product_id][] = $id;
                         }
                     }
                     
                     foreach (array_unique($getCollections) as $cid) {
                         \App\Models\DiscountRewardItem::create(['discount_id' => $discount->id, 'collection_id' => $cid]);
                     }
                     foreach (array_unique($getProducts) as $pid) {
                         \App\Models\DiscountRewardItem::create(['discount_id' => $discount->id, 'product_id' => $pid, 'variant_ids' => null]);
                     }
                     foreach ($getVariants as $pid => $vids) {
                         if (!in_array($pid, $getProducts)) {
                             \App\Models\DiscountRewardItem::create(['discount_id' => $discount->id, 'product_id' => $pid, 'variant_ids' => array_unique($vids)]);
                         }
                     }
                }
            }

            return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully.');
        });
    }

    public function edit(Discount $discount)
    {
        // Load discount with relationships
        $discount->load(['customers', 'items']);
        
        // Get available data for form
        $products = Product::with(['images', 'variants.attributes'])->get();
        $collections = Collection::withCount('products')->get();
        $customers = Customer::all();
        $countries = Country::all();
        
        $type = $discount->type ?? 'amount_off_products';
        $viewName = 'admin.discounts.edit-' . str_replace('_', '-', $type);
        
        // If view doesn't exist, fallback to general edit (or amount-off-products)
        if (!view()->exists($viewName)) {
            $viewName = 'admin.discounts.edit';
        }

        return view($viewName, compact('discount', 'products', 'collections', 'customers', 'countries', 'type'));
    }

    public function update(Request $request, Discount $discount)
    {
        $rules = [
            'method' => 'required|in:code,automatic',
            'starts_at_date' => 'required|date',
            'starts_at_time' => 'required',
        ];

        if ($request->method == 'code') {
            $rules['code'] = 'required|unique:discounts,code,' . $discount->id;
        } else {
            $rules['title'] = 'required';
        }

        $type = $discount->type ?? 'amount_off_products';

        if (in_array($type, ['amount_off_products', 'amount_off_order'])) {
            $rules['value'] = 'required|numeric|min:0';
            $rules['value_type'] = 'required|in:percentage,fixed_amount';
        }

        if ($type == 'buy_x_get_y') {
            $rules['buy_value'] = 'required|numeric|min:1';
            $rules['get_quantity'] = 'required|numeric|min:1';
            $rules['get_type'] = 'required|in:percentage,fixed_amount,free';
            if ($request->get_type != 'free') {
                $rules['get_value'] = 'required|numeric|min:0';
            }
        }

        $request->validate($rules);

        return \DB::transaction(function () use ($request, $discount) {
            $starts_at = $request->starts_at_date . ' ' . $request->starts_at_time . ':00';
            $ends_at = $request->ends_at_date ? ($request->ends_at_date . ' ' . ($request->ends_at_time ? ($request->ends_at_time . ':00') : '23:59:59')) : null;

            $data = [
                'code' => $request->code,
                'title' => $request->title,
                'method' => $request->method,
                'starts_at' => $starts_at,
                'ends_at' => $ends_at,
                'customer_selection' => $request->customer_selection ?? 'all',
                'usage_limit_total' => $request->has('limit_usage_total') ? $request->usage_limit_total : null,
                'usage_limit_per_customer' => $request->has('usage_limit_per_customer'),
                'combinations' => $request->combinations,
                'is_featured' => $request->has('is_featured'),
            ];

            $type = $discount->type ?? 'amount_off_products';

            // Common fields for simple discounts
            if (in_array($type, ['amount_off_products', 'amount_off_order'])) {
                $data['value_type'] = $request->value_type;
                $data['value'] = $request->value;

                $minReqValue = null;
                if ($request->min_requirement_type == 'amount') {
                    $minReqValue = $request->min_requirement_value;
                } elseif ($request->min_requirement_type == 'quantity') {
                    $minReqValue = $request->min_qty_value;
                }
                $data['min_requirement_type'] = $request->min_requirement_type;
                $data['min_requirement_value'] = $minReqValue;
            }

            // Buy X Get Y specific
            if ($type == 'buy_x_get_y') {
                $data['buy_type'] = $request->buy_type;
                $data['buy_value'] = $request->buy_value;

                // Process Buy Items
                $discount->items()->delete();

                if ($request->has('buy_items')) {
                     $buyCollections = [];
                     $buyProducts = [];
                     $buyVariants = [];

                     foreach ($request->buy_items as $index => $id) {
                         $itemType = $request->buy_target_types[$index];
                         if ($itemType == 'collection') $buyCollections[] = $id;
                         elseif ($itemType == 'product') $buyProducts[] = $id;
                         elseif ($itemType == 'variant') {
                             $v = \App\Models\Variant::find($id);
                             if ($v) $buyVariants[$v->product_id][] = $id;
                         }
                     }
                     
                     foreach (array_unique($buyCollections) as $cid) {
                         \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'collection_id' => $cid]);
                     }
                     foreach (array_unique($buyProducts) as $pid) {
                         \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'product_id' => $pid, 'variant_ids' => null]);
                     }
                     foreach ($buyVariants as $pid => $vids) {
                         if (!in_array($pid, $buyProducts)) {
                             \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'product_id' => $pid, 'variant_ids' => array_unique($vids)]);
                         }
                     }
                }

                $data['get_quantity'] = $request->get_quantity;
                $data['get_type'] = $request->get_type;
                $data['get_value'] = ($request->get_type == 'fixed_amount') ? $request->get_value_fixed : $request->get_value;
                
                // Process Get Items
                $discount->rewardItems()->delete();

                if ($request->has('get_items')) {
                     $getCollections = [];
                     $getProducts = [];
                     $getVariants = [];

                     foreach ($request->get_items as $index => $id) {
                         $itemType = $request->get_target_types[$index];
                         if ($itemType == 'collection') $getCollections[] = $id;
                         elseif ($itemType == 'product') $getProducts[] = $id;
                         elseif ($itemType == 'variant') {
                             $v = \App\Models\Variant::find($id);
                             if ($v) $getVariants[$v->product_id][] = $id;
                         }
                     }
                     
                     foreach (array_unique($getCollections) as $cid) {
                         \App\Models\DiscountRewardItem::create(['discount_id' => $discount->id, 'collection_id' => $cid]);
                     }
                     foreach (array_unique($getProducts) as $pid) {
                         \App\Models\DiscountRewardItem::create(['discount_id' => $discount->id, 'product_id' => $pid, 'variant_ids' => null]);
                     }
                     foreach ($getVariants as $pid => $vids) {
                         if (!in_array($pid, $getProducts)) {
                             \App\Models\DiscountRewardItem::create(['discount_id' => $discount->id, 'product_id' => $pid, 'variant_ids' => array_unique($vids)]);
                         }
                     }
                }

                $data['max_uses_per_order'] = $request->has('limit_uses_per_order') ? $request->max_uses_per_order : null;
            }

            // Free Shipping specific
            if ($type == 'free_shipping') {
                $data['countries'] = $request->countries ?? 'all';
                $data['selected_countries'] = ($data['countries'] == 'selected') ? $request->selected_countries : null;
                $data['exclude_shipping_over'] = $request->has('exclude_shipping_rates') ? $request->exclude_shipping_over : null;

                if ($request->min_requirement_type == 'amount') {
                    $data['min_requirement_type'] = 'amount';
                    $data['min_requirement_value'] = $request->min_requirement_value;
                } else {
                    $data['min_requirement_type'] = 'none';
                    $data['min_requirement_value'] = null;
                }
            }

            $discount->update($data);

            // Sync Specific Customers
            if ($request->customer_selection == 'specific' && $request->has('customer_ids')) {
                $discount->customers()->sync($request->customer_ids);
            } else {
                $discount->customers()->detach();
            }

            // Sync Targets (for amount_off_products)
            if ($type == 'amount_off_products' && $request->has('targets')) {
                $discount->items()->delete();

                $collections = [];
                $products = [];
                $variantsByProduct = [];

                foreach ($request->targets as $index => $targetId) {
                    $targetType = $request->target_types[$index];

                    if ($targetType == 'collection') {
                        $collections[] = $targetId;
                    } elseif ($targetType == 'product') {
                        $products[] = $targetId;
                    } elseif ($targetType == 'variant') {
                        $variant = \App\Models\Variant::find($targetId);
                        if ($variant) {
                            $variantsByProduct[$variant->product_id][] = $targetId;
                        }
                    }
                }

                foreach (array_unique($collections) as $collectionId) {
                    \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'collection_id' => $collectionId]);
                }
                foreach (array_unique($products) as $productId) {
                    \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'product_id' => $productId, 'variant_ids' => null]);
                }
                foreach ($variantsByProduct as $productId => $variantIds) {
                    if (!in_array($productId, $products)) {
                        \App\Models\DiscountItem::create(['discount_id' => $discount->id, 'product_id' => $productId, 'variant_ids' => array_unique($variantIds)]);
                    }
                }
            }

            return redirect()->route('admin.discounts.index')->with('success', 'Discount updated successfully.');
        });
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'Discount deleted successfully.');
    }
}
