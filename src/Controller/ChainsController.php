<?PHP
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Chains Controller
 *
 * This controller is responsible for handling actions related to blockchain chains.
 */
class ChainsController extends AppController
{
    /**
     * Initialization method for the ChainsController.
     *
     * Calls the parent's initialization method.
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Info method
     *
     * This method sets up and displays information about various blockchain chains.
     * It fetches details of all networks from the 'Networks' table and passes it to the view.
     */
    public function info()
    {
        // Set the title for the view
        $this->set('title', "Chain Information");
        
        // Fetch all networks from the 'Networks' table
        $networksTable = TableRegistry::getTableLocator()->get('Networks');
        $networks = $networksTable->find('all');
        
        // Pass the fetched networks to the view
        $this->set('networks', $networks);
    }
}

