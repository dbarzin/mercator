<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('workstations', function (Blueprint $table) {
            $table->unsignedInteger('entity_id')->nullable()->after('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('other_user')->nullable();
            $table->string('status')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('last_inventory_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->unsignedInteger('domain_id')->nullable();
            $table->string('warranty')->nullable();
            $table->date('warranty_start_date')->nullable();
            $table->string('warranty_period')->nullable();
            $table->string('agent_version')->nullable();
            $table->string('update_source')->nullable();
            $table->unsignedInteger('network_id')->nullable();
            $table->string('network_port_type')->nullable();
            $table->string('mac_address')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('fin_value', 12, 2)->nullable();

            // Foreign key constraints
            $table->foreign('entity_id')->references('id')->on('entities');
            $table->foreign('user_id')->references('id')->on('admin_users');
            $table->foreign('domain_id')->references('id')->on('domaine_ads');
            $table->foreign('network_id')->references('id')->on('networks');
        });
    }

    public function down()
    {
        Schema::table('workstations', function (Blueprint $table) {
            $table->dropForeign(['entity_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['domain_id']);
            $table->dropForeign(['network_id']);

            $table->dropColumn([
                'entity_id',
                'user_id',
                'other_user',
                'status',
                'manufacturer',
                'model',
                'serial_number',
                'last_inventory_date',
                'warranty_end_date',
                'domain_id',
                'warranty',
                'warranty_start_date',
                'warranty_period',
                'agent_version',
                'update_source',
                'network_id',
                'network_port_type',
                'mac_address',
                'purchase_date',
                'fin_value',
            ]);
        });
    }
};
