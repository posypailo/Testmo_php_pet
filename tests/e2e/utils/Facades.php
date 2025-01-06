<?php

namespace Tests\e2e\utils;

use Tests\e2e\pages\LoginPage;
use Tests\e2e\pages\UserProfilePage;
use Tests\e2e\config\Constants;

class Facades {
    private $driver;

    public function __construct($driver) {
        $this->driver = $driver;
    }

    /**
     * Logs in a user using credentials from the credentials.json file.
     */
    public function authorize() {
        $credentials = json_decode(file_get_contents(__DIR__ . '/../data/credentials.json'), true);
        $email = $credentials['email'];
        $password = $credentials['password'];

        $loginPage = new LoginPage($this->driver);
        $loginPage->openPage();
        $loginPage->login($email, $password);
    }

    /**
     * Verifies the header title on the UserProfilePage.
     */
    public function verifyUserProfileHeader() {
        $userProfilePage = new UserProfilePage($this->driver);
        $userProfilePage->verifyHeaderTitle(Constants::USER_PROFILE_HEADER_TITLE);
    }
}
