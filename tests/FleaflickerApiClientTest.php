<?php

use DanAbrey\FleaflickerApi\FleaflickerApiClient;
use DanAbrey\FleaflickerApi\Models\FleaflickerLeague;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class FleaflickerApiClientTest extends TestCase
{
    private FleaflickerApiClient $client;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = new DanAbrey\FleaflickerApi\FleaflickerApiClient();
    }

    public function testUserLeagues()
    {
        $data = file_get_contents(__DIR__ . '/_data/user-leagues/leagues.json');
        $responses = [
            new MockResponse($data),
        ];
        $client = new MockHttpClient($responses);
        $this->client->setHttpClient($client);
        $leagues = $this->client->userLeagues('email@mail.com');
        $this->assertIsArray($leagues);
        $this->assertInstanceOf(FleaflickerLeague::class, $leagues[0]);
        $this->assertEquals('209741', $leagues[0]->id);
        $this->assertEquals('Area 51', $leagues[0]->name);
    }
}
