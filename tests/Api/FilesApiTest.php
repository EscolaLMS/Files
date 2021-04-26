<?php

namespace EscolaLms\Files\Tests\API;

use EscolaLms\Files\Tests\TestCase;
use Illuminate\Http\UploadedFile;

class FilesApiTest extends TestCase
{
    function testThatTrueIsNotFalse(): void {
        $this->assertTrue(!false);
    }

    public function testSingleFileUpload()
    {
        $name = 'photo1.jpg';
        $file = UploadedFile::fake()->image($name);
        $response = $this->post(
            '/api/file/upload',
            [
                'file[]'=>$file,
                'target'=>$name
            ],
        );
        $response->assertStatus(200);
        $this->assertFileExists('photo1.jpg');
    }
}
