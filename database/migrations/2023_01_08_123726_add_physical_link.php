<?php

use App\Role;
use App\Permission;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhysicalLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Craate the physical_link table
        Schema::create('physical_links', function (Blueprint $table) {
            // Unique physical link identifier
            $table->increments('id');

            // Source and destination ports
            $table->string('src_port')->nullable()->default(null);
            $table->string('dest_port')->nullable()->default(null);
            /*
            Note:
            Some switches do use letters to label their ports instead of numbers. For example,
            some switches use a combination of letters and numbers to label their ports. For example,
            a switch might have ports labeled "G1", "G2", "G3", and so on, where the "G" stands for
            "gigabit" and indicates that the port is a high-speed port. Other switches might use letters
            to indicate the type of port, such as "F" for fiber optic ports or "T" for copper twisted
            pair ports.

            It's also worth noting that some switches use a combination of letters and numbers to label
            their ports, such as "Gi1", "Gi2", "Gi3", and so on, where the "Gi" stands for "gigabit interface"
            and indicates that the port is a high-speed port.
            */

            // Source objects
            $table->unsignedInteger('peripheral_src_id')->nullable()->default(null)->index('peripheral_src_id_fk');
            $table->foreign('peripheral_src_id', 'peripheral_src_id_fk')->references('id')->on('peripherals')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('phone_src_id')->nullable()->default(null)->index('phone_src_id_fk');
            $table->foreign('phone_src_id', 'phone_src_id_fk')->references('id')->on('phones')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('physical_router_src_id')->nullable()->default(null)->index('physical_router_src_id_fk');
            $table->foreign('physical_router_src_id', 'physical_router_src_id_fk')->references('id')->on('physical_routers')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('physical_security_device_src_id')->nullable()->default(null)->index('physical_security_device_src_id_fk');
            $table->foreign('physical_security_device_src_id', 'physical_security_device_src_id_fk')->references('id')->on('physical_security_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('physical_server_src_id')->nullable()->default(null)->index('physical_server_src_id_fk');
            $table->foreign('physical_server_src_id', 'physical_server_src_id_fk')->references('id')->on('physical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('physical_switch_src_id')->nullable()->default(null)->index('physical_switch_src_id_fk');
            $table->foreign('physical_switch_src_id', 'physical_switch_src_id_fk')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('storage_device_src_id')->nullable()->default(null)->index('storage_device_src_id_fk');
            $table->foreign('storage_device_src_id', 'storage_device_src_id_fk')->references('id')->on('storage_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('wifi_terminal_src_id')->nullable()->default(null)->index('wifi_terminal_src_id_fk');
            $table->foreign('wifi_terminal_src_id', 'wifi_terminal_src_id_fk')->references('id')->on('wifi_terminals')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('workstation_src_id')->nullable()->default(null)->index('workstation_src_id_fk');
            $table->foreign('workstation_src_id', 'workstation_src_id_fk')->references('id')->on('workstations')->onUpdate('NO ACTION')->onDelete('CASCADE');

            // Destination objects
            $table->unsignedInteger('peripheral_dest_id')->nullable()->default(null)->index('peripheral_dest_id_fk');
            $table->foreign('peripheral_dest_id', 'peripheral_dest_id_fk')->references('id')->on('peripherals')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('phone_dest_id')->nullable()->default(null)->index('phone_dest_id_fk');
            $table->foreign('phone_dest_id', 'phone_dest_id_fk')->references('id')->on('phones')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('physical_router_dest_id')->nullable()->default(null)->index('physical_router_dest_id_fk');
            $table->foreign('physical_router_dest_id', 'physical_router_dest_id_fk')->references('id')->on('physical_routers')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('physical_security_device_dest_id')->nullable()->default(null)->index('physical_security_device_dest_id_fk');
            $table->foreign('physical_security_device_dest_id', 'physical_security_device_dest_id_fk')->references('id')->on('physical_security_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('physical_server_dest_id')->nullable()->default(null)->index('physical_server_dest_id_fk');
            $table->foreign('physical_server_dest_id', 'physical_server_dest_id_fk')->references('id')->on('physical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('physical_switch_dest_id')->nullable()->default(null)->index('physical_switch_dest_id_fk');
            $table->foreign('physical_switch_dest_id', 'physical_switch_dest_id_fk')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('storage_device_dest_id')->nullable()->default(null)->index('storage_device_dest_id_fk');
            $table->foreign('storage_device_dest_id', 'storage_device_dest_id_fk')->references('id')->on('storage_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('wifi_terminal_dest_id')->nullable()->default(null)->index('wifi_terminal_dest_id_fk');
            $table->foreign('wifi_terminal_dest_id', 'wifi_terminal_dest_id_fk')->references('id')->on('wifi_terminals')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('workstation_dest_id')->nullable()->default(null)->index('workstation_dest_id_fk');
            $table->foreign('workstation_dest_id', 'workstation_dest_id_fk')->references('id')->on('workstations')->onUpdate('NO ACTION')->onDelete('CASCADE');

            // Soft delete and timestamp
            $table->timestamps();
            $table->softDeletes();
        });
        // Access rights

        // if not initial migration -> add permissions
        if (Permission::count()>0) {
            // create new permissions
            $permissions = [
                [
                    'id'    => '263',
                    'title' => 'physical_link_create',
                ],
                [
                    'id'    => '264',
                    'title' => 'physical_link_edit',
                ],
                [
                    'id'    => '265',
                    'title' => 'physical_link_show',
                ],
                [
                    'id'    => '266',
                    'title' => 'physical_link_delete',
                ],
                [
                    'id'    => '267',
                    'title' => 'physical_link_access',
                ],
            ];
            Permission::insert($permissions);

            // Add permissions in roles :
            // Admin
            Role::findOrFail(1)->permissions()->sync([263,264,265,266,267], false);
            // User
            Role::findOrFail(2)->permissions()->sync([263,264,265,266,267], false);
            // Auditor
            Role::findOrFail(3)->permissions()->sync([265,267], false);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('physical_links');

        DB::delete('delete from permissions where id=263;');
        DB::delete('delete from permissions where id=264;');
        DB::delete('delete from permissions where id=265;');
        DB::delete('delete from permissions where id=266;');
        DB::delete('delete from permissions where id=267;');
    }
}
