<?php

namespace Tests\e2e;

use Tests\e2e\pages\LoginPage;
use Tests\e2e\pages\UserProfilePage;
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
    public function logIn(StepContextInterface $context): void {
        $credentials = json_decode(file_get_contents(__DIR__ . '/data/credentials.json'), true);
        $email = $credentials['email'];
        $password = $credentials['password'];

        $context->parameter('Email', $email);

        $loginPage = new LoginPage($this->driver);
        $loginPage->openPage();
        $loginPage->login($email, $password);

        $userProfilePage = new UserProfilePage($this->driver);
        $userProfilePage->verifyHeaderTitle('Candidate AP Test');
    }

    #[DisplayName('Add Automation Job')]
    public function addAutomationJob(StepContextInterface $context): void {
        $jobsPage = new JobsPage($this->driver);
        $jobsPage->openPage();
        $jobsPage->clickAddAutomationJob();

        $jobsPage->addJobModal->clickDropdown();
        $jobsPage->addJobModal->clickFirstDropdownOption();

        $this->dropdownText = $jobsPage->addJobModal->getDropdownText();
        $this->expectedText = explode(' ', trim($this->dropdownText))[0];

        $context->parameter('Dropdown Text', $this->dropdownText);
        $context->parameter('Expected Text', $this->expectedText);

        $jobsPage->addJobModal->clickAddJobButton();
    }

    #[DisplayName('Verify Automation Job')]
    public function verifyAutomationJob(StepContextInterface $context): void {
        $context->parameter('Expected Text', $this->expectedText);

        $jobsPage = new JobsPage($this->driver);
        $jobsPage->queuedJobsTable->verifyProgressIconVisible();
        $jobsPage->queuedJobsTable->verifyJobTitle($this->expectedText);
        $jobsPage->queuedJobsTable->verifyConnectionTitle($this->expectedText);
    }
}
