<?php

namespace App\Services;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
class FileUploader {
    private $container;

    /**
     * @var ContainerBag
     */
    public function __construct(ParameterBagInterface $container)
    {

        $this->container = $container;
    }

    public function uploadFiles(UploadedFile $file){
        $filename = md5(uniqid()) . "." . $file->guessClientExtension();

        $file->move(
            $this->container->get('uploads_dir'),
            $filename
        );

        return $filename;
//        ('uploads_dir')
    }
}
