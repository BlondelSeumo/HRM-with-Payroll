<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'plans', function (Blueprint $table){
                $table->bigIncrements('id');
                $table->string('name', 100)->unique();
                $table->float('price', 15,2)->default(0);
                $table->string('duration', 100);
                $table->integer('max_users')->default(0);
                $table->integer('max_employees')->default(0);
                $table->float('storage_limit', 20, 2)->default(0.00);
                $table->string('enable_chatgpt')->nullable();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
