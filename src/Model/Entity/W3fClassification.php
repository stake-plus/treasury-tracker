<?PHP
// Define the namespace for the model.
namespace App\Model\Entity;

// Import necessary ORM components from CakePHP.
use Cake\ORM\Entity;

// Define the W3fClassification entity that maps to the `w3f_classifications` table.
class W3fClassification extends Entity
{
    // Define the fields that are accessible and can be mass-assigned.
    protected $_accessible = [
        'classification_name' => true,  // The 'classification_name' field is accessible.
    ];
}
