<?php

namespace App\Services;

use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CustomLogger
{
    /**
     * Log an exception.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public static function logException(Throwable $e): void
    {
        // Check if a user is authenticated
        $user = Auth::user();
        
        $userId = $user ? $user->id : 'guest';
        $userEmail = $user ? $user->email : 'guest';
        $userName = $user ? $user->name : 'Guest';
        $club = $user ? $user->club : null;
        $clubId = $user ? $user->club_id : null;

        $logData = [
            'timestamp'   => now()->toDateTimeString(),
            'url'         => Request::fullUrl(),
            'method'      => Request::method(),
            'ip'          => Request::ip(),
            'user'        => [
                'id'    => $userId,
                'name'  => $userName,
                'email' => $userEmail,
            ],
            'club'        => $club ? [
                'id'   => $club->id,
                'name' => $club->name,
            ] : null,
            'exception'   => get_class($e),
            'message'     => $e->getMessage(),
            'file'        => $e->getFile(),
            'line'        => $e->getLine(),
            'trace'       => collect($e->getTrace())
                ->take(15)
                ->map(fn($t) => ($t['file'] ?? 'unknown') . ':' . ($t['line'] ?? 'unknown'))
                ->toArray(),
        ];

        // Format as JSON or a pretty string
        $formattedLog = json_encode($logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL . str_repeat('=', 80) . PHP_EOL;

        // Log to school (club) directory if club_id exists
        if ($clubId) {
            $clubLogDir = storage_path("logs/clubs/club_{$clubId}");
            if (!file_exists($clubLogDir)) {
                mkdir($clubLogDir, 0755, true);
            }
            file_put_contents("{$clubLogDir}/error.log", $formattedLog, FILE_APPEND);
        }

        // Log to user directory if it's an authenticated user
        if ($user) {
            $userLogDir = storage_path("logs/users/user_{$userId}");
            if (!file_exists($userLogDir)) {
                mkdir($userLogDir, 0755, true);
            }
            file_put_contents("{$userLogDir}/error.log", $formattedLog, FILE_APPEND);
        } else {
            // General guest/unauthenticated exceptions log
            $guestLogDir = storage_path("logs/guests");
            if (!file_exists($guestLogDir)) {
                mkdir($guestLogDir, 0755, true);
            }
            file_put_contents("{$guestLogDir}/error.log", $formattedLog, FILE_APPEND);
        }
    }

    /**
     * Log a custom manual message (info/warning/error).
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array   $context
     * @return void
     */
    public static function logMessage(string $level, string $message, array $context = []): void
    {
        $user = Auth::user();
        
        $userId = $user ? $user->id : 'guest';
        $userEmail = $user ? $user->email : 'guest';
        $userName = $user ? $user->name : 'Guest';
        $club = $user ? $user->club : null;
        $clubId = $user ? $user->club_id : null;

        $logData = [
            'timestamp' => now()->toDateTimeString(),
            'level'     => strtoupper($level),
            'message'   => $message,
            'url'       => Request::fullUrl(),
            'method'    => Request::method(),
            'ip'        => Request::ip(),
            'user'      => [
                'id'    => $userId,
                'name'  => $userName,
                'email' => $userEmail,
            ],
            'club'      => $club ? [
                'id'   => $club->id,
                'name' => $club->name,
            ] : null,
            'context'   => $context,
        ];

        $formattedLog = json_encode($logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL . str_repeat('=', 80) . PHP_EOL;

        if ($clubId) {
            $clubLogDir = storage_path("logs/clubs/club_{$clubId}");
            if (!file_exists($clubLogDir)) {
                mkdir($clubLogDir, 0755, true);
            }
            file_put_contents("{$clubLogDir}/activity.log", $formattedLog, FILE_APPEND);
        }

        if ($user) {
            $userLogDir = storage_path("logs/users/user_{$userId}");
            if (!file_exists($userLogDir)) {
                mkdir($userLogDir, 0755, true);
            }
            file_put_contents("{$userLogDir}/activity.log", $formattedLog, FILE_APPEND);
        } else {
            $guestLogDir = storage_path("logs/guests");
            if (!file_exists($guestLogDir)) {
                mkdir($guestLogDir, 0755, true);
            }
            file_put_contents("{$guestLogDir}/activity.log", $formattedLog, FILE_APPEND);
        }
    }
}
