<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface UploadServiceInterface
 * @package App\Service
 */
interface UploadServiceInterface
{
    /**
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    public function upload(UploadedFile $file, string $path): string;

    /**
     * @param string $path
     * @param string $filename
     */
    public function remove(string $path, string $filename): void;
}
