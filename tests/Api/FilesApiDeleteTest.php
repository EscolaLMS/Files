<?php

namespace EscolaLms\Files\Tests\Api;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class FilesApiDeleteTest extends TestCase
{
    private string $url = '/api/admin/file/delete';

    public function testDeleteFileFromMainDirectory()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->disk->putFileAs('/', $file, $file->getClientOriginalName());
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/'.$file->getClientOriginalName()], [], true);
        $response->assertOk();
    }

    public function testDeleteFileFromSubdirectory()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->disk->putFileAs('/directory', $file, $file->getClientOriginalName());
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/directory/'.$file->getClientOriginalName()], [], true);
        $response->assertOk();
    }

    public function testDeleteDirectoryWithFiles()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->disk->putFileAs('/directory', $file, $file->getClientOriginalName());
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/directory'], [], true);
        $response->assertOk();
    }

    public function testDeleteNonExistentFile()
    {
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/missing.txt'], [], true);
        $response->assertStatus(400);
    }

    public function testDeleteOutOfBounds()
    {
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/../oauth-private.key'], [], true);
        $response->assertStatus(405);
    }
}
