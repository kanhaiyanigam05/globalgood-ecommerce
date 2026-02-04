<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::query();

            return DataTables::of($customers)
                ->addIndexColumn()
                ->editColumn('full_name', fn($row) => $row->full_name)
                ->editColumn('email', fn($row) => $row->email)
                ->addColumn('orders', fn($row) => $row->total_orders)
                ->addColumn('spent', fn($row) => '$' . number_format($row->total_spent, 2))
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $showUrl = route('admin.customers.show', $encryptedId);
                    $editUrl = route('admin.customers.edit', $encryptedId);
                    $deleteUrl = route('admin.customers.destroy', $encryptedId);

                    $deleteForm = view('components.forms.form', [
                        'action' => $deleteUrl,
                        'method' => 'DELETE',
                        'type' => 'delete',
                        'varient' => 'reactive',
                        'confirm' => true,
                        'confirm-title' => 'Delete Customer',
                        'confirm-message' => 'Are you sure you want to delete this customer?',
                        'slot' => new HtmlString(
                            '<button class="btn btn-light-danger icon-btn b-r-4" type="submit">
                                <i class="far fa-trash-alt text-danger"></i>
                            </button>'
                        ),
                    ])->render();

                    $actions = '<div class="d-flex gap-2">';
                    $actions .= "<a href=\"$showUrl\" class=\"btn btn-light-primary icon-btn b-r-4\"><i class=\"far fa-eye text-primary\"></i></a>";
                    $actions .= "<a href=\"$editUrl\" class=\"btn btn-light-warning icon-btn b-r-4\"><i class=\"far fa-edit text-warning\"></i></a>";
                    $actions .= $deleteForm;
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.customers.index');
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'language' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'tax_setting' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Customer::create($request->all());

        if ($request->has('address')) {
            $addressData = $request->input('address');
            $addressData['is_default'] = true;
            $customer->addresses()->create($addressData);
        }

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(string $id)
    {
        $customer = Customer::with(['addresses'])->findOrFail(Crypt::decryptString($id));
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(string $id)
    {
        $customer = Customer::findOrFail(Crypt::decryptString($id));
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail(Crypt::decryptString($id));

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'language' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'tax_setting' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer->update($request->all());

        if ($request->has('address')) {
            $addressData = $request->input('address');
            if (isset($addressData['id'])) {
                $address = Address::findOrFail($addressData['id']);
                $address->update($addressData);
            } else {
                $addressData['is_default'] = true;
                $customer->addresses()->create($addressData);
            }
        }

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail(Crypt::decryptString($id));
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
