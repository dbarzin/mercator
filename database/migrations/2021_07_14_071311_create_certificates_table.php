<?php

use App\Role;
use App\Permission;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type')->nullable();
            ;
            $table->longText('description')->nullable();
            $table->date('start_validity')->nullable();
            $table->date('end_validity')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'deleted_at'], 'certificate_name_unique');
        });

        Schema::create('certificate_logical_server', function (Blueprint $table) {
            $table->unsignedInteger('certificate_id')->index('certificate_id_fk_9483453');
            $table->unsignedInteger('logical_server_id')->index('logical_server_id_fk_9483453');

            $table->foreign('certificate_id')->references('id')->on('certificates')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('logical_server_id')->references('id')->on('logical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // if not initial migration -> add permissions
        if (Permission::All()->count()>0) {
            // create new permissions
            $permissions = [
                [
                    'id'    => '257',
                    'title' => 'certificate_create',
                ],
                [
                    'id'    => '258',
                    'title' => 'certificate_edit',
                ],
                [
                    'id'    => '259',
                    'title' => 'certificate_show',
                ],
                [
                    'id'    => '260',
                    'title' => 'certificate_delete',
                ],
                [
                    'id'    => '261',
                    'title' => 'certificate_access',
                ],
            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([257,258,259,260,261], false);
            // User
            Role::findOrFail(2)->permissions()->sync([257,258,259,260,261], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([259,261], false);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificate_logical_server');
        Schema::dropIfExists('certificates');
        if (Permission::All()->count()>0) {
            DB::delete("delete from permissions where id in (257, 258, 259, 260, 261)");
        }
    }
}
