<?php

use App\Enums\AnimalSpecies;
use App\Enums\GenderType;
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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('name');
            $table->string('species');
            $table->string('breed')
                ->nullable();
            $table->enum('gender', [
                GenderType::Male->value,
                GenderType::Female->value
            ]);
            $table->date('date_of_birth')->nullable();

            $table->string('microchip_number')
                ->nullable()
                ->unique();

            $table->boolean('is_neutered')
                ->default(false);

            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
