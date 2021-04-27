<?php

namespace EscolaLms\Files\Tests\Api;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FilesApiListTest extends TestCase
{
    public function testMainDirectoryList()
    {
        $directory = '/';
        $file1 = UploadedFile::fake()->image('test.png');
        Storage::putFile($directory,$file1);
        $response = $this->get(
            '/api/file/list',
            [
                'directory' => $directory,
            ],
        );
        $response->assertOk();
        $response->assertJson([
            ['name'=>'test.png', 'created_at'=>date(DATE_RFC3339),'mime'=>'image/png', 'url'=>'/test.png'],
            ['name'=>'test.json', 'created_at'=>date(DATE_RFC3339),'mime'=>'application/json', 'url'=>'/test.json'],
        ]);
    }
}
