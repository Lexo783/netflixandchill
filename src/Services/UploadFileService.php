<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadFileService
{
    private $targetDirectory;
    private SluggerInterface $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, String $type): FileException|\Exception|string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory($type), $fileName);
        } catch (FileException $e) {
            return $e;
        }

        return $fileName;
    }

    public function getTargetDirectory($type): string
    {
        $path = match ($type) {
            'profil' => "/profil/pictures",
            'movies' => "/movies",
            default => "",
        };

        return $this->targetDirectory.$path;
    }
}