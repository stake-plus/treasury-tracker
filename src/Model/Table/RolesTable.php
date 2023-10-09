<?PHP
// Define the namespace for the model table.
namespace App\Model\Table;

// Import necessary ORM and Validation components from CakePHP.
use Cake\ORM\Table;
use Cake\Validation\Validator;

// Define the RolesTable class that maps to the `roles` database table.
class RolesTable extends Table
{
    // This method is called during the initialization of the table object.
    public function initialize(array $config): void
    {
        // Set the primary key for this table.
        $this->setPrimaryKey('id');
    }

    // Define the default validation rules for the Role entity.
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            // Ensure that the 'name' field is not empty.
            ->notEmptyString('name', 'A role name is required')
            // Set a max length for the 'name' field.
            ->maxLength('name', 255, 'Role name is too long. It should be less than 255 characters.')
            // Ensure the uniqueness of the 'name' field.
            ->add('name', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Role name already exists'
            ]);

        // Return the validator object.
        return $validator;
    }
}
