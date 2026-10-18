<?php

namespace DanAbrey\FleaflickerApi;

use DanAbrey\FleaflickerApi\Models\FleaflickerDraftPick;
use DanAbrey\FleaflickerApi\Models\FleaflickerLeague;
use DanAbrey\FleaflickerApi\Models\FleaflickerRoster;
use DanAbrey\FleaflickerApi\Models\FleaflickerTeam;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FleaflickerApiClient
{
    private HttpClientInterface $httpClient;
    private Serializer $serializer;

    protected const API_BASE = "https://www.fleaflicker.com/api";

    public function __construct()
    {
        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers);
        // Fleaflicker's WAF blocks the HTTP/2 fingerprint produced by some
        // libcurl builds (e.g. the Heroku PHP buildpack) on data-heavy
        // endpoints like FetchLeagueRosters. Forcing HTTP/1.1 avoids it.
        $this->httpClient = HttpClient::create(['http_version' => '1.1']);
    }

    protected function getArgumentsForUrl(array $arguments = []): string
    {
        return http_build_query($arguments);
    }

    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    protected function getClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    protected function getUrl(string $action, array $arguments = []): string
    {
        return sprintf(
            "%s/%s?%s",
            self::API_BASE,
            $action,
            $this->getArgumentsForUrl($arguments),
        );
    }

    protected function makeRequest(string $method, string $url): array
    {
        $response = $this->getClient()->request($method, $url);
        $decodedResponse = json_decode($response->getContent(), true);
        return $decodedResponse;
    }

    /**
     * @param string $email
     * @return array|FleaflickerLeague[]
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function userLeagues(string $email): array
    {
        $arguments = [
            'email' => $email,
        ];

        $response = $this->makeRequest('GET', $this->getUrl('FetchUserLeagues', $arguments));

        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer(null, null, null, new PhpDocExtractor())];
        $serializer = new Serializer($normalizers);
        return $serializer->denormalize($response['leagues'] ?? [], FleaflickerLeague::class . '[]');
    }

    /**
     * @param int $leagueId
     * @return array|FleaflickerTeam[]
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function rosters(int $leagueId): array
    {
        $arguments = [
            'league_id' => $leagueId,
            'external_id_type' => 'SPORTRADAR',
        ];
$url = $this->getUrl('FetchLeagueRosters', $arguments);
        $response = $this->makeRequest('GET', $this->getUrl('FetchLeagueRosters', $arguments));

        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer(null, null, null, new PhpDocExtractor())];
        $serializer = new Serializer($normalizers);
        return $serializer->denormalize($response['rosters'] ?? [], FleaflickerRoster::class . '[]');
    }

    /**
     * Picks owned by the team, plus picks the team originally owned but has traded away (flagged `lost`)
     *
     * @param int $leagueId
     * @param int $teamId
     * @return array|FleaflickerDraftPick[]
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function teamPicks(int $leagueId, int $teamId): array
    {
        $arguments = [
            'sport' => 'NFL',
            'league_id' => $leagueId,
            'team_id' => $teamId,
        ];

        $response = $this->makeRequest('GET', $this->getUrl('FetchTeamPicks', $arguments));

        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer(null, null, null, new PhpDocExtractor())];
        $serializer = new Serializer($normalizers);
        return $serializer->denormalize($response['picks'] ?? [], FleaflickerDraftPick::class . '[]');
    }
}
