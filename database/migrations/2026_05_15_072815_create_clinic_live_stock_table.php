<?php

use App\Models\Clinic;
use App\Models\LiveStock;
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
        Schema::create('clinic_live_stock', function (Blueprint $table) {
            $table->foreignIdFor(Clinic::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Livestock::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_live_stock', function (Blueprint $table) {
            $table->dropForeignIdFor(Clinic::class);
            $table->dropForeignIdFor(LiveStock::class);
        });
    }
};
