<?PHP
namespace App\Command;

// Import necessary libraries and services
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\CommandRunner;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use App\Service\PolkadotApiService;

// Define a command class for backfilling opengov data
class OpengovBackfillCommand extends Command
{
    // Declaration of the Polkadot API Service
    private $apiService;

    // Initialization method
    public function initialize(): void
    {
        parent::initialize();
        $this->apiService = new PolkadotApiService();
    }

    // Build option parser for command-line arguments
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->addOption('network', [
                'help' => 'The network to use (Polkadot or Kusama).',
                'default' => 'Polkadot',
            ]);

        return $parser;
    }

    // Execution method for the command
    public function execute(Arguments $args, ConsoleIo $io)
    {
        // Track the start time for performance measurement
        $start_time = microtime(true);

        // Get the network option passed to the command
        $network = $args->getOption('network');
        $network_id = 0;

        // Initialize the referenda and networks tables
        $referendaTable = TableRegistry::getTableLocator()->get('Referenda');
        $networkTable = TableRegistry::getTableLocator()->get('Networks');

        // Check if the provided network exists in the database
        $query = $networkTable->find()->where(['long_name' => $network]);
        if ($query->count() > 0) {
            $networkInfo = $query->first();
            $network_id = $networkInfo->id;
        } else {
            echo "Unknown Network\r\n";
            die;
        }

        // Call the API to get the count of referenda
        $result = $this->apiService->call('query', 'referenda', 'referendumCount', [], $networkInfo->rpc_server_address);
        $referenda_count = hexdec($result->result);

        // Loop through all referenda
        $i = 0;
        while ($i < $referenda_count) {
            // Execute a shell command for each referenda
            $output = shell_exec("/var/www/governance-platform/bin/cake referenda_import --network=" . $networkInfo->id . " --refnum=" . $i . " &");
            print_r($output);
            sleep(2);

            $i++;
        }

        // Track the end time and calculate the execution time
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);
        echo "Execution time of script = " . $execution_time . " sec\r\n";
    }
}

