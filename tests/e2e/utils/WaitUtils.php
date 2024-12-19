<?php

namespace Tests\e2e\utils;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class WaitUtils {
    private $driver;
    private $logger;

    public function __construct(RemoteWebDriver $driver) {
        $this->driver = $driver;
        $this->logger = Logger::getInstance();
    }

    /**
     * Wait for an element to be visible on the page.
     *
     * @param WebDriverBy $locator
     * @param int $timeout
     * @return \Facebook\WebDriver\WebDriverElement
     * @throws \Exception
     */
    public function waitForElementVisible(WebDriverBy $locator, int $timeout = 10) {
        try {
            $this->logger->info("Waiting for element to be visible: " . $locator->getValue());
            return $this->driver->wait($timeout, 500)
                ->until(WebDriverExpectedCondition::visibilityOfElementLocated($locator));
        } catch (\Exception $e) {
            $this->logger->error("Timeout waiting for element to be visible: " . $locator->getValue());
            throw $e;
        }
    }

    /**
     * Wait for an element to be clickable on the page.
     *
     * @param WebDriverBy $locator
     * @param int $timeout
     * @return \Facebook\WebDriver\WebDriverElement
     * @throws \Exception
     */
    public function waitForElementClickable(WebDriverBy $locator, int $timeout = 10) {
        try {
            $this->logger->info("Waiting for element to be clickable: " . $locator->getValue());
            return $this->driver->wait($timeout, 500)
                ->until(WebDriverExpectedCondition::elementToBeClickable($locator));
        } catch (\Exception $e) {
            $this->logger->error("Timeout waiting for element to be clickable: " . $locator->getValue());
            throw $e;
        }
    }

    /**
     * Wait for all elements matching a locator to be present on the page.
     *
     * @param WebDriverBy $locator
     * @param int $timeout
     * @return array
     * @throws \Exception
     */
    public function waitForElementsPresent(WebDriverBy $locator, int $timeout = 10): array {
        try {
            $this->logger->info("Waiting for elements to be present: " . $locator->getValue());
            return $this->driver->wait($timeout, 500)
                ->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy($locator));
        } catch (\Exception $e) {
            $this->logger->error("Timeout waiting for elements to be present: " . $locator->getValue());
            throw $e;
        }
    }
}
