<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CandidateService
{
    private $http;

    public function __construct()
    {
        $token = $_COOKIE['token'] ?? null;
        $this->prepareRequest($token);
    }

    public function prepareRequest($token)
    {
        $this->http = Http::baseUrl(config('app.candidate_base_url').'/api/v2/')
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ]);
    }

    public function get($uri, $data = [])
    {
        return $this->prepareResponse($this->http->get($uri, $data));
    }

    public function post($uri, $data = [])
    {
        return $this->prepareResponse($this->http->post($uri, $data));
    }

    public function put($uri, $data = [])
    {
        return $this->prepareResponse($this->http->post($uri, $data));
    }

    public function delete($uri)
    {
        return $this->prepareResponse($this->http->delete($uri));
    }

    public function prepareResponse($response)
    {
        if ($response->status() === 403) {
            return response()->json('Invalid authorization', 403);
        }

        return $response;
    }

    

    /**
     * Create Auther from console command
     */
    public function createAuthor($body)
    {
        $token = $this->getToken();
        $this->prepareRequest($token);

        return $this->http->post('authors', $body);
    }

    /**
     * this is for console command authentication
     */
    public function getToken()
    {
        try {
            $body = [
                'email' => 'ahsoka.tano@royal-apps.io',
                'password' => 'Kryze4President',
            ];
            $response = $this->prepareResponse($this->http->post('token', $body));
            if ($response->status() === 200) {
                return $response->object()->token_key;
            }

            return false;
        } catch (\Exception $e) {

            return false;
        }
    }
}
