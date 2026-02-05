<?php

namespace App\Traits;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

trait HandlesSmartCollections
{
    /**
     * Sync products to a specific smart collection based on its conditions.
     */
    public function syncSmartCollectionProducts(Collection $collection)
    {
        if ($collection->type !== 'smart') {
            return;
        }

        $conditions = $collection->conditions;
        if ($conditions->isEmpty()) {
            $collection->products()->detach();
            return;
        }

        $query = Product::query();

        $method = $collection->condition_type === 'any' ? 'orWhere' : 'where';

        $query->where(function ($q) use ($conditions, $collection, $method) {
            foreach ($conditions as $index => $condition) {
                $subMethod = ($index === 0) ? 'where' : $method;
                $this->applyConditionToQuery($q, $condition, $subMethod);
            }
        });

        $productIds = $query->pluck('id');
        $collection->products()->sync($productIds);
    }

    /**
     * Sync all smart collections for a specific product.
     */
    public function syncProductToSmartCollections(Product $product)
    {
        $smartCollections = Collection::where('type', 'smart')->with('conditions')->get();

        /** @var Collection $collection */
        foreach ($smartCollections as $collection) {
            if ($this->productMatchesCollection($product, $collection)) {
                $collection->products()->syncWithoutDetaching([$product->id]);
            } else {
                $collection->products()->detach($product->id);
            }
        }
    }

    /**
     * Check if a product matches a collection's conditions.
     */
    protected function productMatchesCollection(Product $product, $collection): bool
    {
        $conditions = $collection->conditions;
        if ($conditions->isEmpty()) {
            return false;
        }

        $matches = [];
        foreach ($conditions as $condition) {
            $matches[] = $this->evaluateCondition($product, $condition);
        }

        if ($collection->condition_type === 'any') {
            return in_array(true, $matches);
        }

        return !in_array(false, $matches);
    }

    /**
     * Apply a single condition to a database query.
     */
    protected function applyConditionToQuery(Builder $query, $condition, $method)
    {
        $field = $condition->field;
        $operator = $condition->operator;
        $value = $condition->value;

        // Custom field mapping
        if ($field === 'inventory_stock') {
            $field = 'quantity';
        }

        // Handle category title filtering
        if ($field === 'category') {
            $query->{$method . 'Has'}('category', function($q) use ($operator, $value) {
                // Recursively apply to category title
                $dummyCondition = (object)['field' => 'title', 'operator' => $operator, 'value' => $value];
                $this->applyConditionToQuery($q, $dummyCondition, 'where');
            });
            return;
        }

        // Special handling for price (convert to cents for DB query)
        if (in_array($field, ['price', 'compare_at_price'])) {
            $value = (int) round((float)$value * 100);
        }

        // Check if column exists in products table to avoid SQL errors
        if ($field !== 'category' && !\Illuminate\Support\Facades\Schema::hasColumn('products', $field)) {
            return; // Skip fields that don't exist in the DB
        }

        switch ($operator) {
            case 'equals':
                $query->$method($field, '=', $value);
                break;
            case 'not_equals':
                $query->$method($field, '!=', $value);
                break;
            case 'contains':
                $query->$method($field, 'like', '%' . $value . '%');
                break;
            case 'not_contains':
                $query->$method($field, 'not like', '%' . $value . '%');
                break;
            case 'greater_than':
                $query->$method($field, '>', $value);
                break;
            case 'less_than':
                $query->$method($field, '<', $value);
                break;
            case 'starts_with':
                $query->$method($field, 'like', $value . '%');
                break;
            case 'ends_with':
                $query->$method($field, 'like', '%' . $value);
                break;
            case 'is_empty':
                $query->$method(function($q) use ($field) {
                    $q->whereNull($field)->orWhere($field, '');
                });
                break;
            case 'is_not_empty':
                $query->$method(function($q) use ($field) {
                    $q->whereNotNull($field)->where($field, '!=', '');
                });
                break;
        }
    }

    /**
     * Evaluate a condition against a single product model.
     */
    protected function evaluateCondition(Product $product, $condition): bool
    {
        $field = $condition->field;
        $fieldValue = null;

        if ($field === 'category') {
            $fieldValue = $product->category?->title;
        } elseif ($field === 'inventory_stock') {
            $fieldValue = $product->quantity;
        } else {
            $fieldValue = $product->{$field};
        }

        $targetValue = $condition->value;

        // Price adjustment for comparison
        if (in_array($field, ['price', 'compare_at_price'])) {
            $targetValue = (int) round((float)$targetValue * 100);
        }

        switch ($condition->operator) {
            case 'equals': return $fieldValue == $targetValue;
            case 'not_equals': return $fieldValue != $targetValue;
            case 'contains': return str_contains(strtolower($fieldValue), strtolower($targetValue));
            case 'not_contains': return !str_contains(strtolower($fieldValue), strtolower($targetValue));
            case 'greater_than': return $fieldValue > $targetValue;
            case 'less_than': return $fieldValue < $targetValue;
            case 'starts_with': return str_starts_with(strtolower($fieldValue), strtolower($targetValue));
            case 'ends_with': return str_ends_with(strtolower($fieldValue), strtolower($targetValue));
            case 'is_empty': return empty($fieldValue);
            case 'is_not_empty': return !empty($fieldValue);
            default: return false;
        }
    }
}
