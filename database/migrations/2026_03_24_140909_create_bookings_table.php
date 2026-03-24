<?php

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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();

        $table->string('service');
        $table->decimal('amount', 10, 2);

        $table->integer('day');
        $table->integer('month');
        $table->integer('year');

        $table->string('staff');
        $table->string('time');

        $table->string('status')->default('Pending');

        $table->unique(['day', 'month', 'year', 'staff', 'time']);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
