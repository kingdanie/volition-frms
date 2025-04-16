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
        Schema::create('fund_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_id')->constrained()->onDelete('cascade');
            $table->enum('frequency', ['monthly', 'quarterly', 'yearly']);
            $table->boolean('is_compound');
            $table->decimal('percentage', 8, 4); // Allows for precise percentages (e.g., 5.2543%)
            $table->date('date'); // The date this return applies to
            $table->decimal('value_before', 15, 2); // Fund value before this return
            $table->decimal('value_after', 15, 2); // Fund value after this return
            $table->decimal('amount', 15, 2); // The monetary amount this return added/subtracted
            $table->boolean('is_active')->default(true); // For soft-reverting returns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_returns');
    }
};
