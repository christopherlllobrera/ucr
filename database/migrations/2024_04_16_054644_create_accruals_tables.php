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
        Schema::create('accruals', function (Blueprint $table) {
            $table->id();
            $table->string('ucr_ref_id')->unique();
            $table->string('client_name')->nullable();
            $table->string('person_in_charge')->nullable();
            $table->string('wbs_no')->nullable();
            $table->string('particulars')->nullable();
            $table->date('period_covered')->nullable();
            $table->string('month')->nullable();
            $table->string('business_unit')->nullable();
            $table->string('contract_type')->nullable();
            $table->double('accrual_amount')->nullable();
            $table->string('accruals_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accruals');
    }
};
