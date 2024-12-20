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
        $this->driver->get($url);
        $this->logger->info("Navigating to URL: $url");
    }

    /**
     * Click an element located by a specific selector.
     *
     * @param WebDriverBy $locator
     */
    public function clickElement(WebDriverBy $locator): void {
        $this->waitUtils->waitForElementClickable($locator)->click();
        $this->logger->info("Clicking on element: " . $locator->getValue());
    }

    /**
     * Send keys to an element located by a specific selector.
     *
     * @param WebDriverBy $locator
     * @param string $keys
     */
    public function sendKeys(WebDriverBy $locator, string $keys): void {
        $this->waitUtils->waitForElementVisible($locator)->sendKeys($keys);
        $this->logger->info("Sending keys to element: " . $locator->getValue());
    }

    /**
    * Verifies that the text of an element matches the expected value.
    *
    * This method logs the actual and expected text values, performs an assertion to validate the match,
    * and throws an exception with detailed information if the assertion fails.
    *
    * @param WebDriverBy $locator The locator for the element to verify.
    * @param string $expectedText The expected text value.
    *
    * @throws \PHPUnit\Framework\ExpectationFailedException If the text does not match the expected value.
    */

    public function verifyElementText(WebDriverBy $locator, string $expectedText): void {
        $actualText = $this->waitUtils->waitForElementVisible($locator)->getText();
        $this->logger->info("Verifying text of element: " . $locator->getValue());
        $this->logger->info("Expected: '$expectedText', Actual: '$actualText'");
    
        try {
            \PHPUnit\Framework\Assert::assertEquals(
                $expectedText,
                $actualText,
                "Text mismatch! Expected: '$expectedText', Got: '$actualText'"
            );
        } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
