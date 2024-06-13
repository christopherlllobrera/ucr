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
        Schema::create('invoicedetails', function (Blueprint $table) {
            $table->id();
            $table->string('reversal_doc');
            $table->double('gr_amount')->nullable();
            $table->date('date_reversal')->nullable();
            $table->string('accounting_document')->nullable();
            $table->date('invoice_date_received')->nullable();
            $table->string('pojo_no')->nullable();
            $table->string('gr_no_meralco')->nullable();
            $table->string('billing_statement')->nullable();
            $table->date('invoice_date_approved')->nullable();
            $table->date('invoice_posting_date')->nullable();
            $table->double('invoice_posting_amount')->nullable();
            $table->date('invoice_date_forwarded')->nullable();
            $table->string('invoice_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicedetails');
    }
};
