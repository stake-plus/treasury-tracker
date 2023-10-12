<?PHP
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RolesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class RolesTableTest extends TestCase
{
    public $Roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->Roles = TableRegistry::getTableLocator()->get('Roles');
    }

    public function tearDown(): void
    {
        unset($this->Roles);
        parent::tearDown();
    }

    public function testColumnsExistence(): void
    {
        $columns = $this->Roles->getSchema()->columns();

        // Check for each column's existence
        $expectedColumns = ['id', 'name'];
        
        foreach ($expectedColumns as $column) {
            $this->assertTrue(in_array($column, $columns), "Column $column does not exist.");
        }
    }
}
