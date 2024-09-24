<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

use App\Document;
use App\MApplication;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create icon_id
        Schema::table('m_applications', function (Blueprint $table) {
            $table->unsignedInteger('icon_id')->after("type")->nullable()->index('document_id_fk_4394343');
            $table->foreign('icon_id','document_id_fk_4394343')->references('id')->on('documents')->onUpdate('NO ACTION');
        });

        // Move icons to documents
        $applications = MApplication::whereNotNull("icon")->get();
        foreach($applications as $app) {

            // Decode icon
            $data = base64_decode($app->icon);

            // Create a new document
            $document = new Document();
            $document->filename = "icon.png";
            $document->mimetype = "image/png";
            $document->size = strlen($data);
            $document->hash = hash('sha256', $data);

            // Save the document
            $document->save();

            // Define storage filename
            $filePath = storage_path('docs') . '/' . $document->id;

            // save file
            File::put($filePath, $data);

            // Delete icon from application
            $app->icon_id=$document->id;
            $app->icon=null;
            $app->save();
        }

        // Drop icons
        Schema::table('m_applications', function (Blueprint $table) {
            $table->dropColumn("icon");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop incons_id
        Schema::table('m_applications', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_4394343');
            }
        });

        Schema::table('m_applications', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });

        // Creat icon field
        Schema::table('m_applications', function(Blueprint $table) {
            $table->mediumText('icon')->nullable()->after("name");
        });

    }
};
