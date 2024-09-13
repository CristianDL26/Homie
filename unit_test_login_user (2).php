use PHPUnit\Framework\TestCase;

class UserLoginTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        include 'db_connection.php'; 
        $this->conn = $conn;  
    }
//credenziali corrette
    public function testValidUserLogin()
    {
        $email = "angelealessiaa@gmail.com";
        $password = md5("Telecomando1!"); 

        $query = "SELECT * FROM homie.user_data WHERE email = ? AND password = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->assertEquals(1, $result->num_rows, "Utente non trovato o credenziali errate.");
    }

    // credenziali errate
    public function testInvalidUserLogin()
    {
        $email = "angele@gmail.com";
        $password = md5("telec");

        $query = "SELECT * FROM homie.user_data WHERE email = ? AND password = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->assertEquals(0, $result->num_rows, "Trovato un utente con credenziali errate.");
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }
}