<?php

use Mercator\Core\Models\Permission;
use Mercator\Core\Models\Role;
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
        Schema::create('graphs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'deleted_at'], 'graphs_name_unique');
        });

        // if not initial migration -> add permissions
        if (Permission::count() > 0) {
            // create new permissions
            $permissions = [
                [
                    'id' => '296',
                    'title' => 'graph_create',
                ],
                [
                    'id' => '297',
                    'title' => 'graph_edit',
                ],
                [
                    'id' => '298',
                    'title' => 'graph_show',
                ],
                [
                    'id' => '299',
                    'title' => 'graph_delete',
                ],
                [
                    'id' => '300',
                    'title' => 'graph_access',
                ],
            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([296, 297, 298, 299, 300], false);
            // User
            Role::findOrFail(2)->permissions()->sync([296, 297, 298, 299, 300], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([298, 300], false);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tabke
        Schema::dropIfExists('graphs');

        // Remove permissions
        DB::delete('delete from permissions where id=296;');
        DB::delete('delete from permissions where id=297;');
        DB::delete('delete from permissions where id=298;');
        DB::delete('delete from permissions where id=299;');
        DB::delete('delete from permissions where id=300;');

    }
};
