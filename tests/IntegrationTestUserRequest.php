<?php

use PHPUnit\Framework\TestCase;

class IntegrationTestUserRequest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() == PHP_SESSION_NONE && php_sapi_name() != 'cli') {
            session_start();
        }

        $requestData = [
            [
                'requestId' => '6658bc02d7333',
                'userId' => 18,
                'userName' => 'Alessia Angelè',
                'userAddress' => 'Via Tomasino D\'Amico, RM',
                'status' => 'canceled',
                'details' => '',
                'timestamp' => '2024-05-30T19:48:50+02:00'
            ],
            [
                'requestId' => '66d485959d605',
                'userId' => 1,
                'userName' => 'Lorenzo Buzi',
                'userAddress' => 'via santa lucia filippini 7',
                'status' => 'completed',
                'details' => 'asd',
                'timestamp' => '2024-09-01T15:17:41+00:00'
            ]
        ];

        file_put_contents('requests.json', json_encode($requestData));

        $_SESSION['userid'] = 1;

        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    public function testGetUserRequestsIntegration()
    {
        echo "Working directory: " . getcwd() . "\n";

        ini_set('display_errors', 0);

        ob_start();
        include 'utilities.php'; 
        getUserRequests();
        $output = ob_get_clean();

        echo "Raw output: " . $output . "\n";

        $response = json_decode($output, true);

        var_dump($response);

        if ($response === null) {
            echo "JSON decode error: " . json_last_error_msg() . "\n";
        }

        $this->assertTrue($response['success'], "The success field should be true");
        $this->assertCount(1, $response['requests'], "There should be exactly 1 request for the user");
    }




    protected function tearDown(): void
    {
        unlink('requests.json');
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
