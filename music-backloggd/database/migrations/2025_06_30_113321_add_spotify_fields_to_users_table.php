<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('spotify_id')->nullable()->unique()->after('id');
        $table->string('spotify_token')->nullable()->after('password');
        $table->string('spotify_refresh_token')->nullable()->after('spotify_token');
        $table->integer('spotify_expires_in')->nullable()->after('spotify_refresh_token');
        $table->string('avatar')->nullable()->after('spotify_expires_in');

    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'spotify_id',
            'spotify_token',
            'spotify_refresh_token',
            'spotify_expires_in',
            'avatar',
        ]);
    });
}
};
