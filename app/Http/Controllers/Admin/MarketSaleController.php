<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketSale;
use App\Models\MarketSaleItem;
use App\Models\Product;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarketSaleController extends Controller
{
    public function index()
    {
        $sales = MarketSale::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.market-sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::with('images')->get();
        $collections = Collection::all();
        return view('admin.market-sales.create', compact('products', 'collections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sale_type' => 'required|in:percentage,fixed',
            'sale_value' => 'required|numeric|min:0',
            'applied_on' => 'required|in:product,collection',
            'starts_at_date' => 'required|date',
            'starts_at_time' => 'required',
            'items' => 'required|array|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $starts_at = $request->starts_at_date . ' ' . $request->starts_at_time . ':00';
            $ends_at = $request->ends_at_date ? ($request->ends_at_date . ' ' . ($request->ends_at_time ? ($request->ends_at_time . ':00') : '23:59:59')) : null;

            $sale = MarketSale::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . Str::random(5),
                'sale_type' => $request->sale_type,
                'sale_value' => $request->sale_value,
                'applied_on' => $request->applied_on,
                'starts_at' => $starts_at,
                'ends_at' => $ends_at,
                'status' => $request->status ?? 'active',
            ]);

            foreach ($request->items as $itemId) {
                MarketSaleItem::create([
                    'market_sale_id' => $sale->id,
                    'product_id' => $request->applied_on == 'product' ? $itemId : null,
                    'collection_id' => $request->applied_on == 'collection' ? $itemId : null,
                ]);
            }

            return redirect()->route('admin.market-sales.index')->with('success', 'Market Sale created successfully.');
        });
    }

    public function edit(MarketSale $marketSale)
    {
        $marketSale->load('items');
        $products = Product::with('images')->get();
        $collections = Collection::all();
        
        $selectedItemIds = $marketSale->items->pluck($marketSale->applied_on == 'product' ? 'product_id' : 'collection_id')->toArray();
        
        return view('admin.market-sales.edit', compact('marketSale', 'products', 'collections', 'selectedItemIds'));
    }

    public function update(Request $request, MarketSale $marketSale)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sale_type' => 'required|in:percentage,fixed',
            'sale_value' => 'required|numeric|min:0',
            'applied_on' => 'required|in:product,collection',
            'starts_at_date' => 'required|date',
            'starts_at_time' => 'required',
            'items' => 'required|array|min:1',
        ]);

        return DB::transaction(function () use ($request, $marketSale) {
            $starts_at = $request->starts_at_date . ' ' . $request->starts_at_time . ':00';
            $ends_at = $request->ends_at_date ? ($request->ends_at_date . ' ' . ($request->ends_at_time ? ($request->ends_at_time . ':00') : '23:59:59')) : null;

            $marketSale->update([
                'title' => $request->title,
                'sale_type' => $request->sale_type,
                'sale_value' => $request->sale_value,
                'applied_on' => $request->applied_on,
                'starts_at' => $starts_at,
                'ends_at' => $ends_at,
                'status' => $request->status ?? 'active',
            ]);

            $marketSale->items()->delete();

            foreach ($request->items as $itemId) {
                MarketSaleItem::create([
                    'market_sale_id' => $marketSale->id,
                    'product_id' => $request->applied_on == 'product' ? $itemId : null,
                    'collection_id' => $request->applied_on == 'collection' ? $itemId : null,
                ]);
            }

            return redirect()->route('admin.market-sales.index')->with('success', 'Market Sale updated successfully.');
        });
    }

    public function status(string $id)
    {
        $marketSale = MarketSale::findOrFail(\Illuminate\Support\Facades\Crypt::decryptString($id));
        $marketSale->update(['status' => $marketSale->status == 'active' ? 'inactive' : 'active']);
        return response()->json(['success' => true]);
    }

    public function destroy(MarketSale $marketSale)
    {
        $marketSale->delete();
        return redirect()->route('admin.market-sales.index')->with('success', 'Market Sale deleted successfully.');
    }
}
