<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('main_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('lain-lain');
            $table->string('image_path')->nullable();
            $table->string('media_type')->default('image');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('main_contents');
    }
};
