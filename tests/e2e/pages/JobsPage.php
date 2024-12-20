<?php

namespace Tests\e2e\pages;

use Tests\e2e\config\Constants;
use Facebook\WebDriver\WebDriverBy;
use Tests\e2e\components\AddJobModal;
use Tests\e2e\components\QueuedJobsTable;

class JobsPage extends BasePage {
    private const URL = Constants::BASE_URL . '/automation/jobs/10';

    private $addAutomationJobButton = '[data-target*="addButton"]';

    public $queuedJobsTable;
    public $addJobModal;

    public function __construct($driver) {
        parent::__construct($driver);

        $this->queuedJobsTable = new QueuedJobsTable($driver);
        $this->addJobModal = new AddJobModal($driver);
    }

    public function openPage(): void {
        $this->navigateTo(self::URL);
    }

    public function clickAddAutomationJob(): void {
        $this->clickElement(WebDriverBy::cssSelector($this->addAutomationJobButton));
    }
}
