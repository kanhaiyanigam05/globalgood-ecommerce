<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorDocument;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::with('profile')->latest()->paginate(10);
        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vendor = Vendor::with(['profile', 'documents', 'bankAccounts', 'products'])->findOrFail($id);
        
        $stats = [
            'pending_products' => $vendor->products->where('is_approved', false)->where('status', true)->count(),
            'approved_products' => $vendor->products->where('is_approved', true)->count(),
            'rejected_products' => $vendor->products->where('is_approved', false)->where('status', false)->count(),
            'total_orders' => 0, // Placeholder
            'total_earnings' => 0, // Placeholder
            'total_refunds' => 0, // Placeholder
        ];

        return view('admin.vendors.show', compact('vendor', 'stats'));
    }

    /**
     * Update the specified vendor status.
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,suspended,terminated',
            'kyc_status' => 'nullable|in:pending,verified,rejected',
        ]);

        $vendor = Vendor::findOrFail($id);
        
        $vendor->update([
            'status' => $request->status,
            'kyc_status' => $request->kyc_status ?? $vendor->kyc_status,
        ]);

        return back()->with('success', 'Vendor status updated successfully.');
    }

    public function verifyDocument(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $document = VendorDocument::findOrFail($id);
        
        $document->update([
            'status' => $request->status,
            'verified_at' => $request->status === 'verified' ? now() : null,
        ]);

        return back()->with('success', 'Document status updated successfully.');
    }
    public function verifyBank(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $bankAccount = \App\Models\VendorBankAccount::findOrFail($id);
        
        $bankAccount->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Bank account status updated successfully.');
    }
}
