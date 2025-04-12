<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class StatDataService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getApplePriceData(): array
    {
        return Cache::remember('apple_price_data', now()->addDay(), function () {
            $response = $this->client->post('https://pxweb.stat.si:443/SiStatData/api/v1/en/Data/0411005S.px', [
                'json' => [
                    "query" => [
                        [
                            "code" => "IZDELKI IN STORITVE",
                            "selection" => [
                                "filter" => "item",
                                "values" => ["0116103000"]
                            ]
                        ]
                    ],
                    "response" => [
                        "format" => "json-stat"
                    ]
                ]
            ]);
            return json_decode($response->getBody(), true);
        });
    }

    public function getYearlyPriceData(): array
    {
        $data = $this->getApplePriceData();
        $years = array_values($data['dataset']['dimension']['LETO']['category']['label']);
        $prices = $data['dataset']['value'];
        return ['years' => $years, 'prices' => $prices];
    }

    public function getLatestYield(): float
    {
        $priceData = $this->getYearlyPriceData();
        $prices = $priceData['prices'];
        $latestPrice = end($prices);
        return $latestPrice / 100;
    }

    public function getAverageRetailCost(): float
    {
        $priceData = $this->getYearlyPriceData();
        $prices = $priceData['prices'];
        if(empty($prices)) {
            return 0;
        }
        return array_sum($prices) / count($prices);
    }
}
