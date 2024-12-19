<?php

namespace Tests\e2e;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Tests\e2e\utils\Logger;

class TestBase extends TestCase {
    protected $driver;
    protected $logger;

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
        } catch (\Exception $e) {
            $this->logger->error("Error during WebDriver initialization: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Teardown method to close WebDriver and log test completion or failure.
     */
    protected function tearDown(): void {
        $testName = $this->name();
        $screenshotCaptured = false;
    
        try {
            $testFailed = $this->status()->isFailure() || $this->status()->isError();
    
            if ($testFailed) {
                $screenshotPath = "screenshots/{$testName}.png";
    
                $this->logger->error("Test failed: $testName. Attempting to capture screenshot...");
                if ($this->driver) {
                    $this->driver->takeScreenshot($screenshotPath);
                    $this->logger->info("Screenshot captured: $screenshotPath");
                    $screenshotCaptured = true;
                } else {
                    $this->logger->error("WebDriver instance is not available. Cannot capture screenshot.");
                }
            }
        } catch (\Throwable $e) {
            $this->logger->error("Error during screenshot capture: " . $e->getMessage());
        }
    
        try {
            if ($this->driver) {
                $this->driver->quit();
                $this->logger->info("WebDriver session ended.");
            }
        } catch (\Throwable $e) {
            $this->logger->error("Error during WebDriver teardown: " . $e->getMessage());
        } finally {
            try {
                parent::tearDown();
            } catch (\Throwable $e) {
                $this->logger->error("Error in parent tearDown: " . $e->getMessage());
            }
        }
    
        if ($testFailed && !$screenshotCaptured) {
            $this->logger->error("Screenshot could not be captured for failed test: $testName.");
        }
    }        
}
