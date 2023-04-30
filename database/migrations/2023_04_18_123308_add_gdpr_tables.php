<?php

use App\Permission;
use App\Role;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGdprTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get back on previous GDPR migration
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('responsible');
            $table->dropColumn('purpose');
            $table->dropColumn('categories');
            $table->dropColumn('recipients');
            $table->dropColumn('transfert');
            $table->dropColumn('retention');
            $table->dropColumn('controls');
        });

        // Create table security_controls
        Schema::create('security_controls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'deleted_at'], 'security_controls_name_unique');
        });

        // Create table data_processing_register
        Schema::create('data_processing', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->longText('responsible')->nullable();
            $table->longText('purpose')->nullable();
            $table->longText('categories')->nullable();
            $table->longText('recipients')->nullable();
            $table->longText('transfert')->nullable();
            $table->longText('retention')->nullable();
            $table->longText('controls')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Link data_processing <-> documents
        Schema::create('data_processing_document', function (Blueprint $table) {
            $table->unsignedInteger('data_processing_id')->index('data_processing_id_fk_6930583');
            $table->unsignedInteger('document_id')->index('operation_id_fk_4355431');
        });

        Schema::table('data_processing_document', function (Blueprint $table) {
            $table->foreign('data_processing_id', 'data_processing_id_fk_42343234')->references('id')->on('data_processing')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('document_id', 'document_id_fk_3439483')->references('id')->on('documents')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // Link data_processing <-> processes
        Schema::create('data_processing_process', function (Blueprint $table) {
            $table->unsignedInteger('data_processing_id')->index('data_processing_id_fk_5435435');
            $table->unsignedInteger('process_id')->index('process_id_fk_594358');
        });

        Schema::table('data_processing_process', function (Blueprint $table) {
            $table->foreign('data_processing_id', 'data_processing_id_fk_764545345')->references('id')->on('data_processing')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('process_id', 'process_id_fk_0483434')->references('id')->on('processes')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // link data_processing <-> applications
        Schema::create('data_processing_m_application', function (Blueprint $table) {
            $table->unsignedInteger('data_processing_id')->index('data_processing_id_fk_6948435');
            $table->unsignedInteger('m_application_id')->index('m_applications_id_fk_4384483');
        });

        Schema::table('data_processing_m_application', function (Blueprint $table) {
            $table->foreign('data_processing_id', 'data_processing_id_fk_49838437')->references('id')->on('data_processing')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'applications_id_fk_0483434')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // link data_processing <-> information
        Schema::create('data_processing_information', function (Blueprint $table) {
            $table->unsignedInteger('data_processing_id')->index('data_processing_id_fk_6948435');
            $table->unsignedInteger('information_id')->index('information_id_fk_4384483');
        });

        Schema::table('data_processing_information', function (Blueprint $table) {
            $table->foreign('data_processing_id', 'data_processing_id_fk_493438483')->references('id')->on('data_processing')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('information_id', 'information_id_fk_0483434')->references('id')->on('information')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // Access rights
        // if not initial migration -> add permissions
        if (Permission::count()>0) {
            // create new permissions
            $permissions = [
                [
                    'id'    => '268',
                    'title' => 'gdpr_access',
                ],

                [
                    'id'    => '269',
                    'title' => 'security_controls_create',
                ],
                [
                    'id'    => '270',
                    'title' => 'security_controls_edit',
                ],
                [
                    'id'    => '271',
                    'title' => 'security_controls_show',
                ],
                [
                    'id'    => '272',
                    'title' => 'security_controls_delete',
                ],
                [
                    'id'    => '273',
                    'title' => 'security_controls_access',
                ],


                [
                    'id'    => '274',
                    'title' => 'data_processing_register_create',
                ],
                [
                    'id'    => '275',
                    'title' => 'data_processing_register_edit',
                ],
                [
                    'id'    => '276',
                    'title' => 'data_processing_register_show',
                ],
                [
                    'id'    => '277',
                    'title' => 'data_processing_register_delete',
                ],
                [
                    'id'    => '278',
                    'title' => 'data_processing_register_access',
                ],

            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([268,269,270,271,272,273,274,275,276,277,278], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([268,271,273,276,278], false);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // delete access rights
        if (Permission::count()>0) 
            DB::delete("delete from permissions where id in (268, 269, 270, 271, 272, 273, 274, 275, 276, 277, 278)");

        // delete tables
        Schema::dropIfExists('data_processing_document');
        Schema::dropIfExists('data_processing_process');
        Schema::dropIfExists('data_processing_m_application');
        Schema::dropIfExists('data_processing_information');
        Schema::dropIfExists('data_processing');
        Schema::dropIfExists('security_controls');

        // Forward table activities
        Schema::table('activities', function (Blueprint $table) {
            $table->longText('responsible')->nullable()->after('description');
            $table->longText('purpose')->nullable()->after('responsible');
            $table->longText('categories')->nullable()->after('purpose');
            $table->longText('recipients')->nullable()->after('categories');
            $table->longText('transfert')->nullable()->after('recipients');
            $table->longText('retention')->nullable()->after('transfert');
            $table->longText('controls')->nullable()->after('retention');
        });

    }
}
