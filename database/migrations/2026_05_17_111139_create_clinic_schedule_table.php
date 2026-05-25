<?php

use App\Models\Clinic;
use App\Models\Schedule;
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
        Schema::create('clinic_schedule', function (Blueprint $table) {
            $table->foreignIdFor(Clinic::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Schedule::class)
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
        Schema::table('clinic_schedule', function (Blueprint $table) {
            $table->dropForeignIdFor(Clinic::class);
            $table->dropForeignIdFor(Schedule::class);
        });
    }
};
