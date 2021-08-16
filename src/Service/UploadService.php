<?php


namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class UploadService
 * @package App\Service
 */
class UploadService implements UploadServiceInterface
{
    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UploadService constructor.
     * @param SluggerInterface $slugger
     * @param LoggerInterface $logger
     */
    public function __construct(SluggerInterface $slugger, LoggerInterface $logger)
    {
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function upload(UploadedFile $file, string $path): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $filename = $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();

        try {
            $file->move($path, $filename);
        } catch (FileException $exception) {
            $this->logger->error('Error :', [
                'exception' => $exception,
            ]);
        }

        return $filename;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $path, string $filename): void
    {
        unlink($path . '/' . $filename);
    }
}
