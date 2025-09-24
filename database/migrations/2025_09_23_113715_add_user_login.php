<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login')->after('id')->nullable();
        });

        DB::table('users')->update([
            'login' => DB::raw('email'),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('login')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('login');
        });
    }
};
