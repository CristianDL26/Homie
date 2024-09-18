<?php
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class UnitTestGetUserRequests extends TestCase
{
    private $requestData;

    protected function setUp(): void
    {
        $this->requestData = [
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

        $_SESSION['userid'] = 1;

        $_SERVER['REQUEST_METHOD'] = 'GET';

        file_put_contents('requests.json', json_encode($this->requestData));
        echo "File JSON creato con successo";
    }

    public function testGetUserRequests()
    {
        ob_start();
        include 'utilities.php';
        $_SESSION['userid'] = 1;
        getUserRequests(); 
        $output = ob_get_clean(); 
    
        $response = json_decode($output, true);
    
        $this->assertNotNull($response, "L'output JSON non è valido");
    
        $this->assertTrue($response['success'], "Il campo success dovrebbe essere true");
    
        $this->assertCount(1, $response['requests'], "Dovrebbe esserci una sola richiesta per l'utente con ID 1");
    
        $this->assertEquals('Lorenzo Buzi', $response['requests'][1]['userName'], "Il nome dell'utente dovrebbe essere Lorenzo Buzi");
    }
    
    


    protected function tearDown(): void
    {
        if (file_exists('requests.json')) {
            unlink('requests.json');
        }

    }
}
