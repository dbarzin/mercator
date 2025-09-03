<?php

namespace App\Http\Controllers\Admin;

// Framework
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActivityRequest;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CVEController extends Controller
{
    public function search(Request $request)
    {
        $provider = config('mercator-config.cve.provider');
        if ($provider===null)
            return back()->withErrors('CVE Provider not set');


        // Request
        $client = curl_init($provider . '/api/vulnerability/cpesearch/' . $request->cpe);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($client);

        if ($response === false) {
            return back()->withErrors('CVESearch - Could not query the provider - '.$provider);
        }

        // JSON
        $json = json_decode($response);
        $cves = [];
        foreach($json->cvelistv5 as $cve) {
            $cves [] = (object) [
                "cveId" => $cve->cveMetadata->cveId,
                "title" => $cve->containers->cna->title ?? '',
                "description" => $cve->containers->cna->descriptions[0]->value ?? '',
                "url" => $cve->containers->cna->references[0]->url ?? '',
                "name" => $cve->containers->cna->references[0]->name ?? '',
                "datePublished" => substr($cve->cveMetadata->datePublished,0,10) ?? '',
                "dateUpdated" => substr($cve->cveMetadata->dateUpdated,0,10) ?? '',
                "baseScore" =>
                    $cve->containers->cna->metrics[0]->cvssV3_0->baseScore  ??
                    $cve->containers->cna->metrics[0]->cvssV3_1->baseScore  ??
                    $cve->containers->adp[0]->metrics[0]->cvssV3_1->baseScore ??
                    '',
                "baseSeverity" =>
                    $cve->containers->cna->metrics[0]->cvssV3_0->baseSeverity  ??
                    $cve->containers->cna->metrics[0]->cvssV3_1->baseSeverity ??
                    $cve->containers->adp[0]->metrics[0]->cvssV3_1->baseSeverity ??
                    '',
            ];
        }

        return view('admin.cve.show', compact('cves'));
    }

}
