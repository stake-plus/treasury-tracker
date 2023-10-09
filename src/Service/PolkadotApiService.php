<?PHP
namespace App\Service;

// Import the necessary component from CakePHP.
use Cake\Http\Client;
use Cake\Core\Configure;


// Define the PolkadotApiService class that communicates with a Polkadot API.
class PolkadotApiService
{
    // Private variable to store the HTTP client instance.
    private $httpClient;
    private $endpoint;

    // Constructor function for the service.
    public function __construct()
    {
        // Initialize the HTTP client.
        $this->httpClient = new Client();
        $this->endpoint = Configure::read('PolkadotApiService.endpoint', 'http://localhost:3000');
    }

    // Method to make a call to the API.
    public function call($type, $namespace, $method, $params = [], $network = '', $blockHash = null)
    {
        // Construct the data to be sent in the request.
        $data = [
            'type' => $type,
            'namespace' => $namespace,
            'method' => $method,
            'params' => $params,
            'network' => $network,
        ];

        // Add blockHash to the data if provided.
        if ($blockHash) {
            $data['blockHash'] = $blockHash;
        }

        // Send a POST request to the specified endpoint with the constructed data.
        $response = $this->httpClient->post($this->endpoint . '/api', json_encode($data), ['type' => 'json']);
        $result = $response->getStringBody();

        // Check if the result is an array and convert it to a string if needed.
        if (is_array($result)) {
            $result = json_encode($result);
        }

        // Check for errors in the result and throw an exception if found.
        if (!preg_match('/"error"/', $result)) {
            return json_decode($result);
        } else {
            throw new \Exception("API call failed: $result");
        }
    }

    // Method to get the count of referendums.
    public function getReferendumCount($network = '', $blockHash = null)
    {
        return $this->call('query', 'referenda', 'referendumCount', [], $network, $blockHash);
    }

    // Method to get the count of proposals.
    public function getProposalCount($network = '', $blockHash = null)
    {
        return $this->call('query', 'treasury', 'proposalCount', [], $network, $blockHash);
    }

    // Method to get the available pallets and their methods.
    public function getPalletsAndMethods($network = '') {
        // Send a POST request to retrieve the available pallets and methods.
        $response = $this->httpClient->post($this->endpoint . '/api/listMethods', json_encode(['network' => $network]), [
            'type' => 'json',
            'headers' => ['Content-Type' => 'application/json']
        ]);

        // Check if the response is successful and return the result, otherwise throw an exception.
        if ($response->isOk()) {
            return json_decode($response->getStringBody(), true);
        } else {
            throw new \Exception("API call failed: " . $response->getStringBody());
        }
    }
}
