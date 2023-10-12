<?PHP
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\W3fClassificationTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class W3fClassificationTableTest extends TestCase
{
    public $W3fClassification;

    public function setUp(): void
    {
        parent::setUp();
        $this->W3fClassification = TableRegistry::getTableLocator()->get('W3fClassification');
    }

    public function tearDown(): void
    {
        unset($this->W3fClassification);
        parent::tearDown();
    }

    public function testColumnsExistence(): void
    {
        $columns = $this->W3fClassification->getSchema()->columns();

        // Check for each column's existence
        $expectedColumns = ['id', 'classification_name'];
        
        foreach ($expectedColumns as $column) {
            $this->assertTrue(in_array($column, $columns), "Column $column does not exist.");
        }
    }
}
