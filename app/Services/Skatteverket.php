<?php

namespace App\Services;

use App\Models\Country;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class Skatteverket
{
    private const BASE_URI = 'https://skatteverket.entryscape.net/rowstore';

    private const DAILY_ALLOWANCE_DATASET = '/dataset/70ccea31-b64c-4bf5-84c7-673f04f32505';

    private const REFERENCED_ALLOWANCES = [
        'Se Myanmar' => 'Myanmar',
        'Se Danmark' => 'Danmark',
        'Se Övriga länder och områden' => 'Övriga länder och områden',
        'Se Nordmakedonien' => 'Nordmakedonien, f.d. Makedonien',
        'Se Italien' => 'Italien',
        'Se Eswatini' => 'Eswatini',
    ];

    protected Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client;
    }

    public function getResource(string $endpoint): ResponseInterface
    {
        try {
            return $this->client->request('GET', self::BASE_URI.$endpoint, [
                'headers' => ['Accept' => 'application/json'],
            ]);
        } catch (GuzzleException $e) {
            report($e);
            abort(404, 'Could not retrieve Skatteverket resource.');
        }
    }

    public function getCountry(?int $year = null): bool
    {
        $year ??= now()->year;

        $query = http_build_query([
            'år' => $year,
            '_limit' => 500,
            '_offset' => 0,
        ], '', '&', PHP_QUERY_RFC3986);

        $resource = json_decode(
            $this->getResource(self::DAILY_ALLOWANCE_DATASET.'?'.$query)->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($resource['results'] ?? [] as $resultCountry) {
            Country::updateOrCreate(
                ['country' => $resultCountry['land eller område']],
                [
                    'allowance' => $resultCountry['normalbelopp'],
                    'year' => $resultCountry['år'],
                ]
            );
        }

        return true;
    }

    public function checkAllowance(): void
    {
        $allowancesByCountry = Country::query()->pluck('allowance', 'country');

        Country::query()
            ->whereIn('allowance', array_keys(self::REFERENCED_ALLOWANCES))
            ->each(function (Country $country) use ($allowancesByCountry): void {
                $referencedCountry = self::REFERENCED_ALLOWANCES[$country->allowance];
                $allowance = $allowancesByCountry->get($referencedCountry);

                if ($allowance === null) {
                    report(new RuntimeException("Missing referenced allowance country: {$referencedCountry}"));

                    return;
                }

                if ($country->allowance === $allowance) {
                    return;
                }

                $country->update(['allowance' => $allowance]);
            });
    }
}
