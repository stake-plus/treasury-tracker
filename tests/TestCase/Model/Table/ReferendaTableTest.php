<?PHP
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReferendaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ReferendaTableTest extends TestCase
{
    public $Referenda;

    public function setUp(): void
    {
        parent::setUp();
        $this->Referenda = TableRegistry::getTableLocator()->get('Referenda');
    }

    public function tearDown(): void
    {
        unset($this->Referenda);
        parent::tearDown();
    }

    public function testColumnsExistence(): void
    {
        $columns = $this->Referenda->getSchema()->columns();

        // Check for each column's existence
        $expectedColumns = [
            'id', 'network_id', 'referenda_number', 'amount', 'w3f_classification', 
            'final', 'track', 'origin', 'preimage_hash', 'preimage_length',
            'enactment_after', 'submitted_at', 'submission_deposit_address', 'submission_deposit_amount',
            'submission_ts', 'submission_exchange_rate', 'confirmed_ts', 'decision_deposit_address',
            'decision_deposit_amount', 'deciding_since', 'executed_ts', 'executed_exchange_rate',
            'confirming_since', 'tally_ayes', 'tally_nays', 'tally_support', 'title', 'description'
        ];
        
        foreach ($expectedColumns as $column) {
            $this->assertTrue(in_array($column, $columns), "Column $column does not exist.");
        }
    }

    public function testReferencesExistence(): void
    {
        $constraints = $this->Referenda->getSchema()->constraints();

        // Validate foreign key for network_id referencing networks table
        $networksConstraint = $this->Referenda->getSchema()->getConstraint('referenda_ibfk_1');
        $this->assertEquals('network_id', $networksConstraint['columns'][0]);
        $this->assertEquals('networks', $networksConstraint['references'][0]);
    }
}

