<?php
include(__DIR__ . '/../../../inc/includes.php');
include_once(__DIR__ . '/../inc/dashboard.class.php');

Session::checkLoginUser();

if (!PluginMonpluginDashboard::canView()) {
    Html::displayRightError();
}

$plugin_version = defined('PLUGIN_MONPLUGIN_VERSION') ? PLUGIN_MONPLUGIN_VERSION : '2.0.0';
$map_css_file = __DIR__ . '/../css/map-style.css';
$geodash_vue_file = __DIR__ . '/../js/geodash-vue.js';

Html::header('Carte des sites CID', '', 'helpdesk', 'monplugin');

echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css?v='
    . htmlspecialchars($plugin_version, ENT_QUOTES, 'UTF-8')
    . '">' . "\n";
echo '<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css?v='
    . htmlspecialchars($plugin_version, ENT_QUOTES, 'UTF-8')
    . '">' . "\n";
echo '<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css?v='
    . htmlspecialchars($plugin_version, ENT_QUOTES, 'UTF-8')
    . '">' . "\n";

if (is_readable($map_css_file)) {
    echo '<style id="monplugin-map-style-inline">' . file_get_contents($map_css_file) . '</style>' . "\n";
}

echo '<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>' . "\n";
echo '<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>' . "\n";
echo '<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>' . "\n";
echo '<script src="https://unpkg.com/html2canvas@1.4.1/dist/html2canvas.min.js"></script>' . "\n";
echo '<script src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>' . "\n";

echo '<div class="page-header mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h2 class="page-title">
                <i class="ti ti-map-pin me-2" style="color:#d4521c"></i>
                Carte des sites CID
            </h2>
            <div class="text-muted mt-1" style="font-size:12px;">
                Supervision g&eacute;ographique en temps r&eacute;el
            </div>
        </div>
    </div>
</div>';

PluginMonpluginDashboard::renderPage();

if (is_readable($geodash_vue_file)) {
    echo '<script id="monplugin-geodash-vue-inline">'
        . str_replace('</script>', '<\/script>', file_get_contents($geodash_vue_file))
        . '</script>' . "\n";
} else {
    echo '<div class="alert alert-warning">Le fichier js/geodash-vue.js est introuvable dans le plugin.</div>';
}

Html::footer();
