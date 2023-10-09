<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Users');
    }

    /**
     * Register method
     * 
     * This method handles user registration based on their blockchain address and signature.
     * If the user already exists in the database, it doesn't create a new record but uses the existing one.
     * The registered user (either newly created or fetched) is then stored in the session.
     */
    public function register()
    {
        // Check if the request is a POST request
        if ($this->request->is('post')) {
            // Retrieve data from the POST request
            $data = $this->request->getData();
            
            // Create a new empty user entity
            $user = $this->Users->newEmptyEntity();

            // Populate user fields from the POST data
            $user->address = $data['address'];
            $user->signature = $data['signature'];
        
            // Check if the address provided is not empty
            if (!empty($user->address)) {
                // Check if the user with the given address already exists in the database
                $existingUser = $this->Users->find()
                    ->where(['address' => $user->address])
                    ->first();

                // If the user doesn't exist, save the new user entity
                if (!$existingUser) {
                    // Save the new user entity to the database
                    if ($this->Users->save($user)) {
                        // Write the user entity to the session
                        $this->request->getSession()->write('User', $user);
                    } else {
                        // Handle save error if needed
                    }
                } else {
                    // Write the existing user entity to the session
                    $this->request->getSession()->write('User', $existingUser);
                }
            } else {
                // Handle scenarios where address is empty, if needed
            }
        }
    }


    public function login()
    {
        // CakePHP logic for login
    }

    public function myAccount()
    {
        // Ensure user is logged in
    }
}
