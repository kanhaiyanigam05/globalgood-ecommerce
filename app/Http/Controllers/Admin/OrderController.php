<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::with('customer')->latest();
            return DataTables::of($query)
                ->addColumn('customer_name', function ($order) {
                    return $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : ($order->email ?: 'Guest');
                })
                ->editColumn('status', function ($order) {
                    $badges = [
                        'draft' => 'secondary',
                        'pending' => 'warning',
                        'paid' => 'success',
                        'partially_paid' => 'info',
                        'refunded' => 'danger',
                        'voided' => 'dark',
                    ];
                    $color = $badges[$order->status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($order->status) . '</span>';
                })
                ->editColumn('total', function ($order) {
                    return $order->currency . ' ' . $order->formatted_total;
                })
                ->addColumn('action', function ($order) {
                    return '<div class="btn-group">
                        <a href="' . route('admin.orders.show', $order->id) . '" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-order" data-id="' . $order->id . '"><i class="fas fa-trash"></i></button>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('admin.orders.index');
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                // Price comes in as decimal (e.g. 100.00)
                // Model Mutator will handle multiplication by 100
                $price = (float) $item['price'];
                $quantity = (int) $item['quantity'];
                $lineTotal = $price * $quantity;
                
                $product = Product::find($item['product_id']);
                $variant = isset($item['variant_id']) ? Variant::find($item['variant_id']) : null;
                $sku = $variant ? $variant->sku : $product->sku;
                $name = $product->name; // Can be customized later
                
                $itemsData[] = [
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'name' => $name,
                    'sku' => $sku,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $lineTotal,
                    'weight' => 0, // Default for now
                    'tax_amount' => 0, // Placeholder
                    'discount_amount' => 0, // Placeholder
                ];

                $subtotal += $lineTotal;
            }

            // Simple tax calculation (e.g. flat 18% inclusive or exclusive, for now keeping 0 as per logic)
            // But user screenshot says "IGST 18% (Included)"
            // Let's assume the price is inclusive for now as per common e-com logic in India
            // $taxAmount = $subtotal * 0.18; 
            $taxAmount = 0; // Keeping 0 for now as per DB defaults usually needing explicit tax logic
            $shippingAmount = 0;
            $discountAmount = 0;
            $total = $subtotal + $shippingAmount - $discountAmount;

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'email' => $request->customer_id ? Customer::find($request->customer_id)->email : null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'status' => 'pending', // Default status
                'payment_gateway' => 'manual',
                'notes' => $request->notes,
                'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
            ]);

            foreach ($itemsData as $data) {
                $order->items()->create($data);
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating order: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'customer']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'customer']);
        return view('admin.orders.edit', compact('order'));
    }

    // API methods for live search
    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        $products = Product::with('variants')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('sku', 'LIKE', "%{$query}%")
            ->get();
            
        return response()->json($products);
    }

    public function searchCustomers(Request $request)
    {
        $query = $request->get('q');
        $customers = Customer::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->get();
            
        return response()->json($customers);
    }
}
