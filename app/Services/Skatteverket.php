<?php

namespace App\Services;

use App\Models\Country;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Skatteverket
{
    protected $endpoint, $client, $resource;
    protected $list;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getResource($endpoint)
    {
        try {
            return $this->client->request('GET', 'https://skatteverket.entryscape.net/rowstore'. $endpoint, [
                'headers' => ['Accept' =>  "application/json"]
            ]);
        } catch (ClientException $e) {
            report($e);
            abort(404);
        }
    }

    //Retrieving countries
    public function getCountry()
    {
        //Retrive allowances
        $year = 2025;
        $this->array_resource = json_decode($this->getResource('/dataset/70ccea31-b64c-4bf5-84c7-673f04f32505?%C3%A5r=' . $year . '&_limit=500&_offset=0')->getBody()->getContents(), TRUE);

        foreach ($this->array_resource['results'] as $result_country) {
            $country = Country::updateOrCreate(
                ['country' => $result_country['land eller område']],
                ['allowance' => $result_country['normalbelopp'],
                  'year' =>  $result_country['år']]
            );

        }
        return true;
    }

    public function checkAllowance()
    {
        $countries = Country::all();
        foreach($countries as $country) {
            switch($country->allowance) {
                //Burma
                case('Se Myanmar'):
                    $assign = Country::where('country', 'Myanmar')->first();
                    $country->allowance = $assign->allowance;
                    break;
                //Grönland
                case('Se Danmark'):
                    $assign = Country::where('country', 'Danmark')->first();
                    $country->allowance = $assign->allowance;
                    break;
                //Hong Kong
                case('Se Kina'):
                    $assign = Country::where('country', 'Kina')->first();
                    $country->allowance = $assign->allowance;
                    break;
                //Iran
                case('Se Övriga länder och områden'):
                    $assign = Country::where('country', 'Övriga länder och områden')->first();
                    $country->allowance = $assign->allowance;
                    break;
                //Makedonien
                case('Se Nordmakedonien'):
                    $assign = Country::where('country', 'Nordmakedonien, f.d. Makedonien')->first();
                    $country->allowance = $assign->allowance;
                    break;
                //Puerto Rico
                case('Se USA'):
                    $assign = Country::where('country', 'USA')->first();
                    $country->allowance = $assign->allowance;
                    break;
                //San Marino
                case('Se Italien'):
                    $assign = Country::where('country', 'Italien')->first();
                    $country->allowance = $assign->allowance;
                    break;
                //Swaziland
                case('Se Eswatini'):
                    $assign = Country::where('country', 'Eswatini')->first();
                    $country->allowance = $assign->allowance;
                    break;
                //Vitryssland
                case('Se Belarus'):
                    $assign = Country::where('country', 'Belarus')->first();
                    $country->allowance = $assign->allowance;
                    break;
            }
            $country->save();
        }
    }
}
