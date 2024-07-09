<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->tinyInteger('n_rooms');
            $table->tinyInteger('n_beds');
            $table->tinyInteger('n_bathrooms');
            $table->smallInteger('squared_meters');
            $table->text('image')->nullable();
            $table->boolean('is_visible')->default(1);
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('slug');
            $table->softDeletes(); //aggiunge la colonna delete_at per la soft delete
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
