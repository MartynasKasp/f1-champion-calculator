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
    ) {
    }

    /**
     * @return RaceResultDTO[]
     */
    public function getRaceResults(string $season = 'current', string $race = 'last'): array
    {
        $client = (new HttpClient())->create(['headers' => ['Content-Type' => 'application/json']]);
        $response = $client->request('GET', "http://ergast.com/api/f1/$season/$race/results.json");
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
}
