<?php

namespace Backend\HealthCheck;

use mysqli;
use mysqli_sql_exception;

/**
 * HealthCheck class performs system health checks, including database connectivity.
 */
class HealthCheck
{
    /**
     * Formats the duration into a string.
     *
     * @param  float $duration The duration in seconds.
     * @return string Returns the formatted duration.
     */
    private function formatDuration(float $duration): string
    {
        return str_pad((string)(int)($duration * 1000000), 7, '0', STR_PAD_LEFT);
    }

    /**
     * Gets the maximum duration from an array of HealthCheckResult objects.
     *
     * @param  array<string, HealthCheckResult|null> $results Array of HealthCheckResult objects.
     * @return float The maximum duration in seconds.
     */
    private function getMaxDuration(array $results): float
    {
        $maxDuration = 0;

        foreach ($results as $result) {
            if ($result !== null) {
                $duration = (float)$result->duration / 1000000;
                if ($duration > $maxDuration) {
                    $maxDuration = $duration;
                }
            }
        }

        return $maxDuration;
    }

    /**
     * Checks if any of the HealthCheckResult objects in the array have an 'unhealthy' status.
     *
     * @param  array<string, HealthCheckResult|null> $results Array of HealthCheckResult objects.
     * @return bool True if any result has an 'unhealthy' status, otherwise false.
     */
    private function hasUnhealthyStatus(array $results): bool
    {
        foreach ($results as $result) {
            if ($result !== null && $result->status === 'unhealthy') {
                return true;
            }
        }
        return false;
    }

    /**
     * Adds entries to the response array based on the HealthCheckResult objects.
     *
     * @param  array<string, HealthCheckResult|null> $results Array of HealthCheckResult objects.
     * @param  array<string, array<string, string>>  $entries Array to which the entries will be added.
     * @return array<string, array<string, string>> Returns the updated entries array.
     */
    private function addEntries(array $results, array $entries): array
    {
        foreach ($results as $key => $result) {
            if ($result !== null) {
                $entries[$key] = [
                    'duration' => $result->duration,
                    'status' => $result->status,
                    'message' => $result->message
                ];
            }
        }
        return $entries;
    }

    /**
     * Performs the health check for the database.
     *
     * @return array<string, mixed> Returns an array with the overall status,
     *                       total duration, and individual entries for database connectivity.
     */
    public function performHealthCheck(): array
    {
        $response = [
            'status' => 'ok',
            'total-duration' => 0,
            'entries' => []
        ];

        $response['entries'] = $this->addEntries([], $response['entries']);

        return $response;
    }
}
