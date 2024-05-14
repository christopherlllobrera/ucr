<?php

use App\Models\draftbill;
use App\Models\draftbilldetails;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('draftbill_relation', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(draftbill::class);
            $table->foreignIdFor(draftbilldetails::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draftbill_relation');
    }
};
