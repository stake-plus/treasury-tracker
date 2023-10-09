<?PHP
// Define a namespace for the command
namespace App\Command;

// Import necessary CakePHP and custom service classes
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\ORM\TableRegistry;
use App\Service\PolkadotApiService;

// Define a command class called NetworkInfoCommand that extends CakePHP's Command class
class NetworkInfoCommand extends Command
{
    // Function to set up any initial configuration or services
    public function initialize(): void
    {
        // Call the parent initialize method to set up default configurations
        parent::initialize();
        
        // Initialize the PolkadotApiService to interact with the Polkadot API
        $this->apiService = new PolkadotApiService();
    }

    // Function that is executed when the command is run
    public function execute(Arguments $args, ConsoleIo $io)
    {
        // Get the table for networks
        $networksTable = TableRegistry::getTableLocator()->get('Networks');
        
        // Fetch all records from the networks table
        $networks = $networksTable->find()->all();

        // Iterate through each network
        foreach ($networks as $network) {
            // Fetch available pallets and methods using the network's RPC server address
            $result = $this->apiService->getPalletsAndMethods($network->rpc_server_address);
            
            // Fetch network properties using the network's RPC server address
            $networkInfo = $this->apiService->call('rpc', 'system', 'properties', [], $network->rpc_server_address);

            // Create an array to hold the pallets and methods
            $array = [];
            
            // Iterate over the fetched pallets and methods and populate the array
            foreach ($result['result'] as $pallet => $calls) {
                foreach ($calls as $call) {
                    $array[$pallet][$call] = "1";
                }
            }

            // Convert the array to a JSON string
            $jsonEncodedArray = json_encode($array);
            
            // Store the JSON string in the network's pallets property
            $network->pallets = $jsonEncodedArray;

            // Initialize a variable to store the number of decimal places for a token
            $decimalPlaces = 0;

            // Find the matching token in the network properties and determine its decimal places
            foreach ($networkInfo->result->tokenSymbol as $key => $token) {
                if ($token == $network->short_name) {
                    $decimalPlaces = $networkInfo->result->tokenDecimals[$key];
                }
            }

            // Convert the decimal places into an actual power of ten value
            $decimalPlaces = pow(10, $decimalPlaces);
            
            // Update the network's decimal places if they have changed
            if ($decimalPlaces && $decimalPlaces != $network->decimal_places) {
                $network->decimal_places = $decimalPlaces;
            }

            // Save the network details back to the database
            if ($networksTable->save($network)) {
                $io->out("Saved successfully for: " . $network->long_name);
            } else {
                $io->out("Failed to save for: " . $network->long_name);
            }
        }
    }
}

