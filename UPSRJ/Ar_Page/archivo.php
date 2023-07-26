<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedAsset = $_POST["asset"];
    $nombreCarpeta = "output_" . time(); // Nombre de la carpeta basado en la marca de tiempo para evitar conflictos de nombres.

    // Crear la carpeta de destino para almacenar los archivos.
    mkdir($nombreCarpeta);

    // Copiar el archivo .gltf seleccionado en la carpeta de destino.
    $rutaAsset = "assets/{$selectedAsset}.gltf";
    $rutaDestino = "{$nombreCarpeta}/{$selectedAsset}.gltf";
    copy($rutaAsset, $rutaDestino);

    // Crear el contenido de la página HTML.
    $contenidoHTML = <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <script src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
        <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>
        <script src="https://raw.githack.com/donmccurdy/aframe-extras/master/dist/aframe-extras.loaders.min.js"></script>
        <script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-detector.js"></script>
        <script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-handler.js"></script>
    </head>
    <body style="margin: 0; overflow: hidden;">
        <a-scene vr-mode-ui="enabled: false;" loading-screen="enabled: false;" renderer="logarithmicDepthBuffer: true;" arjs="trackingMethod: best; sourceType: webcam; debugUIEnabled: false;" id="scene" embedded gesture-detector>
            <a-assets>
                <a-asset-item id="animated-asset" src="{$rutaDestino}">
                </a-asset-item>
            </a-assets>

            <a-marker id="animated-marker" type="pattern" preset="custom" url="assets/marker.patt" raycaster="objects: .clickable" emitevents="true" cursor="fuse: false; rayOrigin: mouse;" id="markerA">
                <a-entity id="bowser-model" scale="0.010298811946484904 0.010298811946484904 0.010298811946484904" rotation="270 -45 0" animation-mixer="loop: repeat" gltf-model="#animated-asset" class="clickable" gesture-handler>
		            <a-animation attribute="rotation" to="360 0 0" dur="3000" easing="linear" repeat="indefinite"></a-animation>
		        </a-entity>
            </a-marker>
            <a-entity camera></a-entity>
        </a-scene>
    </body>
</html>
EOD;

    // Crear y descargar el archivo HTML.
    $rutaArchivoHTML = "{$nombreCarpeta}/index.html";
    file_put_contents($rutaArchivoHTML, $contenidoHTML);

    // Comprimir los archivos en un archivo zip.
    $zip = new ZipArchive();
    $nombreZip = "{$nombreCarpeta}.zip";
    $zip->open($nombreZip, ZipArchive::CREATE);
    $zip->addFile($rutaArchivoHTML, 'index.html');
    $zip->addFile($rutaDestino, "{$selectedAsset}.gltf");
    $zip->close();

    // Descargar el archivo zip.
    header("Content-Disposition: attachment; filename={$nombreZip}");
    header("Content-Type: application/zip");
    header("Content-Length: " . filesize($nombreZip));
    readfile($nombreZip);

    // Eliminar los archivos temporales.
    unlink($nombreZip);
    unlink($rutaArchivoHTML);
    unlink($rutaDestino);
    rmdir($nombreCarpeta);

    exit;
}
?>
