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
        Schema::create(table: 'blogs', callback: function (Blueprint $table) {
            $table->id();
            $table->text(column: 'title')->nullable();
            $table->text(column: 'slug')->nullable();
            $table->longText(column: 'content')->nullable();
            $table->text(column: 'image')->nullable();
            $table->boolean(column: 'published')->default(value: false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
