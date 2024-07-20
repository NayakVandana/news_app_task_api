<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use PDO;
use Illuminate\Support\Facades\Http;

class NewsService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = "0da6d956e1fa4cc6957132f0723e640d";
    }

    public function getArticles($query = 'tesla', $from = '2024-06-20', $sortBy = 'publishedAt')
    {
        
        $response = Http::get('https://newsapi.org/v2/everything', [
            'q' => $query,
            'from' => $from,
            'sortBy' => $sortBy,
            'apiKey' => $this->apiKey,
        ]);

        if ($response->successful()) {
            
            return  (object) json_decode($response, true);
            
           
        } else {
            return []; // or handle the case where the request was not successful
        }
    }

}