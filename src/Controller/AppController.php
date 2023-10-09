<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    /**
     * This function is executed before rendering a view. It's used to prepare certain session
     * data, user details, and roles which will be needed by the view.
     *
     * @param EventInterface $event An event object representing the current request cycle.
     */
    public function beforeRender(EventInterface $event)
    {
        // Fetching the current session
        $session = $this->request->getSession();
        
        // If a User is present in the session
        if ($session->check('User')) {
            // Read the user details from the session
            $user = $this->request->getSession()->read('User');
            
            // Set the user details for the view
            $this->set('user', $user);
            
            // Flag indicating the user is logged in
            $this->set('loggedIn', true);
            
            // Get the Roles table
            $rolesTable = TableRegistry::getTableLocator()->get('Roles');
            
            // If the user has a role ID, fetch and set the role name for the view
            if (isset($user->role_id)) {
                $role = $rolesTable->get($user->role_id);
                $this->set('role', $role);
            } else {
                // Default role if no role ID found
                $this->set('role', 'User');
            }
            
            // If no mode (light/dark) is set in the session, set it to 'light'
            if (!$session->check('mode')) {
                $session->write('mode', 'light');
            }
        } else {
            // If no User is found in the session, set the loggedIn flag to false
            $this->set('loggedIn', false);
        }
    }
}
