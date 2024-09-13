use PHPUnit\Framework\TestCase;

class LoginIntegrationTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        include 'db_connection.php'; 
        $this->conn = $conn;  
    }

    public function testLoginFlow()
    {
        // Simula i dati del form
        $_POST['email'] = 'angelealessia74@gmail.com';
        $_POST['password'] = 'Telecomando1!';

        ob_start();
        include 'user_login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Login riuscito', $output);
    }

    // credenziali errate
    public function testFailedLoginFlow()
    {
    
        $_POST['email'] = 'angele@gmail.com';
        $_POST['password'] = 'telec';

        ob_start();
        include 'user_login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Credenziali errate', $output);
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }
}