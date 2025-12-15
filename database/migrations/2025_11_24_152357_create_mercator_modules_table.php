<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mercator_modules', function (Blueprint $table) {
            $table->string('name')->primary(); // ex: "firewall"
            $table->string('label');
            $table->string('version');
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mercator_modules');
    }
};
