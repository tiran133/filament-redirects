<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sort_order')->nullable();
            $table->string('from');
            $table->string('to')->nullable();
            $table->integer('status');
            $table->boolean('pass_query_string')->default(0);
            $table->tinyInteger('online')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('redirects');
    }
};
