<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $collections = Collection::query();

            return DataTables::of($collections)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    $src = $row->image ? asset('storage/' . $row->image) : "https://placehold.co/100x100?text={$row->title}";
                    return '<img src="'.$src.'" class="img-thumbnail" width="50">';
                })
                ->addColumn('products_count', fn ($row) => $row->products()->count())
                ->addColumn('type', function ($row) {
                    $badge = $row->type === 'smart' ? 'info' : 'secondary';
                    return '<span class="badge bg-'.$badge.'">'.ucfirst($row->type).'</span>';
                })
                ->addColumn('status', function ($row) {
                    return $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encryptString($row->id);
                    $editUrl = route('admin.collections.edit', $encryptedId);
                    $deleteUrl = route('admin.collections.destroy', $encryptedId);

                    return '<div class="d-flex gap-2">
                        <a href="'.$editUrl.'" class="btn btn-light-warning icon-btn b-r-4"><i class="far fa-edit text-warning"></i></a>
                        <form action="'.$deleteUrl.'" method="POST" class="d-inline border-0 p-0 m-0 bg-transparent">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-light-danger icon-btn b-r-4 delete-confirm"><i class="far fa-trash-alt text-danger"></i></button>
                        </form>
                    </div>';
                })
                ->rawColumns(['image', 'type', 'status', 'action'])
                ->make(true);
        }

        return view('admin.collections.index');
    }

    public function create()
    {
        return view('admin.collections.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:manual,smart',
            'condition_type' => 'required_if:type,smart|nullable|in:all,any',
            'image' => 'nullable|image|max:2048',
            'conditions' => 'required_if:type,smart|array',
            'conditions.*.field' => 'required_with:conditions|string',
            'conditions.*.operator' => 'required_with:conditions|string',
            'conditions.*.value' => 'required_unless:conditions.*.operator,is_empty,is_not_empty|nullable',
            'product_ids' => 'required_if:type,manual|array',
            'product_ids.*' => 'exists:products,id',
        ], [
            'conditions.required_if' => 'Please add at least one condition for the smart collection.',
            'product_ids.required_if' => 'Please select at least one product for the manual collection.',
            'conditions.*.value.required_unless' => 'The value field is required for the selected operator.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return DB::transaction(function () use ($request) {
            $data = $request->only(['title', 'description', 'type', 'condition_type', 'status']);
            $data['status'] = $request->has('status') ? 1 : 0;
            
            if ($request->hasFile('image')) {
                $data['image'] = ImageHelper::store($request->file('image'), 'collections');
            }

            $collection = Collection::create($data);

            if ($collection->type === 'smart' && $request->has('conditions')) {
                foreach ($request->conditions as $cond) {
                    if (!empty($cond['field']) && !empty($cond['operator'])) {
                        $collection->conditions()->create($cond);
                    }
                }
            }

            if ($collection->type === 'manual' && $request->has('product_ids')) {
                $collection->products()->attach($request->product_ids);
            }

            $collection->syncSmartCollectionProducts($collection);

            return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully');
        });
    }

    public function edit($id)
    {
        $collection = Collection::with(['products', 'conditions'])->findOrFail(Crypt::decryptString($id));
        return view('admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, $id)
    {
        $collection = Collection::findOrFail(Crypt::decryptString($id));

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:manual,smart',
            'condition_type' => 'required_if:type,smart|nullable|in:all,any',
            'image' => 'nullable|image|max:2048',
            'conditions' => 'required_if:type,smart|array',
            'conditions.*.field' => 'required_with:conditions|string',
            'conditions.*.operator' => 'required_with:conditions|string',
            'conditions.*.value' => 'required_unless:conditions.*.operator,is_empty,is_not_empty|nullable',
            'product_ids' => 'required_if:type,manual|array',
            'product_ids.*' => 'exists:products,id',
        ], [
            'conditions.required_if' => 'Please add at least one condition for the smart collection.',
            'product_ids.required_if' => 'Please select at least one product for the manual collection.',
            'conditions.*.value.required_unless' => 'The value field is required for the selected operator.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return DB::transaction(function () use ($request, $collection) {
            $data = $request->only(['title', 'description', 'type', 'condition_type', 'status']);
            $data['status'] = $request->has('status') ? 1 : 0;

            if ($request->hasFile('image')) {
                $data['image'] = ImageHelper::store($request->file('image'), 'collections', $collection->image);
            }

            $collection->update($data);

            if ($collection->type === 'smart') {
                $collection->conditions()->delete();
                if ($request->has('conditions')) {
                    foreach ($request->conditions as $cond) {
                        if (!empty($cond['field']) && !empty($cond['operator'])) {
                            $collection->conditions()->create($cond);
                        }
                    }
                }
            } else {
                if ($request->has('product_ids')) {
                    $collection->products()->sync($request->product_ids);
                }
            }

            $collection->syncSmartCollectionProducts($collection);

            return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully');
        });
    }

    public function destroy($id)
    {
        $collection = Collection::findOrFail(Crypt::decryptString($id));
        $collection->delete();
        return redirect()->route('admin.collections.index')->with('success', 'Collection deleted successfully');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        $products = Product::where('title', 'like', "%$query%")
            ->orWhere('slug', 'like', "%$query%")
            ->limit(10)
            ->get(['id', 'title', 'price']);

        return response()->json($products);
    }
}
