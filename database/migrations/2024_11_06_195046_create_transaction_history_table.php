<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TransactionType;
use App\Enums\StatutTransaction;

class CreateTransactionHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('transaction_type', array_column(TransactionType::cases(), 'value'))->default(TransactionType::DEPOT->value);
            $table->decimal('amount', 15, 2);
            $table->enum('status', array_column(StatutTransaction::cases(), 'value'))->default(StatutTransaction::TERMINER->value);
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_histories');
    }
}
