<?php

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
        Schema::create('data_processing_register', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');




            $table->timestamps();
            $table->softDeletes();
        });

        // -> link to entities
        // -> link to applications
        // -> link with security measures

        // create access rights


        // Access rights
        // if not initial migration -> add permissions
        if (Permission::All()->count()>0) {
            // create new permissions
            $permissions = [
                [
                    'id'    => '???',
                    'title' => 'gdpr_access',
                ],

                [
                    'id'    => '263',
                    'title' => 'security_controls_create',
                ],
                [
                    'id'    => '264',
                    'title' => 'security_controls_edit',
                ],
                [
                    'id'    => '265',
                    'title' => 'security_controls_show',
                ],
                [
                    'id'    => '266',
                    'title' => 'security_controls_delete',
                ],
                [
                    'id'    => '267',
                    'title' => 'security_controls_access',
                ],


                [
                    'id'    => '263',
                    'title' => 'data_processing_register_create',
                ],
                [
                    'id'    => '264',
                    'title' => 'data_processing_register_edit',
                ],
                [
                    'id'    => '265',
                    'title' => 'data_processing_register_show',
                ],
                [
                    'id'    => '266',
                    'title' => 'data_processing_register_delete',
                ],
                [
                    'id'    => '267',
                    'title' => 'data_processing_register_access',
                ],

            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([263,264,265,266,267], false);
            // User
            Role::findOrFail(2)->permissions()->sync([263,264,265,266,267], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([266,267], false);
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

        // delete tables
        Schema::dropIfExists('data_processing_register');
        Schema::dropIfExists('security_controls');

        // Forward table activities
        Schema::table('activities', function (Blueprint $table) {
            // name + description                                               // ....
            $table->longText('responsible')->nullable()->after('description');    // a
            $table->longText('purpose')->nullable()->after('responsible');        // b
            $table->longText('categories')->nullable()->after('purpose');         // c
            $table->longText('recipients')->nullable()->after('categories');         // d
            $table->longText('transfert')->nullable()->after('recipients');       // e
            $table->longText('retention')->nullable()->after('transfert');        // f
            $table->longText('controls')->nullable()->after('retention');         // g
        });

    }
}
