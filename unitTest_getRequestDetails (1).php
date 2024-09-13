use PHPUnit\Framework\TestCase;

class RequestDetailsTest extends TestCase
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


        file_put_contents('requests.json', json_encode($this->requestData));
    }


    public function testGetRequestDetails()
    {
        $_GET['requestId'] = '66d485959d605';  

        ob_start();
        include 'utilities.php';
        getRequestDetails();
        $output = ob_get_clean();

       
        $response = json_decode($output, true);

        $this->assertTrue($response['success']);
        $this->assertEquals('Lorenzo Buzi', $response['requestDetails']['userName']);
        $this->assertEquals('completed', $response['requestDetails']['status']);
    }


    protected function tearDown(): void
    {
        unlink('requests.json');
    }
}