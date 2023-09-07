<?php

use App\Role;
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
        // Add a patching group
        Schema::table('logical_servers', function (Blueprint $table) {
            $table->string('patching_group')->nullable();
            $table->integer('paching_frequency')->nullable();
            $table->date('next_update')->nullable();
        });
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->string('patching_group')->nullable();
            $table->integer('paching_frequency')->nullable();
            $table->date('next_update')->nullable();
        });

        // Link between documents and logical_servers
        Schema::create('document_logical_server', function (Blueprint $table) {
            $table->unsignedInteger('logical_server_id')->index('logical_server_id_fk_43832473');
            $table->unsignedInteger('document_id')->index('document_id_fk_1284334');
            $table->foreign('logical_server_id', 'logical_server_id_fk_43832473')->references('id')->on('logical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('document_id', 'document_id_fk_1284334')->references('id')->on('documents')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // Access rights
        // if not initial migration -> add permissions
        if (Permission::count() > 0) {
            // create new permissions
            $permissions = [
                [
                    'id' => '279',
                    'title' => 'patching_access',
                ],
                [
                    'id' => '280',
                    'title' => 'patching_make',
                ],
            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([279, 280], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([279], false);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Link between documents and applications
        Schema::table('document_logical_server', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_1284334');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('logical_server_id_fk_43832473');
            }
        });
        Schema::dropIfExists('document_logical_server');

        // Remove column
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->dropColumn(['patching_group']);
            $table->dropColumn(['paching_frequency']);
            $table->dropColumn(['next_update']);
        });
        Schema::table('logical_servers', function (Blueprint $table) {
            $table->dropColumn(['patching_group']);
            $table->dropColumn(['paching_frequency']);
            $table->dropColumn(['next_update']);
        });

        // delete access rights
        if (Permission::count() > 0)
            DB::delete("delete from permissions where id in (279, 280)");

    }
};
