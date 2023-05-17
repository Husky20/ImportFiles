<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private string $csvDirectory;

    public function __construct(string $csvDirectory)
    {
        $this->csvDirectory = $csvDirectory;
    }

    public function saveFile(UploadedFile $file): void
    {
        $filename = $file->getClientOriginalName();
        $file->move($this->csvDirectory, $filename);
    }
}
