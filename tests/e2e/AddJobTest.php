<?php

namespace Tests\e2e;

use Tests\e2e\utils\Facades;
use Tests\e2e\pages\JobsPage;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\DisplayName;
use Qameta\Allure\Attribute\Description;
use Qameta\Allure\Attribute\Severity;
use Qameta\Allure\Attribute\Owner;
use Qameta\Allure\Attribute\Epic;
use Qameta\Allure\Attribute\Feature;
use Qameta\Allure\Attribute\Story;
use Qameta\Allure\StepContextInterface;

#[Epic('Automation')]
#[Feature('Job Management')]
#[Story('Add Automation Job')]
class AddJobTest extends TestBase {
    private $dropdownText;
    private $expectedText;
    private $jobsPage;

    private $jobsPage = new JobsPage($this->driver);

    #[DisplayName('Add Automation Job Test')]
    #[Description('This test verifies the addition of an automation job.')]
    #[Severity(Severity::CRITICAL)]
    #[Owner('Artem Posypailo')]
    public function testAddAutomationJob() {
        Allure::runStep([$this, 'logIn']);
        Allure::runStep([$this, 'addAutomationJob']);
        Allure::runStep([$this, 'verifyAutomationJob']);
    }

    #[DisplayName('Log in')]
    public function logIn(): void {
        $facades = new Facades($this->driver);
        $facades->authorize();
        $facades->verifyUserProfileHeader();
    }

    #[DisplayName('Add Automation Job')]
    public function addAutomationJob(StepContextInterface $context): void {
        $this->jobsPage->openPage();
        $this->jobsPage->clickAddAutomationJob();

        $this->jobsPage->addJobModal->clickDropdown();
        $this->jobsPage->addJobModal->clickFirstDropdownOption();

        $this->dropdownText = $this->jobsPage->addJobModal->getDropdownText();
        $this->expectedText = explode(' ', trim($this->dropdownText))[0];

        $context->parameter('Dropdown Text', $this->dropdownText);
        $context->parameter('Expected Text', $this->expectedText);

        $this->jobsPage->addJobModal->clickAddJobButton();
    }

    #[DisplayName('Verify Automation Job')]
    public function verifyAutomationJob(StepContextInterface $context): void {
        $context->parameter('Expected Text', $this->expectedText);

        $this->jobsPage->queuedJobsTable->verifyProgressIconVisible();
        $this->jobsPage->queuedJobsTable->verifyJobTitle($this->expectedText);
        $this->jobsPage->queuedJobsTable->verifyConnectionTitle($this->expectedText);
    }
}
