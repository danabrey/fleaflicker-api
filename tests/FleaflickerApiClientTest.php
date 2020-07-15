<?php

use DanAbrey\FleaflickerApi\FleaflickerApiClient;
use DanAbrey\FleaflickerApi\Models\FleaflickerLeague;
use DanAbrey\FleaflickerApi\Models\FleaflickerLeagueRosterPosition;
use DanAbrey\FleaflickerApi\Models\FleaflickerLeagueRosterRequirements;
use DanAbrey\FleaflickerApi\Models\FleaflickerPlayer;
use DanAbrey\FleaflickerApi\Models\FleaflickerProPlayer;
use DanAbrey\FleaflickerApi\Models\FleaflickerRoster;
use DanAbrey\FleaflickerApi\Models\FleaflickerTeam;
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
        $this->assertInstanceOf(FleaflickerTeam::class, $leagues[0]->ownedTeam);
        $this->assertInstanceOf(FleaflickerLeagueRosterRequirements::class, $leagues[0]->rosterRequirements);

        $this->assertIsArray($leagues[0]->rosterRequirements->positions);
        $this->assertInstanceOf(FleaflickerLeagueRosterPosition::class, $leagues[0]->rosterRequirements->positions[0]);
        $this->assertCount(8, $leagues[0]->rosterRequirements->positions);

    }

    public function testRosters()
    {
        $data = file_get_contents(__DIR__ . '/_data/rosters/FetchLeagueRosters.json');
        $responses = [
            new MockResponse($data),
        ];
        $client = new MockHttpClient($responses);
        $this->client->setHttpClient($client);
        $rosters = $this->client->rosters(111);
        $this->assertIsArray($rosters);
        $this->assertInstanceOf(FleaflickerRoster::class, $rosters[1]);
        $this->assertInstanceOf(FleaflickerTeam::class, $rosters[1]->team);
        $this->assertEquals(1387320, $rosters[1]->team->id);
        $this->assertEquals('Antonio Brown Can Go Yuck Fimself', $rosters[1]->team->name);

        $this->assertIsArray($rosters[1]->players);
        $this->assertInstanceOf(FleaflickerPlayer::class, $rosters[1]->players[4]);
        $this->assertInstanceOf(FleaflickerProPlayer::class, $rosters[1]->players[4]->proPlayer);
        $this->assertEquals(12929, $rosters[1]->players[4]->proPlayer->id);
        $this->assertIsArray($rosters[1]->players[4]->proPlayer->externalIds);
        $this->assertEquals('8960d61e-433b-41ea-a7ad-4e76be87b582', $rosters[1]->players[4]->proPlayer->externalIds[0]->id);
    }
}
