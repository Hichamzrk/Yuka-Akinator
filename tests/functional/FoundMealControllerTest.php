<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\TestDataFixtures\TestOneMealFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class CreateMealControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }
    
    public function testWhenOneMealExist(): void
    {
        $this->databaseTool->setPurgeMode();
        $this->databaseTool->loadFixtures([
            TestOneMealFixtures::class 
        ]);

        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertSelectorTextContains('#search-emoji', '😁');
        $this->assertSelectorTextContains('h1', "Je pense que c'est une pizza !");
        $this->assertSelectorTextContains('p', "Je connais 1 plat, je crois que j'ai trouvé !");
        $this->assertSelectorTextContains('p', "Je connais 1 plat, je crois que j'ai trouvé !");
        $this->assertSelectorTextContains('#question_Oui', "Waouh, bien joué !");
        $this->assertSelectorTextContains('#question_Non', "Non pas du tout !");
        
        $client->submitForm('question_Oui');
        
        $this->assertSelectorTextContains('h1', "Une pizza ! Héhé, j'en étais sur !");
        $this->assertSelectorExists('#restartButton');
        
        $client->submitForm('restartButton');
        
        $this->assertResponseStatusCodeSame(302);
        
        $client->followRedirect();
        $client->submitForm('question_Non');
        
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertSelectorTextContains('h1', "Oups, j'étais pourtant sûr que c'étais une pizza.");
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}