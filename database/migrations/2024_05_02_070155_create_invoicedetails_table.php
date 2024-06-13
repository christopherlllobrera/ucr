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
            $table->date('date_reversal');
            $table->string('accounting_document');
            $table->date('invoice_date_received');
            $table->string('pojo_no');
            $table->string('gr_no_meralco');
            $table->string('billing_statement');
            $table->date('invoice_date_approved');
            $table->date('invoice_posting_date');
            $table->double('invoice_posting_amount');
            $table->date('invoice_date_forwarded');
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
