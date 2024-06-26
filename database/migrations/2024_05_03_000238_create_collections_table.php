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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ucr_ref_id')->constrained('accruals')->cascadeOnDelete();
            $table->foreignId('draftbill_no')->constrained('draftbilldetails')->cascadeOnDelete();
            $table->foreignId('accounting_doc')->constrained('invoicedetails')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
