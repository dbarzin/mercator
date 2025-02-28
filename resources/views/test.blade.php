@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-body">

    Fuseau horaire : {{ config('app.timezone') }}<br>
    Langue : {{ config('app.locale') }}<br>

    panel.date_format = {{ config('panel.date_format') }} <br>
    panel.time_format = {{ config('panel.time_format') }}

    <hr>

    <?php
    // Activer l'affichage des erreurs pour le débogage
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    use Carbon\Carbon;

    // Fonction pour tester le parsing de date
    function testDateParsing($dateString) {
        try {
            $date = Carbon::parse($dateString);
            echo "Date parsée avec succès : " . $date->toDateTimeString() . "<br>";
        } catch (Exception $e) {
            echo "Erreur lors du parsing de la date : " . $e->getMessage() . "<br>";
        }
    }

    // Exemple de chaînes de date à tester
    $dateStrings = [
        '2023-10-05',
        '05-10-2023',
        '2023-10-05 14:30:00',
        'date invalide',
    ];

    foreach ($dateStrings as $dateString) {
        echo "Test de la chaîne de date : " . htmlspecialchars($dateString) . "<br>";
        testDateParsing($dateString);
        echo "<hr>";
    }

    ?>

    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection
