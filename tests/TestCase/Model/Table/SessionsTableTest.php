<?PHP
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SessionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class SessionsTableTest extends TestCase
{
    public $Sessions;

    public function setUp(): void
    {
        parent::setUp();
        $this->Sessions = TableRegistry::getTableLocator()->get('Sessions');
    }

    public function tearDown(): void
    {
        unset($this->Sessions);
        parent::tearDown();
    }

    public function testColumnsExistence(): void
    {
        $columns = $this->Sessions->getSchema()->columns();

        // Check for each column's existence
        $expectedColumns = ['id', 'created', 'modified', 'data', 'expires'];
        
        foreach ($expectedColumns as $column) {
            $this->assertTrue(in_array($column, $columns), "Column $column does not exist.");
        }
    }
}
