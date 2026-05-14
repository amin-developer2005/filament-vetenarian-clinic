<?php

use App\Enums\SlotStatus;
use App\Models\User;
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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->date('date');
            $table->time('start');
            $table->time('end');
            $table->enum('status', [
                SlotStatus::Created,
                SlotStatus::Confirmed,
                SlotStatus::Cancelled,
            ])->default(SlotStatus::Created);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
