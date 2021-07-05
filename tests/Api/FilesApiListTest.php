<?php

namespace EscolaLms\Files\Tests\Api;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FilesApiListTest extends TestCase
{
    private string $url = '/api/admin/file/list';
    private string $storagePath = '/storage';

    public function testDirectoryListMainDirectory()
    {
        $file1 = UploadedFile::fake()->image('test.png');
        $file2 = UploadedFile::fake()->create('test.txt', 3, 'text/plain');

        $directory = '/';
        $path = rtrim('/storage/'.$directory, '/');
        $this->disk->putFileAs($directory, $file1, $file1->getClientOriginalName());
        $this->disk->putFileAs($directory, $file2, $file2->getClientOriginalName());

        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => $directory,
            ],
            [],
            true
        );
        $response->assertOk();
        $response->assertJsonFragment(['data' => [
            [
                'name' => $file1->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file1->getCTime()),
                'mime' => $file1->getMimeType(),
                'url' => $path.'/'.$file1->getClientOriginalName(),
                'isDir' => false
            ],
            [
                'name' => $file2->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file2->getCTime()),
                'mime' => $file2->getMimeType(),
                'url' => $path.'/'.$file2->getClientOriginalName(),
                'isDir' => false
            ],
        ]]);
    }

    public function testDirectoryListGivenDirectory()
    {
        $file1 = UploadedFile::fake()->image('test.png');
        $file2 = UploadedFile::fake()->create('test.txt', 3, 'text/plain');

        $directory = '/test';
        $path = rtrim('/storage'.$directory, '/');
        $this->disk->makeDirectory('test');
        $this->disk->putFileAs($directory, $file1, $file1->getClientOriginalName());
        $this->disk->putFileAs($directory, $file2, $file2->getClientOriginalName());

        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => $directory,
            ],
            [],
            true
        );
        $response->assertOk();
        $response->assertJsonFragment(['data' => [
            [
                'name' => $file1->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file1->getCTime()),
                'mime' => $file1->getMimeType(),
                'url' => $path.'/'.$file1->getClientOriginalName(),
                'isDir' => false
            ],
            [
                'name' => $file2->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file2->getCTime()),
                'mime' => $file2->getMimeType(),
                'url' => $path.'/'.$file2->getClientOriginalName(),
                'isDir' => false
            ],
        ]]);
    }

    public function testRecursiveListInDirectory()
    {
        $file = UploadedFile::fake()->image('test.png');
        $fileName = $file->getClientOriginalName();

        $this->disk->makeDirectory('/directory/subdirectory', 0777, true, true);
        $this->disk->putFileAs('/directory', $file, $fileName);

        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => '/',
            ],
            [],
            true
        );
        $response->assertOk();
        $response->assertJsonFragment(['data' => ['data' => [
            [
                'name' => 'subdirectory',
                'created_at' => date(DATE_RFC3339, $file->getCTime()),
                'mime' => 'directory',
                'url' => $this->storagePath.'/directory/subdirectory',
                'isDir' => true
            ],
            [
                'name' => $fileName,
                'created_at' => date(DATE_RFC3339, $file->getCTime()),
                'mime' => $file->getMimeType(),
                'url' => $this->storagePath.'/directory/'.$file->getClientOriginalName(),
                'isDir' => false
            ],
        ]]]);
    }

    public function testListInvalidDirectory()
    {
        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => '../../',
            ],
            [],
            true
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
            ],
            [],
            true
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
            ],
            [],
            true
        );
        $response->assertStatus(302);
    }
}
