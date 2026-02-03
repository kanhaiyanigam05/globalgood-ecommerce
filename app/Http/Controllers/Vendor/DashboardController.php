<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorUpdateNotification;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $vendor = Auth::guard('vendor')->user();
        $vendor->load('profile', 'documents', 'bankAccounts');
       
        return view('vendor.dashboard', compact('vendor'));
    }

    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:GST,PAN,ID',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $vendor = Auth::guard('vendor')->user();

        if ($request->hasFile('document_file')) {
            $path = ImageHelper::store($request->file('document_file'), 'vendor-documents');
            
            $document = VendorDocument::where('vendor_id', $vendor->id)
                ->where('document_type', $request->document_type)
                ->first();

            if ($document) {
                // Update existing document
                $document->update([
                    'document_file' => $path,
                    'status' => 'pending',
                    'verified_at' => null,
                ]);
            } else {
                // Create new document
                VendorDocument::create([
                    'vendor_id' => $vendor->id,
                    'document_type' => $request->document_type,
                    'document_file' => $path,
                    'status' => 'pending',
                ]);
            }

            // Trigger email to admin
            Mail::to('leselaf116@aixind.com')->send(new VendorUpdateNotification($vendor, 'Verification Documents'));

            return back()->with('success', 'Document uploaded successfully.');
        }

        return back()->with('error', 'File upload failed.');
    }
    public function updateBankDetails(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:20',
            'account_holder_name' => 'required|string|max:255',
        ]);

        $vendor = Auth::guard('vendor')->user();
        
        // Check if primary bank account exists, update it, or create new
        $bankAccount = $vendor->bankAccounts()->where('is_primary', true)->first();

        if ($bankAccount) {
            $bankAccount->update([
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                'account_holder_name' => $request->account_holder_name,
                'status' => 'pending', // Reset status to pending on update
            ]);
        } else {
            $vendor->bankAccounts()->create([
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                'account_holder_name' => $request->account_holder_name,
                'is_primary' => true,
                'status' => 'pending',
            ]);
        }

        // Trigger email to admin
        Mail::to('leselaf116@aixind.com')->send(new VendorUpdateNotification($vendor, 'Bank Account Details'));

        return back()->with('success', 'Bank details updated successfully.');
    }
}
