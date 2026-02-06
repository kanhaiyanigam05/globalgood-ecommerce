<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Product;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'handle' => 'nullable|string|max:255|unique:menus,handle',
        ]);

        $menu = Menu::create([
            'name' => $request->name,
            'handle' => $request->handle ?? Str::slug($request->name),
            'status' => true,
        ]);

        return redirect()->route('admin.menus.edit', $menu->id)->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        // Load all items once, ordered globally
        $items = $menu->items()
            ->orderBy('parent_id')
            ->orderBy('order')
            ->get();

        // Build ordered tree
        $tree = $this->buildTree($items);

        return view('admin.menus.edit', compact('menu', 'tree'));
    }

    protected function buildTree($items, $parentId = null)
    {
        return $items
            ->where('parent_id', $parentId)
            ->values()
            ->map(function ($item) use ($items) {
                $item->setRelation(
                    'children',
                    $this->buildTree($items, $item->id)
                );
                return $item;
            });
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'handle' => 'required|string|max:255|unique:menus,handle,' . $menu->id,
        ]);

        $menuStructure = json_decode($request->input('menu_structure'), true);

        if (!is_array($menuStructure)) {
            return back()->withErrors(['items' => 'Invalid menu structure']);
        }

        // Validate items recursively
        $errors = $this->validateItems($menuStructure);
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $menu->update([
            'name' => $request->name,
            'handle' => $request->handle,
        ]);

        // ✅ FIX: Delete children first, then parents (to avoid foreign key constraints)
        $this->deleteMenuItemsCascading($menu);

        $this->syncItems($menu, $menuStructure);

        return redirect()->route('admin.menus.edit', $menu->id)->with('success', 'Menu updated successfully.');
    }

    /**
     * Delete menu items in the correct order (children first, then parents)
     */
    protected function deleteMenuItemsCascading(Menu $menu)
    {
        // Get all items with their depth
        $items = $menu->items()->get();
        
        // Delete children first by ordering by id DESC (assuming newer items are children)
        // OR use a recursive delete function
        
        // Simple approach: Delete all children first (where parent_id is not null)
        MenuItem::where('menu_id', $menu->id)
            ->whereNotNull('parent_id')
            ->delete();
        
        // Then delete parents (where parent_id is null)
        MenuItem::where('menu_id', $menu->id)
            ->whereNull('parent_id')
            ->delete();
    }

    protected function validateItems(array $items, $prefix = '')
    {
        $errors = [];
        foreach ($items as $index => $item) {
            $key = $prefix . ($prefix ? '.' : '') . ($item['label'] ?? "Item #".($index + 1));

            if (empty($item['label'])) {
                $errors[] = "The label field is required for item at position " . ($index + 1) . ($prefix ? " in $prefix" : "") . ".";
            }

            if (empty($item['url']) && empty($item['linkable_id'])) {
                $errors[] = "A link (URL or Model) is required for '{$item['label']}'.";
            }

            if (!empty($item['children'])) {
                $childErrors = $this->validateItems($item['children'], $item['label']);
                $errors = array_merge($errors, $childErrors);
            }
        }
        return $errors;
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }

    public function searchLinkables(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type');
        $results = [];

        // If no query but type is provided, return all for that type (limit 15)
        if (empty($query) && $type) {
            return $this->getResultsByType($type);
        }

        if (empty($query)) {
            return response()->json($this->getLinkableCategories());
        }

        // Search within specific type if provided, otherwise search all
        if (!$type || $type === 'Product') {
            $products = Product::where('title', 'LIKE', "%{$query}%")->limit(10)->get();
            foreach ($products as $product) {
                $results[] = [
                    'id' => $product->id,
                    'label' => $product->title,
                    'type' => 'Product',
                    'full_type' => get_class($product),
                    'url' => $product->getLinkableUrl(),
                ];
            }
        }

        if (!$type || $type === 'Collection') {
            $collections = Collection::where('title', 'LIKE', "%{$query}%")->limit(10)->get();
            foreach ($collections as $collection) {
                $results[] = [
                    'id' => $collection->id,
                    'label' => $collection->title,
                    'type' => 'Collection',
                    'full_type' => get_class($collection),
                    'url' => $collection->getLinkableUrl(),
                ];
            }
        }

        if (!$type || $type === 'Category') {
            $categories = \App\Models\Category::where('title', 'LIKE', "%{$query}%")->limit(10)->get();
            foreach ($categories as $category) {
                $results[] = [
                    'id' => $category->id,
                    'label' => $category->title,
                    'type' => 'Category',
                    'full_type' => get_class($category),
                    'url' => $category->getLinkableUrl(),
                ];
            }
        }

        return response()->json($results);
    }

    protected function getLinkableCategories()
    {
        return [
            [
                'group' => 'Online store',
                'items' => [
                    ['label' => 'Home page', 'url' => '/', 'type' => 'Static', 'id' => 'home', 'icon' => 'ph-house'],
                    ['label' => 'Search', 'url' => '/search', 'type' => 'Static', 'id' => 'search', 'icon' => 'ph-magnifying-glass'],
                    ['label' => 'Collections', 'type' => 'Collection', 'has_children' => true, 'icon' => 'ph-tag'],
                    ['label' => 'Products', 'type' => 'Product', 'has_children' => true, 'icon' => 'ph-package'],
                    // ['label' => 'Pages', 'type' => 'Page', 'has_children' => true, 'icon' => 'ph-file-text'],
                    ['label' => 'Categories', 'type' => 'Category', 'has_children' => true, 'icon' => 'ph-list'],
                ]
            ],
            [
                'group' => 'Customer accounts',
                'items' => [
                    ['label' => 'Orders', 'url' => '/customer/orders', 'type' => 'Static', 'id' => 'orders', 'icon' => 'ph-package'],
                    ['label' => 'Profile', 'url' => '/customer/profile', 'type' => 'Static', 'id' => 'profile', 'icon' => 'ph-user'],
                ]
            ],
            [
                'group' => 'Other',
                'items' => [
                    ['label' => 'Custom link', 'type' => 'Custom', 'icon' => 'ph-link'],
                ]
            ]
        ];
    }

    protected function getResultsByType($type)
    {
        $results = [];
        if ($type === 'Product') {
            $items = Product::limit(15)->get();
            foreach ($items as $item) {
                $results[] = ['id' => $item->id, 'label' => $item->title, 'type' => 'Product', 'full_type' => get_class($item), 'url' => $item->getLinkableUrl()];
            }
        } elseif ($type === 'Collection') {
            $items = Collection::limit(15)->get();
            foreach ($items as $item) {
                $results[] = ['id' => $item->id, 'label' => $item->title, 'type' => 'Collection', 'full_type' => get_class($item), 'url' => $item->getLinkableUrl()];
            }
        } elseif ($type === 'Category') {
            $items = \App\Models\Category::limit(15)->get();
            foreach ($items as $item) {
                $results[] = ['id' => $item->id, 'label' => $item->title, 'type' => 'Category', 'full_type' => get_class($item), 'url' => $item->getLinkableUrl()];
            }
        }
        return response()->json($results);
    }

    protected function syncItems(Menu $menu, array $items, $parentId = null)
    {
        foreach ($items as $index => $itemData) {

            // ✅ Skip empty rows
            if (
                empty($itemData['label']) &&
                empty($itemData['url']) &&
                empty($itemData['linkable_id'])
            ) {
                continue;
            }

            $item = MenuItem::create([
                'menu_id'       => $menu->id,
                'parent_id'     => $parentId,
                'label'         => $itemData['label'] ?? null,
                'linkable_type' => $itemData['linkable_type'] ?? null,
                'linkable_id'   => $itemData['linkable_id'] ?? null,
                'url'           => $itemData['url'] ?? null,
                'order'         => $index + 1, // ✅ FIXED ORDER
            ]);

            // ✅ Recursive children with correct parent_id
            if (!empty($itemData['children']) && is_array($itemData['children'])) {
                $this->syncItems($menu, $itemData['children'], $item->id);
            }
        }
    }

}