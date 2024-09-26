<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Building;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('buildings', function(Blueprint $table) {
            $table->string('attributes')->after('description')->nullable();
        });

        $buildings = Building::All();
        foreach($buildings as $building) {
            $building->attributes = "";
            if ($building->camera)
                $building->attributes .= "Camera ";
            if ($building->badge)
                $building->attributes .= "Badge";
            $building->save();
        }

        Schema::table('buildings', function(Blueprint $table) {
            $table->dropColumn('camera');
            $table->dropColumn('badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('buildings', function(Blueprint $table) {
            $table->dropColumn('attributes');
            $table->boolean('camera')->nullable();
            $table->boolean('badge')->nullable();
        });
    }
};
