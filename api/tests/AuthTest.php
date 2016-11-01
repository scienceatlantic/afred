<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetAuthPingWhenLoggedIn()
    {
        $u = $this->testPostAuthLogin();
        
        $this->get('/auth/ping')
             ->seeStatusCode(200)
             ->seeJson($u);
    }

    public function testGetAuthPingWhenNotLoggedIn()
    {
        $this->get('/auth/ping')
             ->seeStatusCode(200)
             ->see('Not authenticated');
    }

    public function testPostAuthLogin()
    {
        $unHashedPwd = str_random(10);
        $u = $this->getAdmin(['password' => Hash::make($unHashedPwd)]);
        $payload = [
            'email' => $u->email,
            'password' => $unHashedPwd
        ];

        $this->post('/auth/login', $payload)
              ->seeStatusCode(200)
              ->seeJson($u->toArray());

        return $u->toArray();
    }

    public function testPostAuthLoginWithInvalidEmail()
    {
        $unHashedPwd = str_random(10);
        $u = $this->getAdmin(['password' => Hash::make($unHashedPwd)]);
        $payload = [
            'email' => 'random' . $u->email,
            'password' => $unHashedPwd
        ];

        $this->post('/auth/login', $payload)
              ->seeStatusCode(200)
              ->see('Not authorized');       
    }

    public function testPostAuthLoginWithInvalidPassword()
    {
        $unHashedPwd = str_random(10);
        $u = $this->getAdmin(['password' => Hash::make($unHashedPwd)]);
        $payload = [
            'email' => $u->email,
            'password' => 'random' . $unHashedPwd
        ];

        $this->post('/auth/login', $payload)
              ->seeStatusCode(200)
              ->see('Not authorized');           
    }

    public function testGetAuthLogout()
    {
        for ($i = 0; $i < 5; $i++) {
            $u = $this->testPostAuthLogin();

            $this->get('auth/ping')
                 ->seeJson($u);
            $this->get('auth/logout');
            $this->get('auth/ping')
                 ->see('Not authenticated');
        }
    }
}
