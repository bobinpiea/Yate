<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params; 

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // On donne un nouveau nom à l'image 
        $fichier = md5(uniqid(rand(), true )) . '.webp';

        // On récupère le chemin temporaire du fichier uploadé
        $path = $picture->getPathname();

        // On récupère les infos de l'image
        $picture_infos = getimagesize($path);

        if($picture_infos === false) {
            throw new Exception('Format d\'image incorrect');
        }

        // On vérifie le format de l'image
        switch($picture_infos['mime']){
            case 'image/png':
                $picture_source = imagecreatefrompng($path);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($path);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($path);
                break;  
            default : 
                throw new Exception('Format d\'image incorrect');                                                                  
        }

        /** ALLER VOIR LE TUTO POUR MIEUX COMPRENDRE
        * https://www.youtube.com/watch?v=axbLC9PqzfE */     

        // On recadre l'image 
        // Et on récupère les dimensions
        $imageWidth =  $picture_infos[0];
        $imageHeight =  $picture_infos[1];       
            
        // On verifie l'orientation de l'image
        // Portrait ou carré  
        switch($imageWidth <=> $imageHeight){
            case -1: // Portrait 
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break; 
            case 0: // Carré 
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;                        
                break;                        
            case 1: // Paysage 
                $squareSize = $imageHeight;
                $src_y = ($imageWidth - $squareSize) / 2;                    
                $src_x= 0;
            break;              
        }

        // On crée une nouvelle image "vierge"
        $resized_picture = imagecreatetruecolor($width, $height);

        imagecopyresampled(
            $resized_picture, 
            $picture_source,  
            0, 0,
            $src_x, $src_y, 
            $width, $height, 
            $squareSize, $squareSize
        );

        $pathFolder = $this->params->get('images_directory') . $folder; 
            
        // On crée le dossier de destination s'il n'existe pas 
        if(!file_exists($pathFolder . '/mini/')){
            mkdir($pathFolder . '/mini/' ,  0755, true);
        }
            
        // On stocke l'image recadrée
        imagewebp(
            $resized_picture, 
            $pathFolder . '/mini/' . $width . 'x' . $height . '-' . $fichier
        );

        // On stocke aussi l'image originale (non redimensionnée)
        $picture->move($pathFolder . '/' , $fichier);

        return $fichier;
    }

    // On fait aussi une suppression !
    public function delete(string $fichier, ?string $folder = '',
        ?int $width = 250, ?int $height = 250)
    {
        if($fichier !== 'default.webp'){
            $success = false;
            $path =  $this->params->get('images_directory'). $folder; 
                
            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier ;

            // Suppression de la miniature
            if(file_exists($mini)){
                unlink($mini);
                $success = true;
            }

            // Suppression de l'original
            $original = $path . '/' .  $fichier;
            if(file_exists($original)){
                unlink($original);
                $success = true;
            }

            return $success;
        }

        return false; 
    }
}