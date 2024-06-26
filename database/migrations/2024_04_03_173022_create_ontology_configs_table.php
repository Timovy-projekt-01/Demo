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
        Schema::create('ontology_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('content');
            $table->unsignedBigInteger('user_file_id');
            $table->foreign('user_file_id')->references('id')
                ->on('user_files')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ontology_configs');
    }
};
