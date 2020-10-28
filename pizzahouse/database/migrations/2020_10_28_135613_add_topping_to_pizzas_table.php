<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToppingToPizzasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pizzas', function (Blueprint $table) {
            $table->json('toppings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pizzas', function (Blueprint $table) {
            $table->dropColumn('toppings');
        });
    }
}
