<?php

namespace Tests\e2e\components;

use Facebook\WebDriver\WebDriverBy;
use Tests\e2e\pages\BasePage;

class AddJobModal extends BasePage {
    // Selectors
    private $targetDropdown = '[data-controller="components--dropdown"]';
    private $firstDropdownOption = '[class*="dropdown__popup__menu"] [data-value="1"]';
    private $addJobButton = '[data-target="submitButton"]';

    /**
     * Clicks the dropdown to expand its options.
     */
    public function clickDropdown(): void {
        $this->clickElement(WebDriverBy::cssSelector($this->targetDropdown));
        $this->logger->info("Clicked on the target dropdown.");
    }

    /**
     * Clicks the first option in the dropdown.
     */
    public function clickFirstDropdownOption(): void {
        $this->clickElement(WebDriverBy::cssSelector($this->firstDropdownOption));
        $this->logger->info("Clicked on the first dropdown option.");
    }

    /**
     * Gets the text of the currently selected dropdown value.
     *
     * @return string The text of the selected dropdown value.
     */
    public function getDropdownText(): string {
        $text = $this->getElementText(WebDriverBy::cssSelector($this->targetDropdown));
        $this->logger->info("Fetched text from the dropdown: '$text'");
        return $text;
    }

    /**
     * Clicks the "Add Job" button.
     */
    public function clickAddJobButton(): void {
        $this->clickElement(WebDriverBy::cssSelector($this->addJobButton));
        $this->logger->info("Clicked the 'Add Job' button.");
    }
}
