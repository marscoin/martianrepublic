<?php

namespace Tests\Feature;

use App\Http\Controllers\ContentApiController;
use App\Includes\AppHelper;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CitizenshipVideoValidationTest extends TestCase
{
    private array $temporaryFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->temporaryFiles as $file) {
            unlink($file);
        }
        parent::tearDown();
    }

    private function upload(string $name, string $bytes): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'citizen-video-');
        $this->temporaryFiles[] = $path;
        file_put_contents($path, $bytes);

        return new UploadedFile($path, $name, null, null, true);
    }

    public function test_native_video_container_headers_pass_mime_validation(): void
    {
        // These headers exercise finfo's container detection; they are not
        // playable recordings and are never sent to the pinning service.
        foreach ([
            'capture.mp4' => '00000018667479706d703432000000006d70343269736f6d',
            'capture.MOV' => '0000001466747970717420200000000071742020',
        ] as $name => $header) {
            $result = AppHelper::validateUploadedFile($this->upload($name, hex2bin($header)), AppHelper::CITIZENSHIP_VIDEO_TYPES);
            $this->assertTrue($result['valid']);
        }
    }

    public function test_native_formats_still_reject_mime_mismatches_and_oversized_files(): void
    {
        $mismatch = AppHelper::validateUploadedFile($this->upload('capture.mp4', 'not a video'), AppHelper::CITIZENSHIP_VIDEO_TYPES);
        $this->assertFalse($mismatch['valid']);
        $this->assertStringContainsString('MIME', $mismatch['error']);

        $oversized = AppHelper::validateUploadedFile($this->upload('capture.mov', str_repeat('x', 5242881)), AppHelper::CITIZENSHIP_VIDEO_TYPES);
        $this->assertFalse($oversized['valid']);
        $this->assertStringContainsString('5MB', $oversized['error']);
    }

    public function test_missing_video_is_reported_as_a_validation_failure(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(new GenericUser(['id' => 1]));
        $request = Request::create('/api/pinvideo', 'POST', ['address' => 'MTestVideoAddress']);

        $response = (new ContentApiController)->pinvideo($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame(['error' => 'No valid file provided.'], json_decode($response->getContent(), true));
    }
}
