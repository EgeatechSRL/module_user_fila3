<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('socialite_users', function (Blueprint $table) {
            $table->text('refresh_token')->nullable()->after('token');
        });
    }

    public function down()
    {
        Schema::table('socialite_users', function (Blueprint $table) {
            $table->dropColumn('refresh_token');
        });
    }
};
