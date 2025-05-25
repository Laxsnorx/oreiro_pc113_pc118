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
    $table->string('name');
    $table->string('email');
    $table->string('password');
    $table->integer('age');
    $table->string('course');
    $table->string('image')->nullable();
    $table->string('contact_number');
    $table->enum('role', ['instructor']);
    $table->timestamps();

    $table->unsignedBigInteger('subject_id')->nullable();


    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
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