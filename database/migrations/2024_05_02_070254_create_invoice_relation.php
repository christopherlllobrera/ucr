<?php

use App\Models\invoice;
use App\Models\invoicedetails;
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
        Schema::create('invoice_relation', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(invoice::class);
            $table->foreignIdFor(invoicedetails::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_relation');
    }
};
