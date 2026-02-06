<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\TaxSetting;
use App\Models\Variant; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::with('customer')->latest();

            return DataTables::of($query)
                ->addColumn('customer_name', function ($order) {
                    return $order->customer ? $order->customer->first_name.' '.$order->customer->last_name : ($order->email ?: 'Guest');
                })
                ->editColumn('order_status', function ($order) {
                    $badges = [
                        'open' => 'primary',
                        'archived' => 'secondary',
                        'canceled' => 'danger',
                    ];
                    $color = $badges[$order->order_status] ?? 'secondary';

                    return '<span class="badge bg-'.$color.'">'.ucfirst($order->order_status).'</span>';
                })
                ->editColumn('payment_status', function ($order) {
                    $badges = [
                        'pending' => 'warning',
                        'paid' => 'success',
                        'partially_paid' => 'info',
                        'refunded' => 'danger',
                        'voided' => 'dark',
                    ];
                    $color = $badges[$order->payment_status] ?? 'secondary';

                    return "<span class=\"badge bg-$color\">".ucfirst($order->payment_status).'</span>';
                })
                ->editColumn('fulfillment_status', function ($order) {
                    $badges = [
                        'unfulfilled' => 'info',
                        'fulfilled' => 'success',
                        'partially_fulfilled' => 'warning',
                        'restocked' => 'secondary',
                    ];
                    $color = $badges[$order->fulfillment_status] ?? 'secondary';

                    return '<span class="badge bg-'.$color.'">'.ucfirst($order->fulfillment_status ?? 'Unfulfilled').'</span>';
                })
                ->editColumn('total', function ($order) {
                    return $order->currency.' '.$order->formatted_total;
                })
                ->addColumn('action', function ($order) {
                    return '<div class="btn-group">
                        <a href="'.route('admin.orders.show', $order->id).'" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-order" data-id="'.$order->id.'"><i class="fas fa-trash"></i></button>
                    </div>';
                })
                ->rawColumns(['order_status', 'payment_status', 'fulfillment_status', 'action'])
                ->make(true);
        }

        return view('admin.orders.index');
    }

    public function create()
    {
        $countries = Country::all();
        $discounts = Discount::active()->get(['id', 'code', 'title', 'value', 'value_type']);

        return view('admin.orders.create', compact('countries', 'discounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.name' => 'required_without:items.*.product_id|string|max:255',
            'shipping_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $price = (float) $item['price'];
                $quantity = (int) $item['quantity'];
                $lineTotal = $price * $quantity;

                if (! empty($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    $variant = isset($item['variant_id']) ? Variant::find($item['variant_id']) : null;
                    $sku = $variant ? $variant->sku : $product->sku;
                    $name = $product->title;
                    $isCustom = false;
                } else {
                    $name = $item['name'] ?? 'Custom item';
                    $sku = null;
                    $variant = null;
                    $isCustom = true;
                }

                $itemsData[] = [
                    'product_id' => $item['product_id'] ?? null,
                    'variant_id' => $item['variant_id'] ?? null,
                    'name' => $name,
                    'sku' => $sku,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $lineTotal,
                    'weight' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'is_custom' => $isCustom,
                ];

                $subtotal += $lineTotal;
            }

            $taxAmount = $request->input('tax_amount', 0);
            $shippingAmount = $request->input('shipping_amount', 0);
            $discountAmount = $request->input('discount_amount', 0);
            $total = $subtotal + $shippingAmount + $taxAmount - $discountAmount;

            // Resolve Addresses
            $shippingAddressData = null;
            if ($request->shipping_address_id) {
                $shippingAddressData = Customer::find($request->customer_id)->addresses()->find($request->shipping_address_id);
            } elseif ($request->customer_id) {
                $shippingAddressData = Customer::find($request->customer_id)->defaultAddress;
            }

            $billingAddressData = null;
            if ($request->billing_address_id) {
                $billingAddressData = Customer::find($request->customer_id)->addresses()->find($request->billing_address_id);
            } elseif ($request->has('billing_same_as_shipping') && $request->billing_same_as_shipping == '1') {
                $billingAddressData = $shippingAddressData;
            } else {
                // Fallback or explicit billing data? For now assume UI handles IDs or same-as-shipping
                $billingAddressData = $shippingAddressData;
            }

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'email' => $request->customer_id ? Customer::find($request->customer_id)->email : null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'order_status' => 'open',
                'payment_status' => 'pending',
                'fulfillment_status' => 'unfulfilled',
                'payment_gateway' => 'manual',
                'notes' => $request->notes,
                'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
                'shipping_address' => $shippingAddressData?->toArray(),
                'billing_address' => $billingAddressData ? ($billingAddressData instanceof \App\Models\Address ? $billingAddressData->toArray() : $billingAddressData) : ($shippingAddressData?->toArray()),
            ]);

            foreach ($itemsData as $data) {
                $order->items()->create($data);
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error creating order: '.$e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'customer']);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $countries = Country::all();
        $discounts = Discount::active()->get(['id', 'code', 'title', 'value', 'value_type']);

        $order->load(['items.product.images', 'items.variant', 'customer.addresses', 'customer.defaultAddress']);
        $order->items->each(function ($item) {
            $item->product->images->each(function ($image) {
                $image->setAttribute('thumb', $image->thumb(50, 50));
            });
        });

        return view('admin.orders.edit', compact('order', 'countries', 'discounts'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.name' => 'required_without:items.*.product_id|string|max:255',
            'shipping_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'order_status' => 'nullable|string|in:open,archived,canceled',
            'payment_status' => 'nullable|string|in:pending,paid,partially_paid,refunded,voided',
            'fulfillment_status' => 'nullable|string|in:unfulfilled,fulfilled,partially_fulfilled,restocked',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $lineTotal = $item['price'] * $item['quantity'];

                if (! empty($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    $variant = $item['variant_id'] ? \App\Models\Variant::find($item['variant_id']) : null;
                    $name = $product->title;
                    $sku = $variant ? $variant->sku : $product->sku;
                    $isCustom = false;
                } else {
                    $name = $item['name'] ?? 'Custom item';
                    $sku = null;
                    $isCustom = true;
                }

                $itemsData[] = [
                    'product_id' => $item['product_id'] ?? null,
                    'variant_id' => $item['variant_id'] ?? null,
                    'name' => $name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $lineTotal,
                    'sku' => $sku,
                    'is_custom' => $isCustom,
                ];

                $subtotal += $lineTotal;
            }

            $taxAmount = $request->input('tax_amount', 0);
            $shippingAmount = $request->input('shipping_amount', 0);
            $discountAmount = $request->input('discount_amount', 0);
            $total = $subtotal + $shippingAmount + $taxAmount - $discountAmount;

            // Resolve Addresses
            $shippingAddressData = null;
            if ($request->shipping_address_id) {
                $shippingAddressData = Customer::find($request->customer_id)->addresses()->find($request->shipping_address_id);
            } elseif ($request->customer_id) {
                $shippingAddressData = Customer::find($request->customer_id)->defaultAddress;
            }

            $billingAddressData = null;
            if ($request->billing_address_id) {
                $billingAddressData = Customer::find($request->customer_id)->addresses()->find($request->billing_address_id);
            } elseif ($request->has('billing_same_as_shipping') && $request->billing_same_as_shipping == '1') {
                $billingAddressData = $shippingAddressData;
            } else {
                $billingAddressData = $billingAddressData ?: $shippingAddressData;
            }

            $order->update([
                'customer_id' => $request->customer_id,
                'email' => $request->customer_id ? Customer::find($request->customer_id)->email : null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'notes' => $request->notes,
                'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
                'order_status' => $request->input('order_status', $order->order_status),
                'payment_status' => $request->input('payment_status', $order->payment_status),
                'fulfillment_status' => $request->input('fulfillment_status', $order->fulfillment_status),
                'shipping_address' => $shippingAddressData ? $shippingAddressData->toArray() : $order->shipping_address,
                'billing_address' => $billingAddressData ? ($billingAddressData instanceof \App\Models\Address ? $billingAddressData->toArray() : $billingAddressData) : $order->billing_address,
            ]);

            // Sync Items (Delete all and re-create)
            $order->items()->delete();
            foreach ($itemsData as $data) {
                $order->items()->create($data);
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error updating order: '.$e->getMessage())->withInput();
        }
    }

    // --- AJAX Methods ---

    public function searchProducts(Request $request)
{
    $query = $request->get('q');

    $products = Product::with(['variants.attributes', 'images'])
    ->where('title', 'LIKE', "%{$query}%")
    ->get()
    ->each(function ($product) {
        $product->images->each(function ($image) {
            $image->setAttribute('thumb', $image->thumb(50, 50));
        });
    });


    return response()->json($products);
}


    public function searchCustomers(Request $request)
    {
        $query = $request->get('q');

        $cQuery = Customer::with('defaultAddress');

        if ($query) {
            $cQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('phone', 'LIKE', "%{$query}%");
            });
        } else {
            $cQuery->latest()->limit(10);
        }

        $customers = $cQuery->get();

        return response()->json($customers);
    }

    public function storeCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address.first_name' => 'nullable|string|max:255',
            'address.last_name' => 'nullable|string|max:255',
            'address.address1' => 'nullable|string|max:255',
            'address.city' => 'nullable|string|max:255',
            'address.country' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $customer = Customer::create($request->only(['first_name', 'last_name', 'email', 'phone', 'language', 'tax_setting']));

            if ($request->has('address') && $request->input('address.address1')) {
                $addressData = $request->input('address');
                $addressData['is_default'] = true;
                $customer->addresses()->create($addressData);
            }

            DB::commit();

            $customer->load('defaultAddress');

            return response()->json($customer);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Error creating customer', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAddresses(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);

        return response()->json($customer->addresses()->orderBy('is_default', 'desc')->get());
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'country' => 'required',
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        // Create NEW address
        $address = $customer->addresses()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'city' => $request->city,
            'province' => $request->province,
            'country' => $request->country,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'tel' => $request->tel,
            'is_default' => $request->boolean('is_default', true),
        ]);

        // If we want it to be the new default, we should unset others.
        if ($request->boolean('is_default', true)) {
            $customer->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $address->is_default = true; // ensure object has it
        }

        return response()->json($address);
    }

    public function updateCustomerAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'address1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $customer = Customer::findOrFail($request->customer_id);

            $address = $customer->defaultAddress;

            $data = $request->except(['customer_id']);

            if ($address) {
                $address->update($data);
            } else {
                $data['is_default'] = true;
                $address = $customer->addresses()->create($data);
            }

            $customer->load('defaultAddress');

            return response()->json($customer);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating address', 'error' => $e->getMessage()], 500);
        }
    }

    public function fulfill(Order $order)
    {
        if ($order->fulfillment_status === 'fulfilled') {
            return back()->with('error', 'Order is already fulfilled.');
        }

        try {
            DB::beginTransaction();

            $order->update([
                'fulfillment_status' => 'fulfilled',
                'payment_status' => 'paid',
            ]);

            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = Variant::find($item->variant_id);
                    if ($variant) {
                        $variant->decrement('quantity', $item->quantity);
                    }
                } elseif ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('quantity', $item->quantity);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)->with('success', 'Order fulfilled successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error fulfilling order: '.$e->getMessage());
        }
    }
    public function getShippingRates(Request $request)
    {
        $customer = Customer::find($request->customer_id);
        if (!$customer) return response()->json([]);
        $addr = $request->address_id ? $customer->addresses()->find($request->address_id) : $customer->defaultAddress;
        if (!$addr) return response()->json([]);
        $country = Country::where('name', $addr->country)->first();
        if (!$country) return response()->json([]);
        return response()->json(DB::table('shipping_rates')->join('shipping_zones', 'shipping_rates.shipping_zone_id', '=', 'shipping_zones.id')->join('shipping_zone_countries', 'shipping_zones.id', '=', 'shipping_zone_countries.shipping_zone_id')->where('shipping_zone_countries.country_id', $country->id)->select('shipping_rates.*')->get());
    }

    public function calculateTax(Request $request)
    {
        $taxRate = 0;
        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            $addr = $request->address_id ? $customer->addresses()->find($request->address_id) : $customer->defaultAddress;
            if ($addr) {
                $country = Country::where('name', $addr->country)->first();
                if ($country) {
                    $setting = TaxSetting::where('country_id', $country->id)->where('is_active', true)->first();
                    if ($setting) $taxRate = $setting->tax_rate;
                }
            }
        }
        return response()->json(['tax_amount' => ($request->subtotal * $taxRate / 100), 'tax_rate' => $taxRate]);
    }

}
