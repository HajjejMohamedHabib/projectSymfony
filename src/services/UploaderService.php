<?php

namespace App\services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderService
{
public function __construct(private SluggerInterface $slugger)
{

}
public function uploadFile(UploadedFile $file,string $directory):string{
    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    // this is needed to safely include the file name as part of the URL
    $safeFilename = $this->slugger->slug($originalFilename);
    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    try {
        $file->move($directory, $newFilename);
    } catch (FileException $e) {
        echo $e->getMessage();// ... handle exception if something happens during file upload
    }
    return $newFilename;
}
}