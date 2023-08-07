<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlays', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount');
            $table->text('comment');
            $table->date('date');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('outlay_types');
            $table->unsignedBigInteger('cashier_id');
            $table->unique('type_id','cashier_id');
            $table->foreign('cashier_id')->references('id')->on('cashiers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlays');
    }
};
