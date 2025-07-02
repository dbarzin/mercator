<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename permission from data_processing_register to data_processing to match class name
        DB::table('permissions')->where('title', 'data_processing_register_create')->update(['title' => 'data_processing_create']);
        DB::table('permissions')->where('title', 'data_processing_register_edit')->update(['title' => 'data_processing_edit']);
        DB::table('permissions')->where('title', 'data_processing_register_show')->update(['title' => 'data_processing_show']);
        DB::table('permissions')->where('title', 'data_processing_register_delete')->update(['title' => 'data_processing_delete']);
        DB::table('permissions')->where('title', 'data_processing_register_access')->update(['title' => 'data_processing_access']);

        DB::table('permissions')->where('title', 'security_controls_create')->update(['title' => 'security_control_create']);
        DB::table('permissions')->where('title', 'security_controls_edit')->update(['title' => 'security_control_edit']);
        DB::table('permissions')->where('title', 'security_controls_show')->update(['title' => 'security_control_show']);
        DB::table('permissions')->where('title', 'security_controls_delete')->update(['title' => 'security_control_delete']);
        DB::table('permissions')->where('title', 'security_controls_access')->update(['title' => 'security_control_access']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert migration
        DB::table('permissions')->where('title', 'data_processing_create')->update(['title' => 'data_processing_register_create']);
        DB::table('permissions')->where('title', 'data_processing_edit')->update(['title' => 'data_processing_register_edit']);
        DB::table('permissions')->where('title', 'data_processing_show')->update(['title' => 'data_processing_register_show']);
        DB::table('permissions')->where('title', 'data_processing_delete')->update(['title' => 'data_processing_register_delete']);
        DB::table('permissions')->where('title', 'data_processing_access')->update(['title' => 'data_processing_register_access']);

        DB::table('permissions')->where('title', 'security_control_create')->update(['title' => 'security_controls_create']);
        DB::table('permissions')->where('title', 'security_control_edit')->update(['title' => 'security_controls_edit']);
        DB::table('permissions')->where('title', 'security_control_show')->update(['title' => 'security_controls_show']);
        DB::table('permissions')->where('title', 'security_control_delete')->update(['title' => 'security_controls_delete']);
        DB::table('permissions')->where('title', 'security_control_access')->update(['title' => 'security_controls_access']);

    }
};
