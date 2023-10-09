<?PHP
// Define the namespace for the model.
namespace App\Model\Entity;

// Import necessary ORM components from CakePHP.
use Cake\ORM\Entity;

// Define the User entity that maps to the `users` table.
class User extends Entity
{
    // Specify which fields are accessible for creating or updating.
    // Using '*' makes all fields accessible by default, but specific 
    // overrides can be provided for particular fields.
    protected $_accessible = [
        '*' => true,          // Allow all fields to be mass assigned by default.
        'id' => false,        // Prohibit mass assignment to 'id' field.
        'address' => true,    // Allow mass assignment to 'address' field.
        'signature' => true,  // Allow mass assignment to 'signature' field.
        'role_id' => false,   // Prohibit mass assignment to 'role_id' field.
    ];

    // Specify fields that should be converted to empty strings if they are 
    // `null` when the entity is marshaled (transformed for saving).
    protected $_emptyStrings = [
        'address',            // Convert null 'address' to an empty string.
        'signature',          // Convert null 'signature' to an empty string.
    ];
}
