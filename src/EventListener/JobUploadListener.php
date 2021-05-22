<?php


namespace App\EventListener;


use App\Entity\Jobs;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JobUploadListener
{
    private $uploader;

    /**
     * JobUploadListener constructor.
     * @param $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args){
        $enitiy =$args->getEntity();
        $this->uploadFile($enitiy);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args){
        $entity=$args->getEntity();
        $this->uploadFile($entity);
    }

    /**
     * @param $enitiy
     */
    private function uploadFile($enitiy){
        if(!$enitiy instanceof Jobs){
            return;
        }
        $logoFile=$enitiy->getLogo();
         if($logoFile instanceof UploadedFile){
             $fileName=$this->uploader->upload($logoFile);
             $enitiy->setLogo($fileName);
         }
    }

}