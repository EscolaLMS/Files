<?php

namespace EscolaLms\Files\Tests\Api;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FilesApiListTest extends TestCase
{
    private string $url = '/api/file/list';

    public function testDirectoryList()
    {
        $file1 = UploadedFile::fake()->image('test.png');
        $file2 = UploadedFile::fake()->create('test.txt', 3, 'text/plain');

        $directory = '/';
        Storage::putFile($directory,$file1);
        Storage::putFile($directory,$file2);

        $response = $this->get(
            $this->url,
            [
                'directory' => $directory,
            ],
        );
        $response->assertOk();
        $response->assertJson([
            [
                'name' => $file1->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file1->getCTime()),
                'mime' => $file1->getMimeType(),
                'url' => rtrim($directory,'/').'/'.$file1->getClientOriginalName(),
            ],
            [
                'name' => $file2->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339),
                'mime' => 'application/json',
                'url' => rtrim($directory,'/').'/'.$file2->getClientOriginalName(),
            ],
        ]);
    }

    public function testDirectoryListInvalidCount()
    {
        $response = $this->get(
            $this->url,
            [
                'directory' => '/',
                'count' => -1
            ]
        );
        $response->assertStatus(302);
    }
}
