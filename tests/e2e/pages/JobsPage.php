<?php

namespace Tests\e2e\pages;

use Tests\e2e\config\Constants;
use Facebook\WebDriver\WebDriverBy;

class JobsPage extends BasePage {
    private const URL = Constants::BASE_URL . '/automation/jobs/10';

    private $addAutomationJobButton = '[data-target*="addButton"]';

    public function openPage(): void {
        $this->navigateTo(self::URL);
    }

    public function clickAddAutomationJob(): void {
        $this->clickElement(WebDriverBy::cssSelector($this->addAutomationJobButton));
    }
}
