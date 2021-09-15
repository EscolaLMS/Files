<?php

namespace EscolaLms\Files\Http\Requests;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;


class FilesApiFindByNameTest extends TestCase
{
    private string $url = '/api/admin/file/find';

    /**
     * @test
     */
    public function testFindFilesByNameEquals()
    {
        $file = UploadedFile::fake()->create('test-name-equals.txt', 3, 'text/plain');

        $directory1 = '/';
        $path1 = rtrim('/storage/'.$directory1, '/');
        $this->disk->putFileAs($directory1, $file, $file->getClientOriginalName());

        $directory2 = '/directory2';
        $path2 = rtrim('/storage'.$directory2, '/');
        $this->disk->putFileAs($directory2, $file, $file->getClientOriginalName());


        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => '/',
                'name' => 'test'
            ],
            [],
            true
        );
        $response->assertOk();

        $response->assertJsonFragment(['data' => [
            [
                'name' => $file->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file->getCTime()),
                'mime' => $file->getMimeType(),
                'url' => $path1.'/'.$file->getClientOriginalName(),
                'isDir' => false
            ],
            [
                'name' => $file->getClientOriginalName(),
                'created_at' => date(DATE_RFC3339, $file->getCTime()),
                'mime' => $file->getMimeType(),
                'url' => $path2.'/'.$file->getClientOriginalName(),
                'isDir' => false
            ]
        ]]);
    }

    /**
     * @test
     */
    public function testFindFilesByNameContains()
    {
        $filename = 'test-name-contains.txt';
        $file = UploadedFile::fake()->create($filename, 3, 'text/plain');

        $directory1 = '/c/directoryC1';
        $path1 = rtrim('/storage'.$directory1, '/');
        $this->disk->putFileAs($directory1, $file, $file->getClientOriginalName());

        $directory2 = '/c/directoryC2/directory';
        $path2 = rtrim('/storage'.$directory2, '/');
        $this->disk->putFileAs($directory2, $file, $file->getClientOriginalName());


        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => '/c',
                'name' => 'tes'
            ],
            [],
            true
        );
        $response->assertOk();

        $response->assertJsonFragment(['data' => [
            [
                'name' => $filename,
                'created_at' => date(DATE_RFC3339, $file->getCTime()),
                'mime' => $file->getMimeType(),
                'url' => $path1.'/'.$file->getClientOriginalName(),
                'isDir' => false
            ],
            [
                'name' => $filename,
                'created_at' => date(DATE_RFC3339, $file->getCTime()),
                'mime' => $file->getMimeType(),
                'url' => $path2.'/'.$file->getClientOriginalName(),
                'isDir' => false
            ]
        ]]);
    }

    public function testListInvalidDirectory()
    {
        $response = $this->getWithQuery(
            $this->url,
            [
                'directory' => '../../',
                'name' =>'name'
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
                'name' =>'name',
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
                'name' =>'name',
                'page' => -1,
            ],
            [],
            true
        );
        $response->assertStatus(302);
    }

}
