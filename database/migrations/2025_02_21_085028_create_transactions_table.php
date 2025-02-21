<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\TransactionType;
use App\Enum\TransactionStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', callback: function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->integer('amount');
            $table->string('currency');
            $table->enum('type', TransactionType::values());
            $table->enum('status', TransactionStatus::values());
            $table->integer('parent_id')->nullable();
            $table->string('external_id')->nullable();

            $table->foreignId('user_id')->nullable()->index();
            $table->foreignId('payment_provider_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
