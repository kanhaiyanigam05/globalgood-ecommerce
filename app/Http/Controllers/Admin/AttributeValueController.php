<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $values = AttributeValue::with(['attribute'])->get();

            return DataTables::of($values)
                ->addIndexColumn()
                ->editColumn('attribute.name', fn ($row) => ucwords($row->attribute->name ?? '-'))
                ->editColumn('value', fn ($row) => ucwords($row->value ?? '-'))
                ->editColumn('code', fn ($row) => $row->code ?? '-')
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('admin.attribute-values.edit', $encryptedId);
                    $deleteUrl = route('admin.attribute-values.destroy', $encryptedId);

                    $deleteForm = view('components.forms.form', [
                        'action' => $deleteUrl,
                        'method' => 'DELETE',
                        'type' => 'delete',
                        'varient' => 'reactive',
                        'confirm' => true,
                        'confirm-title' => 'Delete Attribute Value',
                        'confirm-message' => 'This action cannot be undone',
                        'slot' => new HtmlString(
                            '<button class="btn btn-light-danger icon-btn b-r-4" type="submit">
                                <i class="far fa-trash-alt text-danger"></i>
                            </button>'
                        ),
                    ])->render();

                    // Actions
                    $actions = '<div class="d-flex gap-2">';
                    $actions .= "<a href=\"$editUrl\" class=\"btn btn-light-warning icon-btn b-r-4\" type=\"button\"><i class=\"far fa-edit text-warning\"></i></a>";
                    $actions .= $deleteForm;
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.attribute-values.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = Attribute::pluck('name', 'id');

        return view('admin.attribute-values.create', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        AttributeValue::create($request->all());

        return redirect()->route('admin.attribute-values.index')
            ->with('success', 'Attribute value created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attributeValue = AttributeValue::findOrFail(Crypt::decryptString($id));
        $attributes = Attribute::pluck('name', 'id');

        return view('admin.attribute-values.edit', compact('attributeValue', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attributeValue = AttributeValue::findOrFail(Crypt::decryptString($id));

        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $attributeValue->update($request->all());

        return redirect()->route('admin.attribute-values.index')
            ->with('success', 'Attribute value updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attributeValue = AttributeValue::findOrFail(Crypt::decryptString($id));
        $attributeValue->delete();

        return redirect()->route('admin.attribute-values.index')
            ->with('success', 'Attribute value deleted successfully.');
    }
}
