<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('pin_priority')
                ->default(0)
                ->after('is_pinned');

            $table->dateTime('pin_start_at')
                ->nullable()
                ->after('pin_priority');

            $table->dateTime('pin_end_at')
                ->nullable()
                ->after('pin_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'pin_priority',
                'pin_start_at',
                'pin_end_at',
            ]);
        });
    }
};