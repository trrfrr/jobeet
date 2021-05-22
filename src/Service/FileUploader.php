<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @return string
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file):string {
        $fileName= md5(uniqid().'.'.$file->guessExtension());
        $file-> move($this->targetDirectory,$fileName);
        return  $fileName;
    }

}