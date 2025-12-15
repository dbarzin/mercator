<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
         * lawfulness (recommandé, clair et conforme à la terminologie RGPD)
         *
         * a)	Consentement de la personne concernée
         *      lawfulness_consent
         *      Base légale : consentement explicite de la personne
         * b)	Exécution d’un contrat ou mesures précontractuelles
         *      lawfulness_contract
         *      Traitement nécessaire à un contrat avec la personne
         * c)	Respect d’une obligation légale
         *      lawfulness_legal_obligation
         *      Obligations légales imposées au responsable du traitement
         * d)	Sauvegarde des intérêts vitaux
         *      lawfulness_vital_interest
         *      Protection de la vie ou santé de la personne ou d’autrui
         * e)	Mission d’intérêt public ou autorité publique
         *      lawfulness_public_interest
         *      Mission d’intérêt public ou exercice d’une autorité officielle
         * f)	Intérêts légitimes du responsable ou d’un tiers
         *      lawfulness_legitimate_interest
         *      Intérêts légitimes, sauf si droits/freedoms de la personne prévalent
         */

        Schema::table('data_processing', function (Blueprint $table) {
            $table->text('lawfulness')->nullable()->after('purpose');
            $table->boolean('lawfulness_legitimate_interest')->nullable()->after('lawfulness');
            $table->boolean('lawfulness_public_interest')->nullable()->after('lawfulness');
            $table->boolean('lawfulness_vital_interest')->nullable()->after('lawfulness');
            $table->boolean('lawfulness_legal_obligation')->nullable()->after('lawfulness');
            $table->boolean('lawfulness_contract')->nullable()->after('lawfulness');
            $table->boolean('lawfulness_consent')->nullable()->after('lawfulness');
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_processing', function (Blueprint $table) {
            $table->dropColumn('lawfulness');
            $table->dropColumn('lawfulness_legitimate_interest');
            $table->dropColumn('lawfulness_public_interest');
            $table->dropColumn('lawfulness_vital_interest');
            $table->dropColumn('lawfulness_legal_obligation');
            $table->dropColumn('lawfulness_contract');
            $table->dropColumn('lawfulness_consent');
        });
    }
};
