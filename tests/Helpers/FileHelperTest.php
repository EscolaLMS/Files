<?php

namespace EscolaLms\Files\Tests\Helpers;

use EscolaLms\Files\Helpers\FileHelper;
use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHelperTest extends TestCase
{
    public function testGetFilePath(): void
    {
        Storage::fake();
        $path = 'images';
        $file = 'test.jpg';
        $fullPath = $path . DIRECTORY_SEPARATOR . $file;
        Storage::makeDirectory($path);
        copy(__DIR__ . '/../mocks/test.jpg', Storage::path($fullPath));
        Storage::assertExists($fullPath);

        $pathToSavedFile = FileHelper::getFilePath(UploadedFile::fake()->image('newFile.jpg'));
        Storage::assertExists($pathToSavedFile);

        $this->assertEquals($fullPath, FileHelper::getFilePath($fullPath));
        $this->assertNull(FileHelper::getFilePath($file));
    }
}
