<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait CandidateApiTrait
{
    /**
     * Create an HTTP client with token and optional base URL.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     * @throws \Exception
     */
    protected function apiClient($token = null)
    {
        // Retrieve the token from the session
        if (empty($token)) {
            $token = session('candidateToken');
        }

        $client = Http::baseUrl(config('services.candidate_api.base_url') . '/api/v2/')
            ->withHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ]);

        return $client;
    }



    /**
     * Login to the Candidate Testing API.
     *
     * @param  string  $email
     * @param  string  $password
    * @return \Illuminate\Http\Client\Response|array
     */
    public function candidateLogin($email, $password)
    {
        $response = $this->apiClient()
            ->post('/token', [
                'email' => $email,
                'password' => $password,
            ]);

        return $this->verifyResponse($response);
    }

    /**
     * Fetch all authors from the Candidate Testing API.
     *
     * @return \Illuminate\Http\Client\Response|array
     */
    public function fetchAuthors($param)
    {
        $response = $this->apiClient()
            ->get('/authors', $param);

        return $this->verifyResponse($response);
    }

    /**
     * Fetch a specific author from the Candidate Testing API.
     *
     * @param  int  $id
    * @return \Illuminate\Http\Client\Response|array
     */
    public function fetchAuthor($id)
    {
        $response = $this->apiClient()
            ->get("/authors/{$id}");

        return $this->verifyResponse($response);
    }

    /**
     * Delete an author from the Candidate Testing API.
     *
     * @param  int  $id
    * @return \Illuminate\Http\Client\Response|array
     */
    public function deleteAuthor($id)
    {
        $response = $this->apiClient()
            ->delete("/authors/{$id}");

        return $this->verifyResponse($response);
    }


    /**
     * Fetch books of a specific author from the Candidate Testing API.
     *
     * @param  int  $authorId
     * @return \Illuminate\Http\Client\Response|array
     */
    public function fetchBooks($authorId)
    {
        $response = $this->apiClient()
            ->get("/authors/{$authorId}/books");

        return $this->verifyResponse($response);
    }

    /**
     * Add a book for a specific author using the Candidate Testing API.
     *
     * @param  string  $title
     * @param  int  $authorId
     * @return \Illuminate\Http\Client\Response|array
     */
    public function addBook($body)
    {
        $response = $this->apiClient()
            ->post("/books", $body);

        return $this->verifyResponse($response);
    }

    /**
     * Delete a book using the Candidate Testing API.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Client\Response|array
     */
    public function deleteBook($id)
    {
        $response = $this->apiClient()
            ->delete("/books/{$id}");

        return $this->verifyResponse($response);
    }


    /**
     * Verify API response and handle unauthorized responses.
     *
     * @param  \Illuminate\Http\Client\Response  $response
     * @return \Illuminate\Http\Client\Response|array
     */
    protected function verifyResponse($response)
    {
        if ($response->status() === 403) {
            return response()->json('Unauthorized', 403);
        }

        return $response;
    }

    /**
     * Create Author using the Candidate Testing API.
     *
     * @param  array  $body
    * @return \Illuminate\Http\Client\Response|array
     */
    public function createAuthor($body)
    {
        try {
            $username = 'ahsoka.tano@royal-apps.io';
            $password = 'Kryze4President';

            $loginResponse = $this->candidateLogin($username, $password);

            $token = $loginResponse->object()->token_key;

            $response = $this->apiClient($token)
                ->post('authors', $body);

            return $this->verifyResponse($response);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create author.'], 500);
        }
    }
}
