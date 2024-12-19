<?php

namespace Tests\e2e\utils;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Logger {
    private static $instance = null;
    private $logger;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {
        $this->logger = new MonologLogger('TestLogger');

        // Create a StreamHandler to write logs to a file
        $streamHandler = new StreamHandler('logs/test.log', MonologLogger::INFO);

        // Customize the log format
        $formatter = new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message%\n", // Format without context/extra
            null, // Date format
            true, // Allow inline line breaks
            true  // Ignore empty context and extra
        );
        $streamHandler->setFormatter($formatter);

        // Add the handler to the logger
        $this->logger->pushHandler($streamHandler);
    }

    /**
     * Get the Singleton instance of the Logger.
     *
     * @return Logger
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Log an informational message.
     *
     * @param string $message
     */
    public function info(string $message): void {
        $this->logger->info($message);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     */
    public function error(string $message): void {
        $this->logger->error($message);
    }
}
