<?PHP
// Define the namespace for the model table.
namespace App\Model\Table;

// Import the necessary component from CakePHP.
use Cake\ORM\Table;

// Define the ReferendaTable class that maps to the database's referenda table.
class ReferendaTable extends Table
{
    // This method is called during the initialization of the table object.
    public function initialize(array $config): void
    {
        // Call parent's initialize method (important if there are global behaviors/settings in a parent table class).
        parent::initialize($config);

        // Set the database table name associated with this class.
        $this->setTable('referenda');

        // Set the display field for this table, which determines the default field to represent rows.
        $this->setDisplayField('id');

        // Set the primary key for this table.
        $this->setPrimaryKey('id');

        // Define the association between the Referenda table and the Networks table.
        // A referendum belongs to a single network.
        $this->belongsTo('Networks', [
            'foreignKey' => 'network_id',
            'joinType' => 'INNER',  // Specify that an INNER JOIN should be used.
        ]);
    }
}
