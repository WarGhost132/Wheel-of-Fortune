<?php

namespace Backend\HealthCheck;

/**
 * Class representing the status of a health check.
 */
class HealthCheckResult
{
    public string $status;
    public string $message;
    public string $duration;

    /**
     * Constructor for the HealthCheckResult class.
     *
     * @param string $status   The status of the health check.
     * @param string $message  The message associated with the health check.
     * @param string $duration The duration of the health check.
     */
    public function __construct(string $status, string $message, string $duration)
    {
        $this->status = $status;
        $this->message = $message;
        $this->duration = $duration;
    }
}
