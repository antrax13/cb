<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 28/02/2019
 * Time: 08:56
 */

namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    public function uploadFile(UploadedFile $uploadedFile, $destination):string
    {
        $fileExtension = $uploadedFile->guessExtension();
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
        $newFileName = Urlizer::urlize($originalFileName).'-'.md5(uniqid()) . '.' . $fileExtension;

        // moves the file to the directory where tags are stored
        $uploadedFile->move(
            $destination,
            $newFileName
        );

        return $newFileName;
    }

    public function deleteFile($path)
    {

        $result = unlink($path);
        if ($result === false) {
            throw new \Exception(sprintf('Error deleting "%s"', $path));
        }
    }
}