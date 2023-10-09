<?PHP
// Define the namespace for the model table.
namespace App\Model\Table;

// Import the necessary ORM component from CakePHP.
use Cake\ORM\Table;

// Define the SessionsTable class that maps to the `sessions` database table.
class SessionsTable extends Table
{
    // This method is called during the initialization of the table object.
    public function initialize(array $config): void
    {
        // Set the name of the database table this class maps to.
        $this->setTable('sessions');
        // Set the primary key for this table.
        $this->setPrimaryKey('id');
    }
}
