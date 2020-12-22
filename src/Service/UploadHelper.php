<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadHelper
{

    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    /**
     * @var string
     */
    private string $uploadDirectory;

    public function __construct(string $uploadDirectory, SluggerInterface $slugger) {
        $this->slugger = $slugger;
        $this->uploadDirectory = $uploadDirectory;
    }

    /**
     * Saves the uploaded file to the uploads directory
     *
     * @param File $file
     * @return string
     */
    public function uploadPostPhoto(File $file): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        } else {
            $originalFilename = $file->getFilename();
        }

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $file->move(
            $this->uploadDirectory,
            $newFilename
        );

        return $newFilename;
    }
}