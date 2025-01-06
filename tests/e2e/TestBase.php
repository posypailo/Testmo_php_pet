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
    
        try {
            $testFailed = $this->status()->isFailure() || $this->status()->isError();
    
            $this->ensureDirectoryExists($screenshotDir);
            $this->ensureDirectoryExists($videoDir);
    
            if ($testFailed && $this->driver) {
                $screenshotPath = "{$screenshotDir}/{$testName}_{$timestamp}.png";
                $this->driver->takeScreenshot($screenshotPath);
                $this->logger->info("Screenshot captured: $screenshotPath");
    
                if (file_exists($screenshotPath)) {
                    $this->logger->info("Attaching screenshot to Allure report: $screenshotPath");
                    Allure::attachmentFile(
                        'Failure Screenshot', 
                        realpath($screenshotPath), 
                        'image/png'
                    );
                } else {
                    $this->logger->error("Screenshot file not found: $screenshotPath");
                }
            }
    
            $this->stopVideoRecording();
    
            if (file_exists($this->videoPath)) {
                $this->logger->info("Attaching video to Allure report: {$this->videoPath}");
                Allure::attachmentFile('Failure Video', realpath($this->videoPath), 'video/mp4');
            }                 
        } catch (\Throwable $e) {
            $this->logger->error("Error during teardown: " . $e->getMessage());
        } finally {
            if ($this->driver) $this->driver->quit();
            parent::tearDown();
        }
    }

    private function ensureDirectoryExists(string $dir): void {
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            $this->logger->error("Failed to create directory: {$dir}");
            throw new \RuntimeException("Directory creation failed: {$dir}");
        }
    }    

    /**
     * Start video recording using ffmpeg.
     */
    private function startVideoRecording(): void {
        $videoDir = "build/allure-results/videos";
        $this->ensureDirectoryExists($videoDir);
    
        $this->videoPath = "{$videoDir}/{$this->name()}.mp4";
        $outputFile = "{$videoDir}/{$this->name()}_ffmpeg.log";
    
        $command = "nohup ffmpeg -y -f avfoundation -framerate 15 -pix_fmt nv12 -i 3 -vf scale=1920:1080 -c:v libx264 -preset ultrafast -crf 28 -pix_fmt yuv420p -movflags +faststart {$this->videoPath} > {$outputFile} 2>&1 & echo $!";
        $this->videoProcessId = trim(shell_exec($command));
    
        if ($this->videoProcessId && is_numeric($this->videoProcessId) && posix_kill($this->videoProcessId, 0)) {
            $this->logger->info("Video recording started: {$this->videoPath} (PID: {$this->videoProcessId})");
        } else {
            $this->logger->error("Failed to start video recording. Check log: {$outputFile}");
            $this->videoProcessId = null;
        }
    }    

    /**
     * Stop video recording by killing the ffmpeg process.
     */
    private function stopVideoRecording(): void {
        if ($this->videoProcessId && is_numeric($this->videoProcessId) && posix_kill($this->videoProcessId, SIGTERM)) {
            sleep(1); // Allow ffmpeg to finalize the file
            if (!posix_kill($this->videoProcessId, 0)) {
                $this->logger->info("Video recording stopped gracefully: {$this->videoPath}");
                return;
            }
    
            exec("kill -9 {$this->videoProcessId}", $output, $returnVar);
            if ($returnVar === 0) {
                $this->logger->info("Video recording stopped forcefully: {$this->videoPath}");
            } else {
                $this->logger->error("Failed to stop video recording (PID: {$this->videoProcessId}).");
            }
        } else {
            $this->logger->error("No running video recording process found.");
        }
    }    
}
