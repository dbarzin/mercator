<?php
use App\Permission;
use App\Role;

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

        Schema::create('clusters', function (Blueprint $table) {
            // Unique cluster link identifier
            $table->increments('id');

            $table->string('name');
            $table->string('type')->nullable();
            $table->longText('description')->nullable();
            $table->string('address_ip')->nullable();

            // Soft delete and timestamp
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'deleted_at'], 'peripherals_name_unique');
        });

        // if not initial migration -> add permissions
        if (Permission::count()>0) {

            // create new permissions
            $permissions = [
                [
                    'id'    => '281',
                    'title' => 'cluster_create',
                ],
                [
                    'id'    => '282',
                    'title' => 'cluster_edit',
                ],
                [
                    'id'    => '283',
                    'title' => 'cluster_show',
                ],
                [
                    'id'    => '284',
                    'title' => 'cluster_delete',
                ],
                [
                    'id'    => '285',
                    'title' => 'cluster_access',
                ],
            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([281,282,283,284,285], false);
            // User
            Role::findOrFail(2)->permissions()->sync([281,282,283,284,285], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([283,285], false);
        }

        Schema::table('logical_servers', function (Blueprint $table) {
            // link to cluster
            $table->unsignedInteger('cluster_id')->index('cluster_id_fk_5435359')->nullable();
            $table->foreign('cluster_id','cluster_id_fk_5435359')->references('id')->on('clusters')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('physical_servers', function (Blueprint $table) {
            // link to cluster
            $table->unsignedInteger('cluster_id')->index('cluster_id_fk_5438543')->nullable();
            $table->foreign('cluster_id','cluster_id_fk_5438543')->references('id')->on('clusters')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logical_servers', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('cluster_id_fk_5435359');
            }
        });

        Schema::table('logical_servers', function (Blueprint $table) {
            $table->dropColumn('cluster_id');
        });

        Schema::table('physical_servers', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('cluster_id_fk_5438543');
            }
        });

        Schema::table('physical_servers', function (Blueprint $table) {
            $table->dropColumn('cluster_id');
        });

        /*
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->dropColumn('cluster_id');
        });
        */
        Schema::dropIfExists('clusters');

        DB::delete('delete from permissions where id=281;');
        DB::delete('delete from permissions where id=282;');
        DB::delete('delete from permissions where id=283;');
        DB::delete('delete from permissions where id=284;');
        DB::delete('delete from permissions where id=285;');
    }
};
