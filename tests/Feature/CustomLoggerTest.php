<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Club;
use App\Services\CustomLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomLoggerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up log directories under storage/logs/clubs, storage/logs/users, storage/logs/guests
        $this->cleanUpLogs();
    }

    protected function tearDown(): void
    {
        $this->cleanUpLogs();
        parent::tearDown();
    }

    protected function cleanUpLogs(): void
    {
        $dirs = [
            storage_path('logs/clubs'),
            storage_path('logs/users'),
            storage_path('logs/guests'),
        ];

        foreach ($dirs as $dir) {
            if (file_exists($dir)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::CHILD_FIRST
                );

                foreach ($files as $fileinfo) {
                    $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                    @$todo($fileinfo->getRealPath());
                }
                @rmdir($dir);
            }
        }
    }

    public function test_logging_as_guest(): void
    {
        $exception = new \Exception("Test Guest Exception");
        CustomLogger::logException($exception);

        $guestLogFile = storage_path('logs/guests/error.log');
        $this->assertFileExists($guestLogFile);
        $content = file_get_contents($guestLogFile);
        $this->assertStringContainsString("Test Guest Exception", $content);
        $this->assertStringContainsString("guest", $content);
    }

    public function test_logging_as_authenticated_user_with_club(): void
    {
        // Create a club and user
        $club = Club::create([
            'name' => 'Test Club',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
            'club_id' => $club->id,
        ]);

        $this->actingAs($user);

        // Test exception log
        $exception = new \Exception("Test Authenticated Exception");
        CustomLogger::logException($exception);

        $clubLogFile = storage_path("logs/clubs/club_{$club->id}/error.log");
        $userLogFile = storage_path("logs/users/user_{$user->id}/error.log");

        $this->assertFileExists($clubLogFile);
        $this->assertFileExists($userLogFile);

        $clubContent = file_get_contents($clubLogFile);
        $this->assertStringContainsString("Test Authenticated Exception", $clubContent);
        $this->assertStringContainsString("testuser@example.com", $clubContent);
        $this->assertStringContainsString("Test Club", $clubContent);

        // Test message log
        CustomLogger::logMessage('info', 'Test Action Message', ['details' => 'some context']);

        $clubActivityFile = storage_path("logs/clubs/club_{$club->id}/activity.log");
        $userActivityFile = storage_path("logs/users/user_{$user->id}/activity.log");

        $this->assertFileExists($clubActivityFile);
        $this->assertFileExists($userActivityFile);

        $activityContent = file_get_contents($clubActivityFile);
        $this->assertStringContainsString("Test Action Message", $activityContent);
        $this->assertStringContainsString("INFO", $activityContent);
        $this->assertStringContainsString("some context", $activityContent);
    }
}
