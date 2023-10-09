<?PHP
// Define the namespace for the model.
namespace App\Model\Entity;

// Import necessary ORM components from CakePHP.
use Cake\ORM\Entity;

// Define the Referendum entity that maps to the `referendums` table.
class Referendum extends Entity
{
    // Define virtual fields that don't map directly to the database table 
    // but can be accessed as properties of the entity.
    protected $_virtual = [
        'network_longname',    // The 'network_longname' is a virtual field.
        'network_shortname',   // The 'network_shortname' is a virtual field.
    ];
}
