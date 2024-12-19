<?php

namespace Tests\e2e\pages;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Tests\e2e\utils\Logger;
use Tests\e2e\utils\WaitUtils;

class BasePage {
    protected $driver;
    protected $waitUtils;
    protected $logger;

    public function __construct(RemoteWebDriver $driver) {
        $this->driver = $driver;
        $this->waitUtils = new WaitUtils($driver);
        $this->logger = Logger::getInstance();
    }

    /**
     * Navigate to a URL.
     *
     * @param string $url
     */
    public function navigateTo(string $url): void {
        $this->logger->info("Navigating to URL: $url");
        $this->driver->get($url);
    }

    /**
     * Click an element located by a specific selector.
     *
     * @param WebDriverBy $locator
     */
    public function clickElement(WebDriverBy $locator): void {
        $this->logger->info("Clicking on element: " . $locator->getValue());
        $this->waitUtils->waitForElementClickable($locator)->click();
    }

    /**
     * Send keys to an element located by a specific selector.
     *
     * @param WebDriverBy $locator
     * @param string $keys
     */
    public function sendKeys(WebDriverBy $locator, string $keys): void {
        $this->logger->info("Sending keys to element: " . $locator->getValue());
        $this->waitUtils->waitForElementVisible($locator)->sendKeys($keys);
    }

    /**
     * Verify that an element's text matches the expected text.
     *
     * @param WebDriverBy $locator
     * @param string $expectedText
     */
    public function verifyElementText(WebDriverBy $locator, string $expectedText): void {
        $this->logger->info("Verifying text of element: " . $locator->getValue());
        $actualText = $this->waitUtils->waitForElementVisible($locator)->getText();
        $this->logger->info("Expected: $expectedText, Actual: $actualText");

        if ($actualText !== $expectedText) {
            $this->logger->error("Text mismatch! Expected: $expectedText, Got: $actualText");
            throw new \Exception("Text mismatch! Expected: $expectedText, Got: $actualText");
        }
    }
}
