<?php
namespace App\Classes;

class GestionnaireFichier {
    private $upload_dir = "/home/ubuntu/FasiChatClassRoom/uploads/";
    private $max_size = 20 * 1024 * 1024; // 20 Mo

    public function upload($file, $type) {
        if ($file['size'] > $this->max_size) {
            throw new \Exception("Fichier trop volumineux (Max 20Mo)");
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        $target = $this->upload_dir . $type . "s/" . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                $this->compressImage($target);
            }
            return $target;
        }
        return false;
    }

    private function compressImage($path) {
        // Utilisation de la bibliothèque GD comme demandé
        $info = getimagesize($path);
        if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($path);
        elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($path);
        
        if (isset($image)) {
            // Compression à 60% de qualité
            imagejpeg($image, $path, 60);
            imagedestroy($image);
        }
    }
}
