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
        Schema::create('medicine_order', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBiginteger("medicine_id");
            $table->unsignedBigInteger("order_id");
            $table->integer("quantity");

            $table->foreign("medicine_id")
                ->references("id")
                ->on("medicines");

            $table->foreign("order_id")
                ->references("id")
                ->on("orders");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine_order');
    }
};
