<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Country;
use App\Models\CountryZone;
use App\Models\Customer;
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
                ->editColumn('full_name', fn ($row) => $row->full_name)
                ->editColumn('email', fn ($row) => $row->email)
                ->addColumn('orders', fn ($row) => $row->total_orders)
                ->addColumn('spent', fn ($row) => '₹'.number_format($row->total_spent, 2))
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
        $countries = Country::all();

        return view('admin.customers.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'language' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'tel' => 'nullable|string|max:10',
            'tax_setting' => 'nullable|string',
        ];

        if ($request->has('address')) {
            $rules = array_merge($rules, [
                'address.first_name' => 'required|string|max:255',
                'address.last_name' => 'required|string|max:255',
                'address.address1' => 'required|string|max:255',
                'address.city' => 'required|string|max:255',
                'address.country' => 'required|string|max:255',
                'address.zip' => 'required|string|max:20',
                'address.phone' => 'nullable|string|max:20',
                'address.company' => 'nullable|string|max:255',
                'address.address2' => 'nullable|string|max:255',
                'address.province' => 'nullable|string|max:255',
            ]);
        }

        if ($request->has('phone_code')) {
            $request->merge(['tel' => $request->phone_code]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Customer::create($request->all());

        if ($request->has('address')) {
            $addressData = $request->input('address');
            $addressData['is_default'] = true;
            // Ensure tel is set if passed in address array (JS needs to handle this)
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
        $countries = Country::all();

        return view('admin.customers.edit', compact('customer', 'countries'));
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail(Crypt::decryptString($id));

        if ($request->has('phone_code')) {
            $request->merge(['tel' => $request->phone_code]);
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
            'language' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'tax_setting' => 'nullable|string',
        ];

        if ($request->has('address')) {
            $rules = array_merge($rules, [
                'address.first_name' => 'required|string|max:255',
                'address.last_name' => 'required|string|max:255',
                'address.address1' => 'required|string|max:255',
                'address.city' => 'required|string|max:255',
                'address.country' => 'required|string|max:255',
                'address.zip' => 'required|string|max:20',
                'address.phone' => 'nullable|string|max:20',
                'address.company' => 'nullable|string|max:255',
                'address.address2' => 'nullable|string|max:255',
                'address.province' => 'nullable|string|max:255']);
        }

        $validator = Validator::make($request->all(), $rules);

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

    public function getZones(Request $request)
    {
        $request->validate([
            'country_id' => 'required',
        ]);

        $zones = CountryZone::where('country_id', $request->country_id)->get();

        return response()->json($zones);
    }
}
