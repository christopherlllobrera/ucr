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
        Schema::create('draftbilldetails', function (Blueprint $table) {
            $table->id();
            $table->string('draftbill_no')->unique()->nullable();
            $table->string('draftbill_particular')->nullable();
            $table->date('bill_date_created')->nullable();
            $table->double('draftbill_amount')->nullable();
            $table->date('bill_date_submitted')->nullable();
            $table->date('bill_date_approved')->nullable();
            $table->string('bill_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draftbilldetails');
    }
};
