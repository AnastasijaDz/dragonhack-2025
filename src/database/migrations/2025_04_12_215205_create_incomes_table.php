<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->integer('income_amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
