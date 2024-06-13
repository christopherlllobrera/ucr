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
            $table->string('draftbill_number')->unique();
            $table->string('draftbill_particular');
            $table->date('bill_date_created');
            $table->double('draftbill_amount');
            $table->date('bill_date_submitted');
            $table->date('bill_date_approved');
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
