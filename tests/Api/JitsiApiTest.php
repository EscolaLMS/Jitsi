<?php

namespace EscolaLms\Jitsi\Tests\Api;

use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Jitsi\Services\FileService;
use EscolaLms\Jitsi\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JitsiApiTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, DatabaseTransactions, WithFaker;

    private array $body;
    private string $url;

    protected function setUp(): void
    {
        parent::setUp();

        $recordingSessionId = Str::uuid()->toString();

        $this->url = "https://localhost/test-app-id/{$recordingSessionId}/nagrywaniewideo_consultations_11_1728385200_2024-10-08-11-35-05.mp4";

        $this->body = [
            "eventType" => "RECORDING_UPLOADED",
            "sessionId" => Str::uuid()->toString(),
            "timestamp" => 1728387319418,
            "fqn" => "test-app-id/nagrywaniewideo_consultations_11_1728385200",
            "idempotencyKey" => Str::uuid()->toString(),
            "customerId" => "66d15721481e4cbbac651b1658dff55c",
            "appId" => "test-app-id",
            "data" => [
                "participants" => [
                    [
                        "name" => "admin.escolalms",
                        "id" => "auth0|66dffe0e58e320bf6575969c"
                    ]
                ],
                "share" => true,
                "initiatorId" => "auth0|66dffe0e58e320bf6575969c",
                "durationSec" => 2,
                "startTimestamp" => 1728387309767,
                "endTimestamp" => 1728387311997,
                "recordingSessionId" => $recordingSessionId,
                "preAuthenticatedLink" => $this->url,
            ]
        ];
    }

    public function testSaveRecordedVideoInvalidAppId(): void
    {
        Config::set('jitsi.app_id', 'test-app-id');

        $this->body['appId'] = 'wrong-app-id';

        $this->json('POST', '/api/jitsi/recorded-video', $this->body)
            ->assertUnprocessable();
    }

    public function testSaveRecordedVideo(): void
    {
        Config::set('jitsi.app_id', 'test-app-id');

        $this->mockGetFileContent('nagrywaniewideo_consultations_11_1728385200_2024-10-08-11-35-05.mp4');

        $this->json('POST', '/api/jitsi/recorded-video', $this->body)->assertOk();

        Storage::assertExists('consultations/11/1728385200/1728387309767.mp4');
    }

    public function testSaveRecordedVideoNoSuffix(): void
    {
        Config::set('jitsi.app_id', 'test-app-id');

        $this->body['fqn'] = "test-app-id/nagrywaniewideo";

        $this->mockGetFileContent('nagrywaniewideo_2024-10-08-11-35-05.mp4');

        $this->json('POST', '/api/jitsi/recorded-video', $this->body)->assertOk();

        Storage::assertExists('jitsi/videos/nagrywaniewideo/1728387309767.mp4');
    }

    public function testSaveRecordedVideoDifferentSuffix(): void
    {
        Config::set('jitsi.app_id', 'test-app-id');

        $this->body['fqn'] = "test-app-id/nagrywaniewideo_webinar_20";

        $this->mockGetFileContent('nagrywaniewideo_webinar_20_2024-10-08-11-35-05.mp4');

        $this->json('POST', '/api/jitsi/recorded-video', $this->body)->assertOk();

        Storage::assertExists('webinar/20/1728387309767.mp4');
    }

    private function mockGetFileContent(string $filename): void
    {
        $this->mock(FileService::class, function ($mock) use ($filename) {
            $mock->shouldReceive('getFileFromUrl')->with($this->url)->andReturn(UploadedFile::fake()->create($filename, 50, 'video/mp4'));
        });
    }
}
