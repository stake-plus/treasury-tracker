<?PHP
// Define a namespace for the command
namespace App\Command;

// Import required classes
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use Symfony\Component\Process\Process;
use App\Service\PolkadotApiService;

// Define a command class called ReferendaUpdateCommand that extends CakePHP's Command class
class ReferendaUpdateCommand extends Command
{
    private $apiService; // Define a private property for the PolkadotApiService

    // Function to initialize configurations or services
    public function initialize(): void
    {
        parent::initialize(); // Call the parent initialize method
        $this->apiService = new PolkadotApiService(); // Initialize the PolkadotApiService
    }

		public function execute(Arguments $args, ConsoleIo $io)
    {
        // Load the referenda and networks tables from the database
        $referendaTable = TableRegistry::getTableLocator()->get('Referenda');
        $networkTable = TableRegistry::getTableLocator()->get('Networks');

        // Retrieve all referenda that are not finalized from the database
        $nonFinalizedReferenda = $referendaTable->find()
            ->where([
                'final !=' => '1'
            ])
            ->toArray();

        // Update each non-finalized referendum
        foreach ($nonFinalizedReferenda as $referendum) {
            // Construct the command to update the referendum
            $forkCommand = [ROOT.'/bin/cake', 'referenda_import', '--network='.$referendum->network_id, '--refnum='.$referendum->referenda_number];
            $process = new Process($forkCommand); // Create a new process for the command
            $process->start(); // Start the process
            sleep(2); // Pause execution for 2 seconds
        }

        // Fetch all networks from the database
        $networks = $networkTable->find();

        // For each network, import new referenda
        foreach ($networks as $network) {
            // Make an API call to get the count of referenda
            $result = $this->apiService->call('query', 'referenda', 'referendumCount', [], $network->rpc_server_address);

            // Check if the result is in hex format and convert it if necessary
						if (str_contains($result->result, '0x')) {
							$referenda_count_api = hexdec($result->result);
						} else {
							$referenda_count_api = $result->result;
						}

            // Fetch the highest referenda number in the database for the current network
            $highestReferenda = $referendaTable->find('all', [
                'conditions' => ['network_id' => $network->id],
                'order' => ['referenda_number' => 'DESC'],
                'limit' => 1
            ])->first();

            $highestReferendaNumber = $highestReferenda ? $highestReferenda->referenda_number : 0;

            // For each new referendum not in the database, start the import process
            for ($i = $highestReferendaNumber + 1; $i < $referenda_count_api; $i++) {
                $forkCommand = [ROOT . '/bin/cake', 'referenda_import', '--network=' . $network->id, '--refnum=' . $i];
                $process = new Process($forkCommand);
                $process->start();
                sleep(2);
            }
        }
    }
}
