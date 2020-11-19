<?php declare(strict_types=1);


namespace App\Tests\Controller;


use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SensitiveControllerTest
 * @package App\Tests\Controller
 */
class SensitiveControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([ClientFixtures::class]);
    }
}
