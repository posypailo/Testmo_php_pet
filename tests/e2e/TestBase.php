<?php

namespace Tests\e2e;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Tests\e2e\utils\Logger;
use Qameta\Allure\Allure;

class TestBase extends TestCase {
    protected $driver;
    protected $logger;
    private $videoProcessId;
    private $videoPath;

    /**
     * Setup method to initialize WebDriver and log test start.
     */
    protected function setUp(): void {
        $this->logger = Logger::getInstance();
        $this->logger->info("Starting test: " . $this->name());

        try {
            // Initialize WebDriver
            $host = 'http://localhost:4444/wd/hub';
            $capabilities = DesiredCapabilities::chrome();
            $this->driver = RemoteWebDriver::create($host, $capabilities);
            $this->driver->manage()->window()->maximize();

            $this->logger->info("WebDriver initialized successfully.");

            // Start video recording
            $this->startVideoRecording();
        } catch (\Exception $e) {
            $this->logger->error("Error during WebDriver initialization: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Teardown method to close WebDriver, capture screenshot and attach video.
     */
    protected function tearDown(): void {
        $testName = $this->name();
        $timestamp = (new \DateTime())->format('Y-m-d_H-i-s');
        $screenshotDir = "build/allure-results/screenshots";
        $videoDir = "build/allure-results/videos";
        $screenshotCaptured = false;

        try {
            $testFailed = $this->status()->isFailure() || $this->status()->isError();

            // Ensure directories exist
            if (!is_dir($screenshotDir)) {
                mkdir($screenshotDir, 0777, true);
            }
            if (!is_dir($videoDir)) {
                mkdir($videoDir, 0777, true);
            }

            // Capture screenshot if the test failed
            if ($testFailed) {
                $screenshotPath = "{$screenshotDir}/{$testName}_{$timestamp}.png";

                if ($this->driver) {
                    $this->driver->takeScreenshot($screenshotPath);
                    $this->logger->info("Screenshot captured: $screenshotPath");

                    if (file_exists($screenshotPath)) {
                        $this->logger->info("Attaching screenshot to Allure report: $screenshotPath");
                        Allure::attachmentFile('Failure Screenshot', $screenshotPath, 'image/png');
                        $screenshotCaptured = true;
                    } else {
                        $this->logger->error("Screenshot file not found: $screenshotPath");
                    }
                } else {
                    $this->logger->error("WebDriver instance is not available. Cannot capture screenshot.");
                }
            }

            // Stop video recording and attach video to Allure
            $this->stopVideoRecording();
            if (file_exists($this->videoPath)) {
                $this->logger->info("Attaching video to Allure report: {$this->videoPath}");
                Allure::attachmentFile('Failure Video', $this->videoPath, 'video/mp4');
            }
        } catch (\Throwable $e) {
            $this->logger->error("Error during teardown: " . $e->getMessage());
        } finally {
            try {
                if ($this->driver) {
                    $this->driver->quit();
                    $this->logger->info("WebDriver session ended.");
                }
            } catch (\Throwable $e) {
                $this->logger->error("Error during WebDriver teardown: " . $e->getMessage());
            }

            try {
                parent::tearDown();
            } catch (\Throwable $e) {
                $this->logger->error("Error in parent tearDown: " . $e->getMessage());
            }
        }
    }

    /**
     * Start video recording using ffmpeg.
     */
    private function startVideoRecording(): void {
        $this->videoPath = "build/allure-results/videos/{$this->name()}.mp4";

        // Command to start video recording using ffmpeg
        $command = "ffmpeg -y -video_size 1920x1080 -f x11grab -i :0.0 -r 25 {$this->videoPath} > /dev/null 2>&1 & echo $!";
        $this->videoProcessId = trim(shell_exec($command));

        if ($this->videoProcessId) {
            $this->logger->info("Video recording started: {$this->videoPath} (PID: {$this->videoProcessId})");
        } else {
            $this->logger->error("Failed to start video recording.");
        }
    }

    /**
     * Stop video recording by killing the ffmpeg process.
     */
    private function stopVideoRecording(): void {
        if ($this->videoProcessId) {
            exec("kill {$this->videoProcessId}", $output, $returnVar);

            if ($returnVar === 0) {
                $this->logger->info("Video recording stopped: {$this->videoPath}");
            } else {
                $this->logger->error("Failed to stop video recording (PID: {$this->videoProcessId}).");
            }
        }
    }
}
