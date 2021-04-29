<?php

namespace EscolaLms\Files\Tests\Api;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class FilesApiListTest extends TestCase
{
    private string $url = '/api/file/list';

    public function testDirectoryListMainDirectory()
    {
        $file1 = UploadedFile::fake()->image('test.png');
        $file2 = UploadedFile::fake()->create('test.txt', 3, 'text/plain');

        $directory = '/';
        $path = rtrim('/storage/'.$directory,'/');
        $this->disk->putFileAs($directory,$file1, $file1->getClientOriginalName());
        $this->disk->putFileAs($directory,$file2, $file2->getClientOriginalName());

        $response = $this->getWithQuery(
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
                'url' => $path.'/'.$file1->getClientOriginalName(),
            ],
            [
                'name' => $file2->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file2->getCTime()),
                'mime' => $file2->getMimeType(),
                'url' => $path.'/'.$file2->getClientOriginalName(),
            ],
        ]);
    }

    public function testDirectoryListGivenDirectory()
    {
        $file1 = UploadedFile::fake()->image('test.png');
        $file2 = UploadedFile::fake()->create('test.txt', 3, 'text/plain');

        $directory = '/test';
        $path = rtrim('/storage'.$directory,'/');
        $this->disk->makeDirectory('test');
        $this->disk->putFileAs($directory,$file1, $file1->getClientOriginalName());
        $this->disk->putFileAs($directory,$file2, $file2->getClientOriginalName());

        $response = $this->getWithQuery(
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
                'url' => $path.'/'.$file1->getClientOriginalName(),
            ],
            [
                'name' => $file2->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file2->getCTime()),
                'mime' => $file2->getMimeType(),
                'url' => $path.'/'.$file2->getClientOriginalName(),
            ],
        ]);
    }

    public function testListInvalidDirectory()
    {
        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => '../../',
            ]
        );
        $response->assertStatus(405);
    }

    public function testListInvalidPerPage()
    {
        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => '/',
                'perPage' => -1
            ]
        );
        $response->assertStatus(302);
    }

    public function testListInvalidPage()
    {
        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => '/',
                'page' => -1,
            ]
        );
        $response->assertStatus(302);
    }
}
