<?PHP
// Define the namespace for the model table.
namespace App\Model\Table;

// Import the necessary components from CakePHP.
use Cake\Validation\Validator;
use Cake\ORM\Table;

// Define the UsersTable class that maps to the database's user table.
class UsersTable extends Table
{
    // This method is called during the initialization of the table object.
    public function initialize(array $config): void
    {
        // Establish a relationship indicating that each user belongs to a role.
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id', // Specify the foreign key for the relationship.
        ]);

        // Add the Timestamp behavior to automatically update the created and updated timestamps.
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [   // Before saving the model...
                    'created_at' => 'new',      // Set 'created_at' timestamp for new records.
                    'updated_at' => 'always',   // Always update the 'updated_at' timestamp.
                ]
            ]
        ]);
    }

    // Define the default validation rules for the user data.
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('address')                           // Ensure the address is a scalar value.
            ->maxLength('address', 255)                   // Ensure the address is not longer than 255 characters.
            ->allowEmptyString('address')                 // Allow an empty string for the address.
            ->allowEmptyString('signature');              // Allow an empty string for the signature.

        return $validator;                                // Return the validator object with the defined rules.
    }
}
