<?php

namespace ErgastAPI;

use App\Trait\LoggerInjector;
use ErgastAPI\Model\RaceResultDTO;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\SerializerInterface;

class Connector
{
    use LoggerInjector;

    public function __construct(
        private SerializerInterface $serializer,
        private string $host = 'https://api.jolpi.ca/ergast/f1/',
    ) {
    }

    /**
     * @return RaceResultDTO[]
     */
    public function getRaceResults(string $season = 'current', string $race = 'last'): array
    {
        $client = (new HttpClient())->create(['headers' => ['Content-Type' => 'application/json']]);
        $response = $client->request('GET', "{$this->host}/$season/$race/results.json");
        try {
            $response = json_decode($response->getContent(), true);
            $results = $response['MRData']['RaceTable']['Races'][0]['Results'];

            return $this->serializer->deserialize(
                json_encode($results),
                RaceResultDTO::class . '[]',
                'json',
            );
        } catch (\Exception $exception) {
            $this->logger->error('ErgastAPI: failed fetching race results. Error: ' . $exception->getMessage());
            return [];
        }
    }

    /**
     * @return RaceResultDTO[]
     */
    public function getSprintResults(string $season = 'current', string $race = 'last'): array
    {
        $client = (new HttpClient())->create(['headers' => ['Content-Type' => 'application/json']]);
        $response = $client->request('GET', "{$this->host}/$season/$race/sprint/");
        try {
            $response = json_decode($response->getContent(), true);
            $results = $response['MRData']['RaceTable']['Races'][0]['SprintResults'];

            return $this->serializer->deserialize(
                json_encode($results),
                RaceResultDTO::class . '[]',
                'json',
            );
        } catch (\Exception $exception) {
            $this->logger->error('ErgastAPI: failed fetching race results. Error: ' . $exception->getMessage());
            return [];
        }
    }

    public function checkRaceResultsInfo(string $season = 'current', string $race = 'last'): array
    {
        $client = (new HttpClient())->create(['headers' => ['Content-Type' => 'application/json']]);
        $response = $client->request('GET', "{$this->host}/$season/$race/results.json");
        try {
            $response = json_decode($response->getContent(), true);
            return $response['MRData']['RaceTable']['Races'][0];
        } catch (\Exception $exception) {
            $this->logger->error('ErgastAPI: failed fetching race results. Error: ' . $exception->getMessage());
            return [];
        }
    }

    public function checkSprintResultsInfo(string $season = 'current', string $race = 'last'): array
    {
        $client = (new HttpClient())->create(['headers' => ['Content-Type' => 'application/json']]);
        $response = $client->request('GET', "{$this->host}/$season/$race/sprint/");
        try {
            $response = json_decode($response->getContent(), true);
            return $response['MRData']['RaceTable']['Races'][0];
        } catch (\Exception $exception) {
            $this->logger->error('ErgastAPI: failed fetching race results. Error: ' . $exception->getMessage());
            return [];
        }
    }
}
