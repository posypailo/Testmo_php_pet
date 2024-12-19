<?php

namespace Tests\e2e\pages;

use Facebook\WebDriver\WebDriverBy;

class UserProfilePage extends BasePage {
    private $headerTitle = '.card__header__title a';

    public function getHeaderTitleText(): void {
        $this->verifyElementText(WebDriverBy::cssSelector($this->headerTitle), "Candidate AP Test");
    }
}
