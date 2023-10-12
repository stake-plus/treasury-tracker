<?PHP
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class UsersTableTest extends TestCase
{
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function tearDown(): void
    {
        unset($this->Users);
        parent::tearDown();
    }

    public function testColumnsExistence(): void
    {
        $columns = $this->Users->getSchema()->columns();

        // Check for each column's existence
        $expectedColumns = ['id', 'address', 'created_at', 'updated_at', 'signature', 'role_id'];
        
        foreach ($expectedColumns as $column) {
            $this->assertTrue(in_array($column, $columns), "Column $column does not exist.");
        }
    }
}
