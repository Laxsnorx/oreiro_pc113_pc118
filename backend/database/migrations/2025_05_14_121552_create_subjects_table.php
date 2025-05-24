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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name'); // Name of the subject
            $table->foreignId('instructor_id'); // Foreign key linking to instructors table
            $table->string('year'); // Academic year
            $table->string('schedule'); // Schedule of the subject (e.g., "MWF 10:00 AM - 12:00 PM")
            $table->integer('units'); // Added units field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
