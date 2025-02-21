<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This is log table, in order to speed up the insertion there will not
     * have foreing constrains, just indexes, also there is no UUID column,
     * the rows should not to be exposed in an API
     */
    public function up(): void
    {
        Schema::create('webhook_requests', function (Blueprint $table) {
            $table->id();

            $table->json('request');
            $table->integer('transaction_id')->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_requests');
    }
};
