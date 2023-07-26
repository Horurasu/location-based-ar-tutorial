
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el nombre de la nueva página desde el formulario
    $nombre_pagina = $_POST['nombre_pagina'];
    // Crear una carpeta para la nueva página
    mkdir('nuevas_paginas/' . $nombre_pagina, 0777, true);
    // Crear el archivo HTML para la nueva página
    $ruta_pagina = 'nuevas_paginas/' . $nombre_pagina . '/' . $nombre_pagina . '.html';
    $contenido_pagina = '<!DOCTYPE html>
<html>
<head>
    <title>' . $nombre_pagina . '</title>
</head>
<body>
    <h1>' . $nombre_pagina . '</h1>
    <p>Esta es una nueva página generada dinámicamente.</p>
</body>
</html>';
    file_put_contents($ruta_pagina, $contenido_pagina);

    echo '<p>Página generada con éxito.</p>';
}
?>
