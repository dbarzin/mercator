<?php

use App\Permission;
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

        Schema::create('containers', function (Blueprint $table) {
	        $table->increments('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->longText('description')->nullable();

            $table->unsignedInteger('icon_id')->nullable()->index('document_id_fk_43948593');
            $table->foreign('icon_id','document_id_fk_434833774')->references('id')->on('documents')->onUpdate('NO ACTION');

            $table->timestamps();
	        $table->softDeletes();
            $table->unique(['name', 'deleted_at'], 'container_name_unique');
        });

        Schema::create('container_logical_server', function (Blueprint $table) {
            $table->unsignedInteger('container_id')->index('container_id_fk_54933032');
            $table->unsignedInteger('logical_server_id')->index('logical_server_id_fk_4394832');

            $table->foreign('container_id')->references('id')->on('containers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('logical_server_id')->references('id')->on('logical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::create('container_m_application', function (Blueprint $table) {
            $table->unsignedInteger('container_id')->index('container_id_fk_549854345');
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_344234340');

            $table->foreign('container_id')->references('id')->on('containers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // if not initial migration -> add permissions
        if (Permission::All()->count()>0) {
            // create new permissions
            $permissions = [
                [
                    'id'    => '301',
                    'title' => 'container_create',
                ],
                [
                    'id'    => '302',
                    'title' => 'container_edit',
                ],
                [
                    'id'    => '303',
                    'title' => 'container_show',
                ],
                [
                    'id'    => '304',
                    'title' => 'container_delete',
                ],
                [
                    'id'    => '305',
                    'title' => 'container_access',
                ],
            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([301,302,303,304,305], false);
            // User
            Role::findOrFail(2)->permissions()->sync([301,302,303,304,305], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([303,305], false);

        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('container_logical_server');
        Schema::dropIfExists('container_m_application');
        Schema::dropIfExists('containers');
        if (Permission::All()->count()>0) {
            DB::delete("delete from permissions where id in (301, 302, 303, 304, 305)");
        }
    }
};
