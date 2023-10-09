<?PHP
namespace App\Controller;

use App\Controller\AppController;
use Parsedown;

/**
 * Referendum Controller
 *
 * This controller is responsible for handling actions related to blockchain referendums.
 */
class ReferendumController extends AppController
{
    /**
     * View method
     *
     * This method sets up and displays detailed information about a specific blockchain referendum.
     *
     * @param string $network Name of the network (default is "Kusama").
     * @param int $id ID of the referendum to view.
     */
    public function view($network = "Kusama", $id = NULL)
    {
        // Initialize markdown parser
        $parsedown = new Parsedown();
        
        // Set the name of the network for the view
        $this->set('network', ucfirst($network));
        
        // Set the title for the view based on network and referendum ID
        $title = ucfirst($network) . ' Referendum ' . $id;
        $this->set('title', $title);

        // Fetch the details of the selected network
        $networksTable = $this->getTableLocator()->get('Networks');
        $network = $networksTable->find()
          ->select(['id', 'short_name'])
          ->where(['long_name' => $network])
          ->first();
          
        // Set the short name of the network for the view
        $this->set('network_short', $network->short_name);
        
        // Fetch ID of the selected network
        $network_id = $network->id;
        
        // Fetch the details of the specified referendum
        $referendaTable = $this->getTableLocator()->get('Referenda');
        $referendum = $referendaTable->find()
          ->where(['network_id' => $network_id, 'referenda_number' => $id])
          ->first();
          
        // Convert the description of the referendum from markdown to HTML
        if (!empty($referendum->description)) {
            $referendum->description = $parsedown->text($referendum->description);
        }

        // Fetch all classifications for dropdown
        $classificationTable = $this->getTableLocator()->get('W3fClassification');
        $classifications = $classificationTable->find('list', ['keyField' => 'id', 'valueField' => 'classification_name'])
            ->toArray();

        // If the referendum exists, get the corresponding classification
        $classification = null;
        if ($referendum && isset($classifications[$referendum->w3f_classification])) {
            $classification = $classifications[$referendum->w3f_classification];
        }

        // Set variables for the view
        $this->set(compact('referendum', 'classifications', 'classification'));
    }
}

