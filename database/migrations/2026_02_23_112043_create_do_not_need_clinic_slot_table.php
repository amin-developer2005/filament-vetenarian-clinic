<?php

use App\Models\Clinic;
use App\Models\Slot;
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
        Schema::create('do_not_need_clinic_slot', function (Blueprint $table) {
            $table->foreignIdFor(Clinic::class);
            $table->foreignIdFor(Slot::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('do_not_need_clinic_slot', function (Blueprint $table) {
            $table->dropForeignIdFor(Clinic::class);
            $table->dropForeignIdFor(Slot::class);
        });
    }
};
