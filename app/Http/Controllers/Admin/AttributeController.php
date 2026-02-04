<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Enums\Scope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $attributes = Attribute::with([])->withCount(['values'])->get();

            return DataTables::of($attributes)
                ->addIndexColumn()
                ->editColumn('name', fn ($row) => ucwords($row->name ?? '-'))
                ->editColumn('scope', fn ($row) => ucfirst($row->scope->value))
                ->editColumn('values_count', fn ($row) => $row->values_count)
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('admin.attributes.edit', $encryptedId);
                    $deleteUrl = route('admin.attributes.destroy', $encryptedId);

                    $deleteForm = view('components.forms.form', [
                        'action' => $deleteUrl,
                        'method' => 'DELETE',
                        'type' => 'delete',
                        'varient' => 'reactive',
                        'confirm' => true,
                        'confirm-title' => 'Delete Attribute',
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

        return view('admin.attributes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $scopes = [
            Scope::PRODUCT->value => 'Product',
            Scope::VARIANT->value => 'Variant',
        ];

        $categories = Category::all()->pluck('title', 'id');

        return view('admin.attributes.create', compact('scopes', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:attributes,name',
            'scope' => ['required', Rule::in([Scope::PRODUCT->value, Scope::VARIANT->value])],
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $attribute = Attribute::create($request->except('categories'));

        if ($request->has('categories')) {
            $attribute->categories()->sync($request->categories);
        }

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attribute = Attribute::findOrFail(Crypt::decryptString($id));

        $scopes = [
            Scope::PRODUCT->value => 'Product',
            Scope::VARIANT->value => 'Variant',
        ];

        $categories = Category::all()->pluck('title', 'id');
        $selectedCategories = $attribute->categories()->pluck('categories.id')->toArray();

        return view('admin.attributes.edit', compact('attribute', 'scopes', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attribute = Attribute::findOrFail(Crypt::decryptString($id));

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('attributes', 'name')->ignore($attribute->id)],
            'scope' => ['required', Rule::in([Scope::PRODUCT->value, Scope::VARIANT->value])],
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $attribute->update($request->except('categories'));

        if ($request->has('categories')) {
            $attribute->categories()->sync($request->categories);
        } else {
            $attribute->categories()->detach();
        }

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = Attribute::findOrFail(Crypt::decryptString($id));

        // Check if attribute has values
        if ($attribute->values()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete attribute with values. Please delete values first.');
        }

        $attribute->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute deleted successfully.');
    }

    /**
     * Get attributes by category
     */
    public function getAttributesByCategory(Request $request) 
    {
        $categoryId = $request->input('category_id');
        $scope = $request->input('scope');

        $query = Attribute::query();

        if ($scope) {
            $query->where('scope', $scope);
        }

        if ($categoryId) {
            $query->where(function($q) use ($categoryId) {
                // Attributes assigned to this category
                $q->whereHas('categories', function($q2) use ($categoryId) {
                    $q2->where('categories.id', $categoryId);
                })
                // OR attributes not assigned to any category (global)
                ->orWhereDoesntHave('categories');
            });
        }
        
        $attributes = $query->with('values')->get();

        return response()->json([
            'success' => true,
            'attributes' => $attributes
        ]);
    }
}
