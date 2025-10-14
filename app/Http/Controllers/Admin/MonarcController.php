<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Entity;
use App\Models\Information;
use App\Models\MApplication;
use App\Models\Process;
use App\Models\Site;
use App\Services\MonarcExportService;
use App\Services\MospService;

class MonarcController extends Controller
{
    public function __construct(
        private MospService $mosp,
        private MonarcExportService $exporter,
    ) {
    }

    public function index()
    {
        // abort_if(Gate::denies('activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::select('id', 'name')->orderBy('name')->get();
        $processes = Process::select('id', 'name')->orderBy('name')->get();
        $informations = Information::select('id', 'name')->orderBy('name')->get();
        $applications = MApplication::select('id', 'name')->orderBy('name')->get();

        $sites = Site::select('id', 'name')->orderBy('name')->get();
        $buildings = Building::select('id', 'name')->orderBy('name')->get();

        // Get all objects
        // https://objects.monarc.lu/api/v2/object
        // curl -X POST "https://objects.monarc.lu/api/v2/object/" -H  "accept: application/json" -H  "X-API-KEY: <your-token>" -H  "Content-Type: application/json" -d $object
        // $this->getNames();

        $referentials = $this->mosp->getReferentials(); // en mémoire (cache)
        // $assets       = $this->getSelectableAssets();    // à adapter à ton modèle
        // return view('monarc.create', compact(,'assets'));

        dd($referentials);

        return view(
            'monarc',
            compact(
                'referentials',
                // assets
                'entities',
                'processes',
                'informations',
                'applications',
                'sites',
                'buildings'
            )
        );
    }

    private function getNames()
    {
        $url = 'https://objects.monarc.lu/api/v2/object/';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
            // CURLOPT_SSL_VERIFYPEER => false, // ❗️TEMPORAIRE uniquement pour debug
            // CURLOPT_SSL_VERIFYHOST => false, // ❗️TEMPORAIRE uniquement pour debug
            CURLOPT_USERAGENT => 'Mercator', // contourne parfois les blocages
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
            ],
            // CURLOPT_HEADER => true,
            // CURLOPT_VERBOSE => true,
        ]);

        $response = curl_exec($ch);
        // $info = curl_getinfo($ch);
        $error = curl_error($ch);
        // $httpCode = $info['http_code'];

        if ($error) {
            \Log::error("Erreur cURL : {$error}");
        }

        curl_close($ch);

        // Vérification des erreurs
        if ($response === false) {
            echo 'Erreur cURL : '.curl_error($ch);
            curl_close($ch);

            return;
        }

        // Fermeture de cURL
        curl_close($ch);

        // Décodage de la réponse JSON
        $data = json_decode($response, true);
        dd($response);

        // Vérification de la structure de la réponse
        if (! is_array($data)) {
            echo 'Réponse invalide ou non JSON.';

            return null;
        }

        // Parcours des objets pour extraire les "name"
        foreach ($data as $bloc) {
            if (isset($bloc['data']) && is_array($bloc['data'])) {
                foreach ($bloc['data'] as $objet) {
                    if (isset($objet['name'])) {
                        // echo $objet['name'] . PHP_EOL;
                        $names->push($objet['name']);
                    }
                }
            }
        }
        dd($names);

        return $names;
    }
}
