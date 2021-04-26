<?php

namespace EscolaLms\Files\Tests\Api;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class FilesApiTest extends TestCase
{
    function testThatTrueIsNotFalse(): void {
        $this->assertTrue(!false);
    }

    public function testSingleFileUpload()
    {
        $target = '/';
        $file = UploadedFile::fake()->image('file');
        $response = $this->post(
            '/api/file/upload',
            [
                'file'=>[$file],
                'target'=>$target,
            ],
        );
        $response->assertStatus(200);
        $this->assertFileExists($file->getClientOriginalName());
    }
}
