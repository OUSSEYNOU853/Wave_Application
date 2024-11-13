<?php

use App\Enums\StatutPayment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('merchant_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Payer
            $table->foreignId('merchant_id')->constrained('users')->onDelete('cascade'); // Marchand
            $table->decimal('amount', 15, 2);
            $table->enum('status', array_column(StatutPayment::cases(), 'value'))->default(StatutPayment::EN_ATTENTE->value);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_payments');
    }
};
