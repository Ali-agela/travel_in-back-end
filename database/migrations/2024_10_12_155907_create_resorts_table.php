<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resorts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->text('description');
            $table->foreignId('admin_id')->constrained('admins');
            $table->string('number_of_rooms');
            $table->string('price_per_room');
            $table->string('number_of_poeple');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resorts');
    }
};
