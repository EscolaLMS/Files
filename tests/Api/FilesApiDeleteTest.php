<?php

namespace EscolaLms\Files\Tests\Api;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class FilesApiDeleteTest extends TestCase
{
    private string $url = '/api/file/delete';

    public function testDeleteFromMainDirectory()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->disk->putFileAs('/',$file, $file->getClientOriginalName());
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/'.$file->getClientOriginalName()]);
        $response->assertOk();
    }

    public function testDeleteFromSubdirectory()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->disk->putFileAs('/directory',$file, $file->getClientOriginalName());
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/directory/'.$file->getClientOriginalName()]);
        $response->assertOk();
    }

    public function testDeleteNonExistentFile()
    {
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/missing.txt']);
        $response->assertStatus(400);
    }

    public function testDeleteOutOfBounds()
    {
        $response = $this->deleteWithQuery($this->url, ['url'=>'/storage/../oauth-private.key']);
        $response->assertStatus(405);
    }
}
