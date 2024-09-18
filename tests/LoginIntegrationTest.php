<?php
use PHPUnit\Framework\TestCase;
ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

class LoginIntegrationTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        include 'db_connection.php'; 
        $this->conn = $conn;

        if (!defined('PHPUNIT_RUNNING')) {
            define('PHPUNIT_RUNNING', true);
        }
    }

    public function testLoginFlow()
    {
        $_POST['email'] = 'angelealessia74@gmail.com';
        $_POST['password'] = 'Telecomando1!';

        ob_start();
        include 'user_login.php';
        $output = trim(ob_get_clean());

        var_dump($output);
        $this->assertStringContainsString('Login riuscito', $output);
    }

    public function testFailedLoginFlow()
    {
        $_POST['email'] = 'angele@gmail.com';
        $_POST['password'] = 'telec';

        ob_start();
        include 'user_login.php';
        $output = trim(ob_get_clean());

        var_dump($output);
        $this->assertStringContainsString('Credenziali errate', $output);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_POST = [];
    }
}
