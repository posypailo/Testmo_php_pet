<?php

namespace Tests\e2e;

use Tests\e2e\pages\LoginPage;
use Tests\e2e\pages\UserProfilePage;

class LoginTest extends TestBase {
    public function testLogin() {
        $credentials = json_decode(file_get_contents(__DIR__ . '/data/credentials.json'), true);
        $email = $credentials['email'];
        $password = $credentials['password'];

        $this->driver->get('https://sjpknight.testmo.net/auth/login');
        $loginPage = new LoginPage($this->driver);
        $loginPage->login($email, $password);

        $userProfilePage = new UserProfilePage($this->driver);

        $actualText = $userProfilePage->getHeaderTitleText();
        $expectedText = 'Candidate AP Test';

        $this->assertEquals($expectedText, $actualText, "The header text after login does not match.");
    }
}
