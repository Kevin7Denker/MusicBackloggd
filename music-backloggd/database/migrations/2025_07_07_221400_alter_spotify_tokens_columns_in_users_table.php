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
        Schema::table('users', function (Blueprint $table) {
            $table->text('spotify_token')->change()->nullable();;
            $table->text('spotify_refresh_token')->change()->nullable();;
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('spotify_token', 255)->change()->nullable();;
            $table->string('spotify_refresh_token', 255)->change()->nullable();;
        });
    }
};
