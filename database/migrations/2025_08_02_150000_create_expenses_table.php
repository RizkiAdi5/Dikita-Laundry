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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', [
                'operational', 
                'utilities', 
                'salary', 
                'inventory', 
                'equipment', 
                'maintenance', 
                'marketing', 
                'rent', 
                'insurance', 
                'tax', 
                'other'
            ])->default('operational');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'card', 'check', 'other'])->default('cash');
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->enum('frequency', ['one_time', 'daily', 'weekly', 'monthly', 'yearly'])->default('one_time');
            $table->unsignedBigInteger('employee_id')->nullable(); // Employee who made the expense
            $table->unsignedBigInteger('approved_by')->nullable(); // Employee who approved
            $table->string('receipt_number')->nullable();
            $table->string('supplier')->nullable();
            $table->date('expense_date');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable(); // For receipt/invoice files
            $table->boolean('is_recurring')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
}; 