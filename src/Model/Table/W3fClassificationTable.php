<?PHP
// Define the namespace for the model table.
namespace App\Model\Table;

// Import the necessary component from CakePHP.
use Cake\ORM\Table;

// Define the W3fClassificationTable class that maps to the database's w3f_classification table.
class W3fClassificationTable extends Table
{
    // This method is called during the initialization of the table object.
    public function initialize(array $config): void
    {
        // Set the database table name associated with this class.
        $this->setTable('w3f_classification');
        
        // Set the display field for this table, which determines the default field to represent rows.
        $this->setDisplayField('classification_name');
        
        // Set the primary key for this table.
        $this->setPrimaryKey('id');
    }
}
