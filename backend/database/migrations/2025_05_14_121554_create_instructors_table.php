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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the instructor
            $table->string('email'); // Name of the instructor
            $table->string('password');
            $table->integer('age'); // Age of the instructor
            $table->string('course'); 
            $table->string('image')->nullable(); // Profile image (optional)
            $table->string('contact_number'); // Contact number
            $table->enum('role', ['instructor']);
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->unique();
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
