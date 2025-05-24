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
            $table->string('email'); 
            $table->string('password');
            $table->integer('age'); // Age of the instructor
            $table->string('course'); 
            $table->string('image')->nullable(); // Profile image (optional)
            $table->string('contact_number'); // Contact number
            $table->enum('role', ['instructor']);
            
            // ✅ Define subject_id column first
            $table->unsignedBigInteger('subject_id')->nullable(); // nullable if not always assigned

            // ✅ Then define the foreign key
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

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
