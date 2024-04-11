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
        Schema::create('logical_flows', function (Blueprint $table) {
            // Unique cluster link identifier
            $table->increments('id');

            $table->string('name')->nullable();
            $table->string('source_ip_range');
            $table->string('dest_ip_range');
            $table->string('source_port')->nullable();
            $table->string('dest_port')->nullable();
            $table->string('protocol')->nullable();
            $table->text('description')->nullable();

            // Soft delete and timestamp
            $table->timestamps();
            $table->softDeletes();
        });

        // if not initial migration -> add permissions
        if (Permission::count()>0) {

            // create new permissions
            $permissions = [
                [
                    'id'    => '286',
                    'title' => 'logical_flow_create',
                ],
                [
                    'id'    => '287',
                    'title' => 'logical_flow_edit',
                ],
                [
                    'id'    => '288',
                    'title' => 'logical_flow_show',
                ],
                [
                    'id'    => '289',
                    'title' => 'logical_flow_delete',
                ],
                [
                    'id'    => '290',
                    'title' => 'logical_flow_access',
                ],
            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([286,287,288,289,290], false);
            // User
            Role::findOrFail(2)->permissions()->sync([286,287,288,289,290], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([288,290], false);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logical_flows');

        DB::delete('delete from permissions where id=286;');
        DB::delete('delete from permissions where id=287;');
        DB::delete('delete from permissions where id=288;');
        DB::delete('delete from permissions where id=289;');
        DB::delete('delete from permissions where id=290;');
    }
};
