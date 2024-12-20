<?php

namespace Tests\e2e\components;

use Facebook\WebDriver\WebDriverBy;
use Tests\e2e\pages\BasePage;

class QueuedJobsTable extends BasePage {
    private $progressIcon = '.automation-jobs-queue-view-busy';
    private $jobTitle = '[class="table__field__text"] [data-action="view"]';
    private $connectionTitle = '[data-name="queuedJobs"] [class="table__field__image-text__text"]';

    /**
     * Verifies that the progress icon is visible.
     *
     * @throws \Exception If the progress icon is not visible.
     */
    public function verifyProgressIconVisible(): void {
        $this->waitUtils->waitForElementVisible(WebDriverBy::cssSelector($this->progressIcon));
        $this->logger->info("Progress icon is visible.");
    }

    /**
     * Verifies the job title matches the expected value.
     *
     * @param string $expectedTitle The expected job title.
     * @throws \Exception If the job title does not match the expected value.
     */
    public function verifyJobTitle(string $expectedTitle): void {
        $this->verifyElementText(WebDriverBy::cssSelector($this->jobTitle), $expectedTitle);
    }

    /**
     * Verifies the connection title matches the expected value.
     *
     * @param string $expectedTitle The expected connection title.
     * @throws \Exception If the connection title does not match the expected value.
     */
    public function verifyConnectionTitle(string $expectedTitle): void {
        $this->verifyElementText(WebDriverBy::cssSelector($this->connectionTitle), $expectedTitle);
    }
}
