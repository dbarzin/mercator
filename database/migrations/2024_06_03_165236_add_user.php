<?php

use App\Models\Permission;
use App\Models\Role;
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
        //

        Schema::create('admin_users', function (Blueprint $table) {
            // Unique cluster link identifier
            $table->increments('id');

            $table->string('user_id');

            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();

            $table->string('type')->nullable();
            $table->longText('description')->nullable();

            $table->boolean('local')->nullable();
            $table->boolean('privileged')->nullable();

            $table->unsignedInteger('domain_id')->index('domain_id_fk_69385935')->nullable();

            // Soft delete and timestamp
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['domain_id', 'user_id', 'deleted_at'], 'domain_id_user_id_unique');
        });

        Schema::table('admin_users', function (Blueprint $table) {
            // link to domain
            $table->foreign('domain_id', 'domain_id_fk_69385935')->references('id')->on('domaine_ads')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // if not initial migration -> add permissions
        if (Permission::count() > 0) {

            // create new permissions
            $permissions = [
                [
                    'id' => '291',
                    'title' => 'admin_user_create',
                ],
                [
                    'id' => '292',
                    'title' => 'admin_user_edit',
                ],
                [
                    'id' => '293',
                    'title' => 'admin_user_show',
                ],
                [
                    'id' => '294',
                    'title' => 'admin_user_delete',
                ],
                [
                    'id' => '295',
                    'title' => 'admin_user_access',
                ],
            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([291, 292, 293, 294, 295], false);
            // User
            Role::findOrFail(2)->permissions()->sync([291, 292, 293, 294, 295], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([293, 295], false);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('admin_users');

        DB::delete('delete from permissions where id=291;');
        DB::delete('delete from permissions where id=292;');
        DB::delete('delete from permissions where id=293;');
        DB::delete('delete from permissions where id=294;');
        DB::delete('delete from permissions where id=295;');
    }
};
