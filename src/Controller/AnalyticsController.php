<?PHP
namespace App\Controller;

use App\Controller\AppController;

/**
 * AnalyticsController handles operations related to analytics.
 */
class AnalyticsController extends AppController
{
    /**
     * Initialization method.
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Display analytics by categories.
     */
    public function categories()
    {
        $this->set('title', "Spend by Category");

        // Load category and network data
        $networksTable = $this->getTableLocator()->get('Networks');
        $classificationTable = $this->getTableLocator()->get('W3fClassification');

        // Map classifications to array
        $cats = $classificationTable->find()->toArray();
        $classifications = array();
        foreach ($cats as $cat) {
            $classifications[$cat->id] = $cat->classification_name;
        }

        // Map networks to array
        $nets = $networksTable->find()->toArray();
        $networks = array();
        foreach ($nets as $net) {
            $networks[$net->id]['long_name'] = $net->long_name;
            $networks[$net->id]['short_name'] = $net->short_name;
            $networks[$net->id]['type'] = $net->type;
        }

        // Process filters from query
        $conditions = [];
        $temp = $this->request->getQuery('networks', []);
        if (empty($temp)) {
            $temp = array('1','2');
        }
        $conditions[] = ['network_id IN' => $temp];

        if ($temp = $this->request->getQuery('classifications', [])) {
            $conditions[] = ['w3f_classification IN' => $temp];
        } 
        $temp = $this->request->getQuery('state', null);

        // Default to approved referenda
        if (!$temp) {
            $temp = "1";
        }

        // Define additional conditions based on state filter
        switch ($temp)  {
            case "0":
                $conditions[] = ['final' => '1'];
                break;
            case "1":
                $conditions[] = ['final' => '1'];
                $conditions[] = ['tally_ayes > tally_nays'];
                break;
            case "2":
                $conditions[] = ['final' => '1'];
                $conditions[] = ['tally_ayes < tally_nays'];
                break;
        }

        // Fetch referenda data
        $referendaTable = $this->getTableLocator()->get('Referenda');
        $query = $referendaTable->find()->where($conditions);

        // Process fetched data
        $response_data = [];
        foreach($query as $referendum) {
            $amount_in_usd = $referendum->amount * $referendum->submission_exchange_rate;
            $date_submitted = date('Y-m-d', $referendum->submission_ts);

            // Set category and specify Uncategorized if category is not set
            if ($referendum->w3f_classification) {
                $cat = $classifications[$referendum->w3f_classification];
            } else {
                $cat = $classifications[0];
            }

            // Prepare data for frontend
            $response_data[] = [
                'id' => $referendum->id,
                'date' => $date_submitted,
                'network' => $networks[$referendum->network_id]['long_name'],
                'refnum' => $referendum->referenda_number,
                'category' => $cat,
                'track' => $referendum->track,
                'amount' => $referendum->amount,
                'amount_in_usd' => $amount_in_usd,
                'title' => $referendum->title,
            ];
        }

        // Aggregate data by date and category for visualization
        $aggregatedData = [];
        foreach ($response_data as $record) {
            $date = $record['date'];
            $category = $record['category'];

            if (!isset($aggregatedData[$date])) {
                $aggregatedData[$date] = [];
            }

            if (!isset($aggregatedData[$date][$category])) {
                $aggregatedData[$date][$category] = 0;
            }

            $aggregatedData[$date][$category] += $record['amount_in_usd'];
        }

        // Pass data to frontend
        $response_data = json_encode($response_data);
        $aggregatedData = json_encode($aggregatedData);
        $this->set(compact('classifications', 'networks', 'response_data', 'aggregatedData'));
    }

    /**
     * Display analytics by tracks.
     */
    public function tracks()
    {
        // Set the title for the view.
        $this->set('title', "Referenda by Tracks");

        // Load categories and networks data for frontend use.
        $networksTable = $this->getTableLocator()->get('Networks');
        $classificationTable = $this->getTableLocator()->get('W3fClassification');

        // Extract classifications for referenda.
        $cats = $classificationTable->find()->toArray();
        $classifications = array();
        foreach ($cats as $cat) {
            $classifications[$cat->id] = $cat->classification_name;
        }

        // Extract network information.
        $nets = $networksTable->find()->toArray();
        $networks = array();
        foreach ($nets as $net) {
            $networks[$net->id]['long_name'] = $net->long_name;
            $networks[$net->id]['short_name'] = $net->short_name;
            $networks[$net->id]['type'] = $net->type;
        }

        // Process filters for refining the data request.
        $conditions = [];
        $temp = $this->request->getQuery('networks', []);
        if (empty($temp)) {
            $temp = array('1','2');
        }
        $conditions[] = ['network_id IN' => $temp];

        if ($temp = $this->request->getQuery('classifications', [])) {
            $conditions[] = ['w3f_classification IN' => $temp];
        } 
        $temp = $this->request->getQuery('state', null);

        // Set default filter to approved referenda if no specific state is given.
        if (!$temp) {
            $temp = "1";
        }

        // Add conditions based on the state of referenda.
        switch ($temp)  {
            case "0":
                $conditions[] = ['final' => '1'];
                break;
            case "1":
                $conditions[] = ['final' => '1'];
                $conditions[] = ['tally_ayes > tally_nays'];
                break;
            case "2":
                $conditions[] = ['final' => '1'];
                $conditions[] = ['tally_ayes < tally_nays'];
                break;
        }
        
        // Fetch relevant referenda data based on the conditions.
        $referendaTable = $this->getTableLocator()->get('Referenda');
        $query = $referendaTable->find()->where($conditions);

        // Process and format the data for frontend consumption.
        $response_data = [];
        foreach($query as $referendum) {
            $amount_in_usd = $referendum->amount * $referendum->submission_exchange_rate;
            $date_submitted = date('Y-m-d', $referendum->submission_ts);

            // Determine the category or set to 'Uncategorized'.
            if ($referendum->w3f_classification) {
                $cat = $classifications[$referendum->w3f_classification];
            } else {
                $cat = $classifications[0];
            }

            // Map the data for frontend presentation.
            $response_data[] = [
                'id' => $referendum->id,
                'date' => $date_submitted,
                'network' => $networks[$referendum->network_id]['long_name'],
                'refnum' => $referendum->referenda_number,
                'track' => $referendum->track,  
                'category' => $cat,  // Reintroduced category for clarity.
                'amount' => $referendum->amount,
                'amount_in_usd' => $amount_in_usd,
                'title' => $referendum->title,
            ];
        }

        // Aggregate data for visualization.
        $aggregatedData = [];
        foreach ($response_data as $record) {
            $date = $record['date'];
            $track = $record['track'];

            if (!isset($aggregatedData[$date])) {
                $aggregatedData[$date] = [];
            }

            if (!isset($aggregatedData[$date][$track])) {
                $aggregatedData[$date][$track] = 0;
            }

            // Count the referenda for each track.
            $aggregatedData[$date][$track]++;
        }

        // Prepare data for frontend as JSON.
        $response_data = json_encode($response_data);
        $aggregatedData = json_encode($aggregatedData);

        // Pass processed data to the frontend.
        $this->set(compact('classifications', 'networks', 'response_data', 'aggregatedData'));
    }
}
