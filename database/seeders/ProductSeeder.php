<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Vendor;
use App\Enums\Scope;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Create Attributes if they don't exist
        $productAttributes = [
            'Material' => ['Cotton', 'Polyester', 'Silk', 'Wool', 'Leather'],
            'Style' => ['Casual', 'Formal', 'Sporty', 'Vintage', 'Modern'],
        ];

        $variantAttributes = [
            'Color' => ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow'],
            'Size' => ['S', 'M', 'L', 'XL', 'XXL'],
        ];

        $attributeModels = [];

        foreach ($productAttributes as $name => $values) {
            $attribute = Attribute::firstOrCreate(
                ['name' => $name],
                ['scope' => Scope::PRODUCT]
            );
            $attributeModels[$name] = $attribute;

            foreach ($values as $value) {
                AttributeValue::firstOrCreate([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }

        foreach ($variantAttributes as $name => $values) {
            $attribute = Attribute::firstOrCreate(
                ['name' => $name],
                ['scope' => Scope::VARIANT]
            );
            $attributeModels[$name] = $attribute;

            foreach ($values as $value) {
                AttributeValue::firstOrCreate([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }

        $categories = Category::all();
        $vendors = Vendor::all();

        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please seed categories first.');
            return;
        }

        if ($vendors->isEmpty()) {
            $this->command->error('No vendors found. Please seed vendors first.');
            return;
        }

        // 2. Generate 100 Products
        for ($i = 0; $i < 100; $i++) {
            $title = $faker->unique()->words(3, true);
            $product = Product::create([
                'category_id' => $categories->random()->id,
                'vendor_id' => $vendors->random()->id,
                'title' => Str::title($title),
                'slug' => Str::slug($title),
                'short_description' => $faker->sentence(),
                'description' => $faker->paragraphs(3, true),
                'price' => $faker->numberBetween(10, 500), // setPriceAttribute will multiply by 100
                'compare_at_price' => $faker->numberBetween(501, 1000),
                'quantity' => $faker->numberBetween(10, 100),
                'status' => true,
                'is_approved' => true,
                'is_featured' => $faker->boolean(20),
            ]);

            // Attach product-level attributes
            foreach ($productAttributes as $name => $values) {
                $value = $faker->randomElement($values);
                $product->attributes()->attach($attributeModels[$name]->id, ['value' => $value]);
            }

            // 3. Generate 3-5 variants per product
            $numVariants = $faker->numberBetween(3, 5);
            for ($j = 0; $j < $numVariants; $j++) {
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'price' => $product->price / 100 + $faker->numberBetween(-5, 20),
                    'compare_at_price' => $product->compare_at_price / 100,
                    'quantity' => $faker->numberBetween(0, 50),
                    'sku' => strtoupper(Str::random(10)),
                    'status' => true,
                ]);

                // Attach variant-level attributes
                foreach ($variantAttributes as $name => $values) {
                    $value = $faker->randomElement($values);
                    $variant->attributes()->attach($attributeModels[$name]->id, ['value' => $value]);
                }
            }
        }

        $this->command->info('100 products with variants seeded successfully!');
    }
}
