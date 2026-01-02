<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('occupation')->nullable();
            $table->date('planned_check_in')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['new', 'contacted', 'survey', 'deal', 'cancelled'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->datetime('contacted_at')->nullable();
            $table->datetime('survey_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
