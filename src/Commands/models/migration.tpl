<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{MIGRATION}Table extends Migration
{
    //提交
    public function up()
    {
        Schema::create('{SNAKE_MIGRATION}', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    //回滚
    public function down()
    {
        Schema::dropIfExists('{SNAKE_MIGRATION}');
    }
}
