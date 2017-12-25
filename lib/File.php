<?php
class File {
    public static function build_path($path_array) {
        // $ROOT_FOLDER (sans slash à la fin) vaut
        // "/home/ann2/votre_login/public_html/TD5" à l'IUT
        $DS = DIRECTORY_SEPARATOR; // DS contient le slash des chemins de fichiers, c'est-à-dire '/' sur Linux et '\' sur Windows
        $ROOT_FOLDER = __DIR__ . $DS . '..'; // __DIR__ est une constante "magique" de PHP qui contient le chemin du dossier courant, on doit donc revenir en arrière de un dossier car File.php se trouve dans le dossier lib
        return $ROOT_FOLDER. $DS . join($DS, $path_array);
    }
}
