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
        Schema::create('collectiondetails', function (Blueprint $table) {
            $table->id();
            $table->double('amount_collected')->nullable();
            $table->date('tr_posting_date')->nullable();
            $table->string('or_number')->nullable();
            $table->string('collection_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectiondetails');
    }
};
