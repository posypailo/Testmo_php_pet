<?php

namespace Tests\e2e;

use Tests\e2e\utils\Facades;

class LoginTest extends TestBase {
    public function testLogin() {
        $facades = new Facades($this->driver);
        $facades->authorize();
        $facades->verifyUserProfileHeader()
    }
}
