<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\TestDataFixtures\TestOneMealFixtures;
use App\DataFixtures\TestDataFixtures\TestManyNodesFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use App\DataFixtures\TestDataFixtures\TestOneQuestionFixtures;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class QuestionControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testTrueQuestionExistWhenYesSubmitted(): void
    {
        $this->databaseTool->setPurgeMode();
        $this->databaseTool->loadFixtures([
            TestOneQuestionFixtures::class 
        ]);

        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('question_Oui');

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        
        $this->assertSelectorTextContains('h1', "Je pense que c'est une glace !");
    }

    public function testTrueQuestionExistWhenNoSubmitted(): void
    {
        $this->databaseTool->setPurgeMode();
        $this->databaseTool->loadFixtures([
            TestOneQuestionFixtures::class 
        ]);

        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('question_Non');

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertSelectorTextContains('h1', "Je pense que c'est une pizza !");
    }

    public function testCountLastNode()
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

        $this->assertSelectorTextContains('p', "Je connais 1 plat, je crois que j'ai trouvé !");
    }

    public function testCountNodes()
    {
        $this->databaseTool->setPurgeMode();
        $this->databaseTool->loadFixtures([
            TestManyNodesFixtures::class 
        ]);

        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('p', "Je connais 3 plats, je vais trouver en 3 questions maximum !");

        $client->submitForm('question_Non');

        $this->assertSelectorTextContains('p', "Je connais 3 plats, je vais trouver en 2 questions maximum !");
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}