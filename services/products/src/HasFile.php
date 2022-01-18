<?php

namespace App;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait HasFile
{
    public function generateName(UploadedFile $file)
    {
        return \uniqid() . '.' . $file->guessClientExtension();
    }

    public function getImagesPublicPath()
    {
        return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'images';
    }

    public function storeImage(UploadedFile $image): string
    {
        $imageName = $this->generateName($image);
        $imagePublicPath = DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $imageName;

        try {
            $image->move($this->getImagesPublicPath(), $imageName);
        } catch (FileException $e) {
            throw $e;
        }

        return $imagePublicPath;
    }

    public function deleteImage($target)
    {
        $filesystem = new Filesystem();
        $filesystem->remove(PUBLIC_PATH . $target);
    }
}
