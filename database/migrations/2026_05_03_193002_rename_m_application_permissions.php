<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('permissions')
            ->where('title', 'like', 'm_application_%')
            ->get()
            ->each(function ($permission): void {
                DB::table('permissions')
                    ->where('id', $permission->id)
                    ->update(['title' => str_replace('m_application_', 'application_', $permission->title)]);
            });
    }

    public function down(): void
    {
        DB::table('permissions')
            ->where('title', 'like', 'application_%')
            ->whereIn('title', ['application_create', 'application_edit', 'application_show', 'application_delete', 'application_access'])
            ->get()
            ->each(function ($permission): void {
                DB::table('permissions')
                    ->where('id', $permission->id)
                    ->update(['title' => str_replace('application_', 'm_application_', $permission->title)]);
            });
    }
};
