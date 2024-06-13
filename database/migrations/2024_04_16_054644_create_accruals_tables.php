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
            $table->string('client_name');
            $table->date('date_accrued');
            $table->string('UCR_Park_Doc');
            $table->string('contract_type');
            $table->string('person_in_charge');
            $table->string('business_unit');
            $table->string('particulars');
            $table->date('period_started');
            $table->date('period_ended');
            $table->string('wbs_no');
            $table->string('month');
            $table->double('accrual_amount');
            $table->longText('accruals_attachment')->nullable();
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
