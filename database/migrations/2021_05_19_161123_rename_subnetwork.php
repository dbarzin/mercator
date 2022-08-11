<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameSubnetwork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('network_subnetword', function (Blueprint $table) {
            $table->dropForeign('subnetword_id_fk_1492377');
        });

        Schema::rename('network_subnetword', 'network_subnetwork');

        Schema::table('network_subnetwork', function (Blueprint $table) {
            $table->renameColumn('subnetword_id', 'subnetwork_id');
        });

        Schema::table('network_subnetwork', function (Blueprint $table) {
            $table->foreign('subnetwork_id', 'subnetwork_id_fk_1492377')->references('id')->on('subnetworks')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // rename permissions
        DB::update("update permissions set title = 'subnetwork_create' where title = 'subnetword_create'");
        DB::update("update permissions set title = 'subnetwork_edit' where title = 'subnetword_edit'");
        DB::update("update permissions set title = 'subnetwork_show' where title = 'subnetword_show'");
        DB::update("update permissions set title = 'subnetwork_delete' where title = 'subnetword_delete'");
        DB::update("update permissions set title = 'subnetwork_access' where title = 'subnetword_access'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('network_subnetwork', function (Blueprint $table) {
            $table->dropForeign('subnetwork_id_fk_1492377');
        });
        Schema::rename('network_subnetwork', 'network_subnetword');

        Schema::table('network_subnetword', function (Blueprint $table) {
            $table->renameColumn('subnetwork_id', 'subnetword_id');
        });

        Schema::table('network_subnetword', function (Blueprint $table) {
            $table->foreign('subnetword_id', 'subnetword_id_fk_1492377')->references('id')->on('subnetworks')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // rename permissions
        DB::update("update permissions set title = 'subnetword_create' where title = 'subnetwork_create'");
        DB::update("update permissions set title = 'subnetword_edit' where title = 'subnetwork_edit'");
        DB::update("update permissions set title = 'subnetword_show' where title = 'subnetwork_show'");
        DB::update("update permissions set title = 'subnetword_delete' where title = 'subnetwork_delete'");
        DB::update("update permissions set title = 'subnetword_access' where title = 'subnetwork_access'");
    }
}
