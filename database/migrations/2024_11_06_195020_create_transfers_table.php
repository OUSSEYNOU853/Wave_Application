<?php

use App\Enums\StatutTransfert;
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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->string('recipient_number')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', array_column(StatutTransfert::cases(), 'value'))->default(StatutTransfert::EN_ATTENTE->value);
            $table->boolean('is_multiple')->default(false);
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
