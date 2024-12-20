<?php

namespace Tests\e2e\pages;

use Facebook\WebDriver\WebDriverBy;

class UserProfilePage extends BasePage {
    private $headerTitle = '.card__header__title a';

    /**
     * Verifies if the header title matches the expected text.
     *
     * @param string $expectedText
     * @throws \Exception if the text does not match
     */
    public function verifyHeaderTitle(string $expectedText): void {
        $this->verifyElementText(WebDriverBy::cssSelector($this->headerTitle), $expectedText);
    }
}
