<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('room_type_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('floor')->default(1);
            $table->decimal('price', 12, 2);
            $table->enum('status', ['empty', 'occupied', 'maintenance'])->default('empty');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('floor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
