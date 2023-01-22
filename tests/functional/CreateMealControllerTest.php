<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\TestDataFixtures\TestOneMealFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class FoundMealControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testCreateMealWithTrueQuestion(): void
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
        $client->submitForm('question_Non');
        
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $client->submitForm('new_meal_submit', [
            'new_meal[meal_name]' => "Une glace",
            'new_meal[question]' => "Est-ce que c'est froid ?",
            'new_meal[yes_no]' => "1"
        ]);

        $client->submitForm('restartButton');

        $this->assertSelectorTextContains('h1', "Est-ce que c'est froid ?");
    }
    
    public function testCreateMealWithFalseQuestion(): void
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
        $client->submitForm('question_Non');
        
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $client->submitForm('new_meal_submit', [
            'new_meal[meal_name]' => "Une glace",
            'new_meal[question]' => "Est-ce que c'est chaud ?",
            'new_meal[yes_no]' => "0"
        ]);

        $client->submitForm('restartButton');

        $this->assertSelectorTextContains('h1', "Est-ce que c'est chaud ?");
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}