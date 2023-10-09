<?PHP
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

class ApiController extends Controller
{
    /**
     * Initialization method
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    /**
     * Before filter method
     */
    public function beforeFilter(EventInterface $event)
    {
    }

    /**
     * Handle AJAX requests for updating classifications
     */
    public function ajax()
    {
        // Allow only POST requests
        $this->request->allowMethod(['post']);
        
        // Get POST data
        $data = $this->request->getData();

        // Retrieve the user from the session
        $user = $this->request->getSession()->read('User');

        // Extract classification and referendum IDs from the data
        $classificationId = $data['classificationId'];
        $referendumId = $data['referendumId'];

        // Check if the user has the role of admin (role_id = 2)
        if ($user['role_id'] == 2) {
            $referendaTable = $this->getTableLocator()->get('Referenda');

            // Get the referendum and update its classification
            $referendum = $referendaTable->get($referendumId);
            $referendum->w3f_classification = $classificationId;

            // Save the updated referendum and set appropriate response
            if ($referendaTable->save($referendum)) {
                $response = ['status' => 'success', 'message' => 'Classification updated successfully.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to update classification.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Access denied. You do not have permission to perform this action.'];
        }

        // Set the response data for rendering
        $this->set(compact('response'));

        // Configure view for AJAX response in JSON format
        $this->viewBuilder()->setLayout('ajax');
        $this->viewBuilder()->setOption('serialize', ['response']);
        $this->RequestHandler->renderAs($this, 'json');
    }

    /**
     * Handle AJAX requests for fetching chart data
     */
    public function ajaxChartData() 
    {
        // Allow only POST requests
        $this->request->allowMethod(['post']);
        
        // Get POST data
        $data = $this->request->getData();

        // Define filter criteria for querying referenda
        $filter = [
            'amount IS NOT' => null,
            'amount >' => 0,
            'tally_ayes > tally_nays',
            'network_id' => 1
        ];
        
        $referendaTable = $this->getTableLocator()->get('Referenda');
        $query = $referendaTable->find()->where($filter);

        $response_data = [];

        // Process each referendum and format data for the response
        foreach($query as $referendum) {
            $amount_in_usd = $referendum->amount * $referendum->submission_exchange_rate;
            $date_submitted = date('Y-m-d', $referendum->submission_ts);

            $response_data[] = [
                'id' => $referendum->id,
                'refnum' => $referendum->referenda_number,
                'title' => $referendum->title,
                'amount' => $referendum->amount,
                'date' => $date_submitted,
                'amount_in_usd' => $amount_in_usd,
            ];
        }

        // Set the response data for rendering
        $this->set(compact('response_data'));

        // Configure view for AJAX response in JSON format
        $this->viewBuilder()->setLayout('ajax');
        $this->viewBuilder()->setOption('serialize', ['response_data']);
        $this->RequestHandler->renderAs($this, 'json');
    }
}

