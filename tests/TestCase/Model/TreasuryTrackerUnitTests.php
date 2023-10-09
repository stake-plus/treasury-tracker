<?PHP

use App\Model\Entity\User;
use App\Model\Entity\Referendum;
use App\Model\Entity\W3fClassification;
use App\Model\Table\UsersTable;
use App\Model\Table\ReferendaTable;
use App\Model\Table\W3fClassificationTable;
use App\Service\PolkadotApiService;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

class TreasuryTrackerUnitTests extends TestCase
{
    public $fixtures = [
        'app.Users',
        'app.Referenda',
        'app.W3fClassifications',
    ];

    // Initialization
    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Referenda = TableRegistry::getTableLocator()->get('Referenda');
        $this->W3fClassifications = TableRegistry::getTableLocator()->get('W3fClassifications');
        $this->PolkadotApiService = new PolkadotApiService();
    }

    public function tearDown(): void
    {
        unset($this->Users, $this->Referenda, $this->W3fClassifications, $this->PolkadotApiService);
        parent::tearDown();
    }

    // Entity Tests
    public function testUserEntityValidation()
    {
        $user = new User(['address' => 'SomeAddress']);
        $errors = $user->getErrors();
        $this->assertEmpty($errors, 'Validation errors found for User entity.');
    }

    public function testReferendumEntityValidation()
    {
        $referendum = new Referendum(['network_id' => 1]); // Assuming a network_id is required
        $errors = $referendum->getErrors();
        $this->assertEmpty($errors, 'Validation errors found for Referendum entity.');
    }

    public function testW3fClassificationEntityValidation()
    {
        $classification = new W3fClassification(['classification_name' => 'Infrastructure']);
        $errors = $classification->getErrors();
        $this->assertEmpty($errors, 'Validation errors found for W3fClassification entity.');
    }

    // Service Tests
    public function testPolkadotApiServiceCall()
    {
        $result = $this->PolkadotApiService->call('query', 'someNamespace', 'someMethod');
        $this->assertNotNull($result, 'API call did not return any result.');
    }

    public function testGetReferendumCount()
    {
        $result = $this->PolkadotApiService->getReferendumCount();
        $this->assertTrue(is_int($result), 'Expected integer referendum count.');
    }

    public function testGetProposalCount()
    {
        $result = $this->PolkadotApiService->getProposalCount();
        $this->assertTrue(is_int($result), 'Expected integer proposal count.');
    }

    // Table Tests
    public function testAddNewUser()
    {
        $data = [
            'address' => 'NewAddress',
            'signature' => 'NewSignature',
        ];
        $user = $this->Users->newEntity($data);
        $savedUser = $this->Users->save($user);
        $this->assertNotNull($savedUser, 'New user not saved.');
    }

    public function testFetchReferendaByNetworkId()
    {
        $query = $this->Referenda->find()->where(['network_id' => 1]);
        $results = $query->all();
        $this->assertNotEmpty($results, 'No referenda found for given network_id.');
    }

    public function testFetchW3fClassificationByName()
    {
        $query = $this->W3fClassifications->find()->where(['classification_name' => 'Infrastructure']);
        $results = $query->all();
        $this->assertNotEmpty($results, 'No classification found by given name.');
    }
}
