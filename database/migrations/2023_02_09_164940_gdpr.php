<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gdpr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            // name + description
            $table->longText('responsible')->nullable()->after('description');
            $table->longText('purpose')->nullable()->after('responsible');
            $table->longText('categories')->nullable()->after('purpose');
            $table->longText('recipients')->nullable()->after('categories');
            $table->longText('transfert')->nullable()->after('recipients');
            $table->longText('retention')->nullable()->after('transfert');
            $table->longText('controls')->nullable()->after('retention');
        });

        Schema::table('information', function (Blueprint $table) {
            $table->string('retention')->nullable()->after('constraints');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['responsible', 'purpose', 'categories', 'recipients', 'transfert', 'retention', 'controls']);
        });

        Schema::table('information', function (Blueprint $table) {
            $table->dropColumn(['retention']);
        });
    }
}
