<?php
abort_if(Gate::denies('configure'), 403, '403 Forbidden');

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fonction pour obtenir les informations PHP
function getPhpInfo() {
    ob_start();
    phpinfo();
    $phpinfo = ob_get_contents();
    ob_end_clean();

    $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
    return $phpinfo;
}

// Fonction pour obtenir les informations sur les extensions chargées
function getLoadedExtensions() {
    $extensions = get_loaded_extensions();
    return implode(', ', $extensions);
}

// Fonction pour obtenir les informations sur Xdebug
function getXdebugInfo() {
    if (function_exists('xdebug_info')) {
        return xdebug_info();
    } else {
        return "Xdebug n'est pas installé ou activé.";
    }
}

?>
<html>
<body>
    <h1>Informations de Débogage du Serveur et de Laravel</h1>

    <h2>Informations PHP</h2>
    <div><?php echo getPhpInfo(); ?></div>

    <h2>Extensions Chargées</h2>
    <pre><?php echo getLoadedExtensions(); ?></pre>

    <h2>Informations Xdebug</h2>
    <pre><?php echo getXdebugInfo(); ?></pre>
</body>
</html>
