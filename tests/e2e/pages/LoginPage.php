<?php

namespace Tests\e2e\pages;

use Tests\e2e\config\Constants;
use Facebook\WebDriver\WebDriverBy;

class LoginPage extends BasePage {
    private const URL = Constants::BASE_URL . '/auth/login';

    private $emailField = '[name="email"]';
    private $passwordField = '[name="password"]';
    private $loginButton = '[type="submit"]';

    public function openPage(): void {
        $this->navigateTo(self::URL);
    }

    public function login(string $email, string $password): void {
        $this->sendKeys(WebDriverBy::cssSelector($this->emailField), $email);
        $this->sendKeys(WebDriverBy::cssSelector($this->passwordField), $password);
        $this->clickElement(WebDriverBy::cssSelector($this->loginButton));
    }
}
