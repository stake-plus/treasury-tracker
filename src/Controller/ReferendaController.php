<?PHP
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReferendaController handles operations related to referenda.
 */
class ReferendaController extends AppController
{
    /**
     * Initialize controller components and settings.
     */
    public function initialize(): void
    {
        parent::initialize();

        // Load the Paginator component
        $this->loadComponent('Paginator');

        // Set default pagination settings
        $this->paginate = [
            'limit' => 10,
            'order' => ['Referenda.submission_ts' => 'DESC'],
            'sortableFields' => [
                'Referenda.submission_ts',
            ]
        ];
    }

    /**
     * Display an overview of referenda.
     */
    public function overview()
    {
        $this->set('title', "Overview");

        // Fetch all referenda, ordered by referenda number
        $referendaTable = $this->getTableLocator()->get('Referenda');
        $referenda = $referendaTable->find()
            ->order(['referenda_number' => 'DESC'])
            ->toArray();

        $this->set(compact('referenda'));
    }

    /**
     * Display a list of referenda based on filters.
     */
    public function referenda()
    {
        $this->set('title', "Referenda");

        // Retrieve filters from query parameters
        $getNetworks = $this->request->getQuery('networks', []);
        if (empty($getNetworks)) {
            $getNetworks = array('1','2');
        }
        $getState = $this->request->getQuery('state', null);
        $getCats = $this->request->getQuery('classifications', []);
        $conditions = [];

        // Build query conditions based on filters
        if ($getNetworks) {
            $conditions[] = ['network_id IN' => $getNetworks];
        }

        if ($getCats) {
            $conditions[] = ['w3f_classification IN' => $getCats];
        }

        // Further filter based on referenda state
        switch ($getState)  {
            case "0":
                break;
            case "1":
                $conditions[] = ['final' => '0'];
                break;
            case "2":
                $conditions[] = ['final' => '1'];
                break;
        }

        // Fetch referenda matching the conditions
        $referendaTable = $this->getTableLocator()->get('Referenda');
        $referenda = $referendaTable->find()->where($conditions);

        // Retrieve related data
        $networksTable = $this->getTableLocator()->get('Networks');
        $classificationTable = $this->getTableLocator()->get('W3fClassification');

        $cats = $classificationTable->find()->toArray();
        $classifications = array();
        foreach ($cats as $cat) {
            $classifications[$cat->id] = $cat->classification_name;
        }

        $nets = $networksTable->find()->toArray();
        $networks = array();
        foreach ($nets as $net) {
            $networks[$net->id]['long_name'] = $net->long_name;
            $networks[$net->id]['short_name'] = $net->short_name;
            $networks[$net->id]['type'] = $net->type;
        }

        // Calculate referenda count by status
        $count["active"] = 0;
        $count["approved"] = 0;
        $count["rejected"] = 0;
        $count["total"] = 0;

        foreach ($referenda as $referendum) {
            $count["total"] = $count["total"] + 1;
            if ($referendum->final < 1) {
                $count["active"] = $count["active"] + 1;
            } else {
                if ($referendum->tally_ayes > $referendum->tally_nays) {
                    $count["approved"] = $count["approved"] + 1;
                } else {
                    $count["rejected"] = $count["rejected"] + 1;
                }
            }
        }

        // Paginate the results
        $referenda = $this->paginate($referenda);
        $this->set(compact('count', 'classifications', 'networks', 'referenda'));
    }

    /**
     * Display discussions related to referenda.
     */
    public function discussions()
    {
        $this->set('title', "Discussions");

        // Fetch all referenda, ordered by referenda number
        $referendaTable = $this->getTableLocator()->get('Referenda');
        $referenda = $referendaTable->find()
            ->order(['referenda_number' => 'DESC'])
            ->toArray();

        $this->set(compact('referenda'));
    }
}

