<?PHP
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NetworksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class NetworksTableTest extends TestCase
{
    public $Networks;

    public function setUp(): void
    {
        parent::setUp();
        $this->Networks = TableRegistry::getTableLocator()->get('Networks');
    }

    public function tearDown(): void
    {
        unset($this->Networks);
        parent::tearDown();
    }

    public function testColumnsExistence(): void
    {
        $columns = $this->Networks->getSchema()->columns();

        // Check for each column's existence
        $expectedColumns = [
            'id', 'short_name', 'long_name', 'type', 'rpc_server_address', 
            'decimal_places', 'pallets'
        ];
        
        foreach ($expectedColumns as $column) {
            $this->assertTrue(in_array($column, $columns), "Column $column does not exist.");
        }
    }
}
