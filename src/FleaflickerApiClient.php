<?php

namespace DanAbrey\FleaflickerApi;

use DanAbrey\FleaflickerApi\Models\FleaflickerLeague;
use Symfony\Component\HttpClient\HttpClient;
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
        $this->httpClient = HttpClient::create();
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

    protected function getUrl(array $arguments = []): string
    {
        return sprintf(
            "%s?%s",
            self::API_BASE,
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

        $response = $this->makeRequest('GET', $this->getUrl($arguments));

        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers);
        return $serializer->denormalize($response['leagues'], FleaflickerLeague::class . '[]');
    }
}
