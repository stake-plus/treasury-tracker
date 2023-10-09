<?PHP
namespace App\Command;

// Import necessary libraries and services
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use App\Service\PolkadotApiService;
use Cake\Http\Client;

// Define a command class for importing referenda data
class ReferendaImportCommand extends Command
{
    private $apiService; // Define a private property for the PolkadotApiService

    // Initialization function
    public function initialize(): void
    {
        parent::initialize(); // Call the parent initialize method
        // Instantiate the Polkadot API service
        $this->apiService = new PolkadotApiService();
    }

    // Option parser for the command-line arguments
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->addOption('network', [
                'help' => 'The network to use (Polkadot or Kusama).',
                'default' => 'Polkadot',
            ])
            ->addOption('refnum', [
                'help' => 'The referenda number to update',
                'default' => '1',
            ]);

        return $parser;
    }

    // Helper function to recursively sum the proposal amounts
    private function recursiveSum($obj) {
        $sum = 0;
        if (is_object($obj)) {
            if (isset($obj->callName, $obj->palletName, $obj->args->amount)) {
                if ($obj->callName === "spend" && $obj->palletName === "treasury") {
                    if(is_numeric($obj->args->amount)) {
                        $sum += $obj->args->amount;
                    } elseif(preg_match('/^0x[a-f0-9]+$/i', $obj->args->amount)) {
                        $sum += hexdec($obj->args->amount);
                    } else {
                        echo "Non-numeric value encountered: " . $obj->args->amount . "\n";
                    }
                }
            }
            foreach($obj as $key => $value) {
                // Only recurse if the current property is an object or array that contains a 'callIndex' property
                if (is_object($value) && isset($value->callIndex)) {
                    $sum += $this->recursiveSum($value);
                }
            }
        } elseif (is_array($obj)) {
            foreach ($obj as $value) {
                // Only recurse if the current element is an object or array that contains a 'callIndex' property
                if (is_array($value) && isset($value['callIndex'])) {
                    $sum += $this->recursiveSum($value);
                }
            }
        }
        return $sum;
    }

    // Main execution function
    public function execute(Arguments $args, ConsoleIo $io)
    {
        // Start a new HTTP client instance
        $client = new Client();

        // Load command line options
        $network = $args->getOption('network');
        $refnum = $args->getOption('refnum');

        // Load required database tables
        $referendaTable = TableRegistry::getTableLocator()->get('Referenda');
        $networkTable = TableRegistry::getTableLocator()->get('Networks');

        // Variables for later checks and conditionals
        $network_id = 0;
        $check = 0;
        $final = 0;

        // Retrieve network data based on the provided network ID
        $query = $networkTable->find()->where(['id' => $network]);
        if ($query->count() > 0) {
            $networkInfo = $query->first();
            $network_id = $networkInfo->id;
        } else {
            echo "Unknown Network\r\n";
            die;
        }

        // Fetch PolkAssembly data on the referenda
        $paResponse = "";
        $maxRetries = 3;
        for ($i = 0; $i < $maxRetries; $i++) {
            try {
                $response = $client->get('https://api.polkassembly.io/api/v1/posts/on-chain-post', [
                    'proposalType' => 'referendums_v2',
                    'postId' => $refnum,
                ], [
                    'headers' => ['x-network' => strtolower($networkInfo->long_name)]
                ]);

                if ($response->isOk()) {
                    $paResponse = $response->getJson();
                } else {
                    $io->error('Failed to fetch data for referendum ' . $refnum);
                }

                break;
            } catch (\Exception $e) {
                if ($i === $maxRetries - 1) {
                    $io->error('Failed to fetch data for referendum ' . $refnum . ' after ' . $maxRetries . ' attempts.');
                    throw $e;
                }
                sleep(1);
            }
        }

        // Variables for timestamps
        $submitTS=0;
        $confirmTS=0;
        $executeTS=0;
        // Extract timestamps from PolkAssembly response
        if (!empty($paResponse['statusHistory'])) {
		        foreach ($paResponse['statusHistory'] as $record) {
		            switch($record['status']) {
		                case "Submitted":
  		                  $dateTime = new \DateTime($record['timestamp']);
  		                  $submitTS = $dateTime->format('U');
  		                  break;
 		               case "Confirmed":
 		                   $dateTime = new \DateTime($record['timestamp']);
 		                   $confirmTS = $dateTime->format('U');
 		                   break;
 		               case "Executed":
 		                   $dateTime = new \DateTime($record['timestamp']);
 		                   $executeTS = $dateTime->format('U');
 		                   break;
 		           }
 		       }
				}

        // Retrieve referenda details from the RPC
        $result = $this->apiService->call('query', 'referenda', 'referendumInfoFor', [$refnum], $networkInfo->rpc_server_address);

        // Checks for approved and rejected status, and determine the last block for reference
        if (isset($result->result->approved)) {
            $lastblock = $result->result->approved[0];
            $lastblock = $lastblock - 1;
            $check = 1;
            $final = 1;
        }

        // Rejected status, lookup historical block
        if (isset($result->result->rejected)) {
            $lastblock = $result->result->rejected[0];
            $lastblock = $lastblock - 1;
            $check = 1;
            $final = 1;
        }

        // Ongoing status, use current data
        if (isset($result->result->ongoing)) {
            $ref = $result->result->ongoing;
        }

        // Pull data from historical block
        if ($check == 1 && $lastblock != 0) {
            $result = $this->apiService->call('rpc', 'chain', 'getBlockHash', [$lastblock], $networkInfo->rpc_server_address);
            $result = $this->apiService->call('query', 'referenda', 'referendumInfoFor', [$refnum], $networkInfo->rpc_server_address, $result->result);
            if (isset($result->result->ongoing)) {
                $ref = $result->result->ongoing;
            }
        }

        if (!isset($result->result->approved) && !isset($result->result->rejected) && !isset($result->result->ongoing)) {
            // Missing Data? Do something?
        } else {
            // Determine if the referenda exists in the database
            $query = $referendaTable->find()->where(['referenda_number' => $refnum, 'network_id' => $network_id]);

            // If the referenda exists in the database, fetch it. Otherwise, create a new entry.
            if ($query->count() > 0) {
                $referenda = $query->first();
            } else {
                $referenda = $referendaTable->newEmptyEntity();
            }

            // Initialize the amount variable for later calculation
            $amount = 0;
            // If the proposal has a lookup value, process it
            if (!empty($result->result->ongoing->proposal->lookup)) {
                $referenda->preimage_hash = $result->result->ongoing->proposal->lookup->hash;
                $referenda->preimage_length = intval($result->result->ongoing->proposal->lookup->len);

                // Fetch preimage data using the preimage hash
                $preimageData = $this->apiService->call(
                    'query', 
                    'preimage', 
                    'preimageFor', 
                    [[$referenda->preimage_hash, $referenda->preimage_length]], 
                    $networkInfo->rpc_server_address
                );
  
                // Compute the total token amount for the proposal
                $amount = $this->recursiveSum($preimageData);

                $type = "";
                if ($amount > 0) {
                    $type = "Spend";
                }
            }

            // Define exchange rate holders
            $submitExchange = 0;
            $executeExchange = 0;

            // Delay in seconds.
            $maxRetries = 3;
            $retryDelay = 2;

            // Retry logic for fetching the exchange rate data from Subscan API
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                	  // Define the API endpoint for the price converter service on Subscan
                    $apiUrl = "https://".$networkInfo->long_name.".api.subscan.io/api/open/price_converter";

                    // Initialize the HTTP client
                    $http = new Client();
                    
                    // Request payload to fetch the exchange rate at the time of the submission
                    $requestData = ['time' => intval($submitTS), 'value' => 1, 'from' => $networkInfo->short_name, 'quote' => 'USD'];                
                    $headers = ['Content-Type' => 'application/json', 'X-API-Key' => 'cef5fa19cd5c4e13b152ce8722ff0a91'];
                    $response = $http->post($apiUrl, json_encode($requestData), ['headers' => $headers, 'type' => 'json']);

                    // If the response is successful, set the exchange rate for the submission
                    if ($response->isOk()) {
                        $subResponse = $response->getJson();
                        $submitExchange = $subResponse['data']['output'];
                    }

                    // If there's an execution timestamp, fetch the exchange rate at that time
                    if (!empty($executeTS)) {
                        $requestData = ['time' => intval($executeTS), 'value' => 1, 'from' => $networkInfo->short_name, 'quote' => 'USD'];                
                        $headers = ['Content-Type' => 'application/json', 'X-API-Key' => 'cef5fa19cd5c4e13b152ce8722ff0a91'];
                        $response = $http->post($apiUrl, json_encode($requestData), ['headers' => $headers, 'type' => 'json']);
                        
                        // If the response is successful, set the exchange rate for the execution
                        if ($response->isOk()) {
                            $subResponse = $response->getJson();
                            $executeExchange = $subResponse['data']['output'];
                        }
                    }

                    break;
                } catch (\Cake\Http\Client\Exception\NetworkException $e) {
                    // If max retries reached and still failed, throw the exception
                    if ($attempt === $maxRetries) {
                        throw $e;
                    }
                    // Delay before the next retry
                    sleep($retryDelay);
                }
            }

            // Assigning values from the API responses to the referenda entity
            $referenda->referenda_number = $refnum;
            $referenda->network_id = $network_id;
            $referenda->final = $final;
            $referenda->track = $result->result->ongoing->track;

            if (!empty($result->result->ongoing->origin->origins)) {
                $referenda->origin = intval($result->result->ongoing->origin->origins);
            }
            
            if (!empty($result->result->ongoing->enactment->after)) {
                $referenda->enactment_after = intval($result->result->ongoing->enactment->after);
            }

            $referenda->submitted_at = intval($result->result->ongoing->submitted);
            $referenda->submission_deposit_address = $result->result->ongoing->submissionDeposit->who;
            $referenda->submission_deposit_amount = intval($result->result->ongoing->submissionDeposit->amount) / $networkInfo->decimal_places;

            if (!empty($amount)) {
                $referenda->amount = intval($amount) / $networkInfo->decimal_places;
            } else {
                if (!empty($paResponse['requested']) && intval($amount) == "0") {
                    $referenda->amount = intval($paResponse['requested']) / $networkInfo->decimal_places;
                } else {
                    $referenda->amount = 0;
                }
            }

            if (!empty($submitExchange)) {
                $referenda->submission_exchange_rate = $submitExchange;
            }

            if (!empty($executeExchange)) {
                $referenda->executed_exchange_rate = $executeExchange;
            }

            if (!empty($submitTS)) {
                $referenda->submission_ts = $submitTS;
            }

            if (!empty($confirmTS)) {
                $referenda->confirmed_ts = $confirmTS;
            }

            if (!empty($executeTS)) {
                $referenda->executed_ts = $executeTS;
            }

            if (!empty($result->result->ongoing->decisionDeposit->who) && !empty($result->result->ongoing->decisionDeposit->amount)) {
                $referenda->decision_deposit_address = $result->result->ongoing->decisionDeposit->who;
                $referenda->decision_deposit_amount = intval($result->result->ongoing->decisionDeposit->amount) / $networkInfo->decimal_places;
            }

            if (!empty($result->result->ongoing->deciding->since)) {
                $referenda->deciding_since = intval($result->result->ongoing->deciding->since);
            }

            if (!empty($paResponse['title'])) {
                $referenda->title = $paResponse['title'];
            }

            if (!empty($paResponse['content'])) {
                $referenda->description = $paResponse['content'];
            }

            if (!empty($referenda->confirming_since)) {
                $referenda->confirming_since = intval($result->result->ongoing->confirming->since);
            }

            // Convert hex values to decimal and adjust for token decimal places
            if (str_contains($result->result->ongoing->tally->ayes, '0x')) { 
                $ayes = hexdec($result->result->ongoing->tally->ayes); 
                $ayes = $ayes / $networkInfo->decimal_places; 
                $referenda->tally_ayes = $ayes;
            } else {
                $ayes = $result->result->ongoing->tally->ayes / $networkInfo->decimal_places;
                $referenda->tally_ayes = $ayes;
            }

            // Convert hex values to decimal and adjust for token decimal places
            if (str_contains($result->result->ongoing->tally->nays, '0x')) {
                $nays = hexdec($result->result->ongoing->tally->nays); 
                $nays = $nays / $networkInfo->decimal_places; 
                $referenda->tally_nays = $nays;
            } else {
                $nays = $result->result->ongoing->tally->nays / $networkInfo->decimal_places;
                $referenda->tally_nays = $nays;
            }

            // Convert hex values to decimal and adjust for token decimal places
            if (str_contains($result->result->ongoing->tally->support, '0x')) {
                $support = hexdec($result->result->ongoing->tally->support); 
                $support = $support / $networkInfo->decimal_places; 
                $referenda->tally_support = $support;
            } else {
                $support = $result->result->ongoing->tally->support / $networkInfo->decimal_places;
                $referenda->tally_support = $support;
            }

            // Save the referenda data to the database
            if ($referendaTable->save($referenda)) {
                echo "Referenda $refnum: Saved successfully.\r\n";
            } else {
                echo "Referenda $refnum: Failed to save.\r\n";
            }
        }
    }
}
