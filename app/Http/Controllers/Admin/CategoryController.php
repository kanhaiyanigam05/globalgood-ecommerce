<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\ComponentAttributeBag;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::with([])->withCount(['children', 'products'])->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->editColumn('title', fn ($row) => ucwords($row->title ?? '-'))
                ->editColumn('image', function ($row) {
                    $src = $row->image ? asset("uploads/{$row->image}") : "https://placehold.co/100x100?text={$row->title}"; // Fallback image?

                    return '<img src="'.$src.'" class="img-thumbnail" width="50">';
                })
                ->editColumn('products_count', fn ($row) => $row->products_count)
                ->editColumn('children_count', fn ($row) => $row->children_count)
                ->editColumn('status', function ($row) {
                    $statusHtml = view('components.forms.switch', [
                        'name' => 'status',
                        'id' => "status-{$row->id}",
                        'value' => $row->status,
                        'checked' => $row->status,
                        'class' => 'toggle-status',
                        'attributes' => new ComponentAttributeBag([
                            'data-id' => Crypt::encryptString($row->id),
                        ]),
                    ])->render();

                    return $statusHtml;
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('admin.categories.edit', $encryptedId);
                    $deleteUrl = route('admin.categories.destroy', $encryptedId);

                    $deleteForm = view('components.forms.form', [
                        'action' => $deleteUrl,
                        'method' => 'DELETE',
                        'type' => 'delete',
                        'varient' => 'reactive',
                        'confirm' => true,
                        'confirm-title' => 'Delete Category',
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
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);

        }

        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id') // Only get root categories
            ->orderBy('title')
            ->get();

        $categories = formatCategoriesRecursive($categories);

        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:categories,title',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Set default status if not provided
        $data['status'] = $request->has('status') ? 1 : 0;

        // Handle image upload (will be processed by model mutator)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $category = Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail(Crypt::decryptString($id));

        $categories = Category::with('children')
            ->whereNull('parent_id') // Only get root categories
            ->orderBy('title')
            ->get();

        $categories = formatCategoriesRecursive($categories);

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail(Crypt::decryptString($id));

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', Rule::unique('categories', 'title')->ignore($category->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:'.$category->id],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Set status
        $data['status'] = $request->has('status') ? 1 : 0;

        // Handle image upload (will be processed by model mutator)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        } elseif (! $request->has('keep_image')) {
            // If no new image and not keeping old one, remove it
            unset($data['image']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail(Crypt::decryptString($id));

        // Check if category has children
        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with subcategories. Please delete or reassign subcategories first.');
        }

        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with products. Please delete or reassign products first.');
        }

        // Image will be automatically deleted by model event
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Status update from the category
     */
    public function status(string $id)
    {
        $category = Category::findOrFail(Crypt::decryptString($id));

        $category->status = ! $category->status;
        $category->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'status' => $category->status,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Status updated successfully.');
    }

    public function fetchHierarchicalData(Request $request)
    {
        $action = $request->input('action', 'children');
        $parentId = $request->input('parent_id');

        if ($action === 'search') {
            $query = $request->input('query');
            $results = Category::where('title', 'like', "%$query%")
                ->limit(20)
                ->get()
                ->map(fn (Category $cat) => [
                    'id' => $cat->id,
                    'name' => $cat->title,
                    'has_children' => $cat->hasChildren(),
                ]);

            return response()->json(['data' => $results]);
        }

        if ($action === 'descendants') {
            $ids = $this->getAllChildIds($parentId);

            return response()->json(['data' => $ids]);
        }

        // Default: children
        $children = Category::where('parent_id', $parentId)
            ->get()
            ->map(fn (Category $cat) => [
                'id' => $cat->id,
                'name' => $cat->title,
                'children' => $cat->hasChildren() ? [] : null,
            ]);

        return response()->json(['data' => $children]);
    }

    private function getAllChildIds($parentId)
    {
        $ids = [];
        $childrenIds = Category::where('parent_id', $parentId)->pluck('id')->toArray();

        foreach ($childrenIds as $childId) {
            $ids[] = $childId;
            $ids = array_merge($ids, $this->getAllChildIds($childId));
        }

        return $ids;
    }
}
