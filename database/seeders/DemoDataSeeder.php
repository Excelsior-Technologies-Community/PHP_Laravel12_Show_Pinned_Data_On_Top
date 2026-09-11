<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Home & Living', 'slug' => 'home-living'],
        ])->map(fn (array $data) => Category::updateOrCreate(['slug' => $data['slug']], $data));

        $products = [
            ['name' => 'Wireless Headphones', 'price' => 2499, 'original_price' => 2999, 'discount_price' => 1999, 'label' => 'Sale', 'brand' => 'SoundMax', 'category_id' => $categories[0]->id, 'image' => '1766555413.jpg', 'details' => 'Noise cancelling wireless headphones with deep bass.', 'stock' => 24, 'low_stock_threshold' => 5, 'is_pinned' => 1, 'pin_priority' => 1, 'tags' => ['audio', 'wireless', 'sale'], 'specifications' => ['Battery' => '30 hours', 'Connection' => 'Bluetooth 5.3']],
            ['name' => 'Smart Watch Pro', 'price' => 3999, 'original_price' => 4999, 'discount_price' => 3499, 'label' => 'Popular', 'brand' => 'FitTrack', 'category_id' => $categories[0]->id, 'image' => '1766555854.jpg', 'details' => 'Fitness tracking smartwatch with heart-rate monitoring.', 'stock' => 12, 'low_stock_threshold' => 3, 'is_pinned' => 1, 'pin_priority' => 2, 'tags' => ['fitness', 'watch', 'smart'], 'specifications' => ['Display' => 'AMOLED', 'Water Resistance' => '5 ATM']],
            ['name' => 'Cotton Casual Shirt', 'price' => 1299, 'original_price' => 1699, 'discount_price' => 999, 'label' => 'New', 'brand' => 'UrbanWeave', 'category_id' => $categories[1]->id, 'image' => '1766555879.jpg', 'details' => 'Breathable cotton shirt for everyday comfort.', 'stock' => 30, 'low_stock_threshold' => 5, 'is_pinned' => 1, 'pin_priority' => 3, 'tags' => ['shirt', 'cotton', 'casual'], 'specifications' => ['Fabric' => '100% Cotton', 'Fit' => 'Regular']],
            ['name' => 'Minimal Table Lamp', 'price' => 1799, 'original_price' => 2199, 'discount_price' => 1499, 'label' => 'Sale', 'brand' => 'LumaHome', 'category_id' => $categories[2]->id, 'image' => '1766556487.jpg', 'details' => 'Warm LED table lamp for your workspace or bedside.', 'stock' => 18, 'low_stock_threshold' => 4, 'is_pinned' => 0, 'pin_priority' => 0, 'tags' => ['lamp', 'decor', 'led'], 'specifications' => ['Light' => 'Warm LED', 'Power' => '12W']],
            ['name' => 'Everyday Backpack', 'price' => 2299, 'original_price' => 2799, 'discount_price' => 1899, 'label' => 'Popular', 'brand' => 'TrailPack', 'category_id' => $categories[1]->id, 'image' => '1766556499.jpg', 'details' => 'Durable backpack with laptop compartment and organizer pockets.', 'stock' => 20, 'low_stock_threshold' => 5, 'is_pinned' => 0, 'pin_priority' => 0, 'tags' => ['bag', 'travel', 'laptop'], 'specifications' => ['Capacity' => '24L', 'Laptop' => '15.6 inch']],
        ];

        foreach ($products as $data) {
            $tagNames = $data['tags'];
            unset($data['tags']);
            $product = Product::updateOrCreate(['name' => $data['name']], $data);
            foreach ($tagNames as $tagName) {
                $tag = Tag::updateOrCreate(['slug' => Str::slug($tagName)], ['name' => ucwords($tagName)]);
                $product->tagsRelation()->syncWithoutDetaching([$tag->id]);
            }
        }

        $headphones = Product::where('name', 'Wireless Headphones')->firstOrFail();
        $watch = Product::where('name', 'Smart Watch Pro')->firstOrFail();
        ProductVariant::updateOrCreate(['product_id' => $headphones->id, 'name' => 'Color', 'value' => 'Black'], ['stock' => 12]);
        ProductVariant::updateOrCreate(['product_id' => $headphones->id, 'name' => 'Color', 'value' => 'White'], ['stock' => 12]);
        ProductVariant::updateOrCreate(['product_id' => $watch->id, 'name' => 'Size', 'value' => 'Standard'], ['stock' => 12]);
        $headphones->relatedProducts()->syncWithoutDetaching([$watch->id]);
        $watch->relatedProducts()->syncWithoutDetaching([$headphones->id]);
    }
}
