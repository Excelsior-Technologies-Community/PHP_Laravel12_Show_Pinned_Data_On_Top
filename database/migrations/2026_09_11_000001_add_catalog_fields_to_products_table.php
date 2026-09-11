<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('original_price', 10, 2)->nullable()->after('price');
            $table->decimal('discount_price', 10, 2)->nullable()->after('original_price');
            $table->string('label')->nullable()->after('discount_price');
            $table->string('brand')->nullable()->after('label');
            $table->json('tags')->nullable()->after('brand');
            $table->json('specifications')->nullable()->after('tags');
            $table->string('video_url')->nullable()->after('specifications');
            $table->unsignedInteger('stock')->default(0)->after('video_url');
            $table->unsignedInteger('low_stock_threshold')->default(5)->after('stock');
            $table->unsignedBigInteger('views')->default(0)->after('low_stock_threshold');
            $table->index(['brand', 'label']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_brand_label_index');
            $table->dropColumn([
                'original_price', 'discount_price', 'label', 'brand', 'tags',
                'specifications', 'video_url', 'stock', 'low_stock_threshold', 'views',
            ]);
        });
    }
};
