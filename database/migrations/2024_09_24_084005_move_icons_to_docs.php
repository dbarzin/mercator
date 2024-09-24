<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workstations', function(Blueprint $table) {
            $table->dropColumn('icon')->nullable()->after("type");
            $table->unsignedInteger('icon_id')->after("type")->nullable()->index('document_id_fk_129483');
            $table->foreign('icon_id','document_id_fk_129483')->references('id')->on('documents')->onUpdate('NO ACTION');
        });
        Schema::table('peripherals', function(Blueprint $table) {
            $table->dropColumn('icon')->nullable()->after("name");
            $table->unsignedInteger('icon_id')->after("type")->nullable()->index('document_id_fk_129484');
            $table->foreign('icon_id','document_id_fk_129484')->references('id')->on('documents')->onUpdate('NO ACTION');
        });
        Schema::table('sites', function(Blueprint $table) {
            $table->dropColumn('icon')->nullable()->after("name");
            $table->unsignedInteger('icon_id')->after("name")->nullable()->index('document_id_fk_129485');
            $table->foreign('icon_id','document_id_fk_129485')->references('id')->on('documents')->onUpdate('NO ACTION');
        });
        Schema::table('entities', function(Blueprint $table) {
            $table->dropColumn('icon')->nullable()->after("name");
            $table->unsignedInteger('icon_id')->after("name")->nullable()->index('document_id_fk_129486');
            $table->foreign('icon_id','document_id_fk_129486')->references('id')->on('documents')->onUpdate('NO ACTION');
        });
        Schema::table('admin_users', function(Blueprint $table) {
            $table->dropColumn('icon')->nullable()->after("type");
            $table->unsignedInteger('icon_id')->after("type")->nullable()->index('document_id_fk_129487');
            $table->foreign('icon_id','document_id_fk_129487')->references('id')->on('documents')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop icon_id on Workstations
        Schema::table('workstations', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_129483');
            }
        });

        Schema::table('workstations', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });

        Schema::table('workstations', function(Blueprint $table) {
            $table->mediumText('icon')->nullable()->after("name");
        });

        // Drop icon_id on Peripherals
        Schema::table('peripherals', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_129484');
            }
        });

        Schema::table('peripherals', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });

        Schema::table('peripherals', function(Blueprint $table) {
            $table->mediumText('icon')->nullable()->after("name");
        });


        // Drop icon_id on Sites
        Schema::table('sites', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_129485');
            }
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });

        Schema::table('sites', function(Blueprint $table) {
            $table->mediumText('icon')->nullable()->after("name");
        });

        // Drop icon_id on Entities
        Schema::table('entities', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_129486');
            }
        });

        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });

        Schema::table('entities', function(Blueprint $table) {
            $table->mediumText('icon')->nullable()->after("name");
        });

        // Drop icon_id on Admin users
        Schema::table('admin_users', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_129487');
            }
        });

        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });

        Schema::table('admin_users', function(Blueprint $table) {
            $table->mediumText('icon')->nullable()->after("type");
        });
    }
};
