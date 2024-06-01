<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use App\Traits\CandidateApiTrait;
use Carbon\Carbon;

class BookController extends Controller
{
    use CandidateApiTrait;


    public function store(BookCreateRequest $request)
    {
        $input = $request->all();
        $input['release_date'] = Carbon::parse($input['release_date'])->toIso8601String();

        $response = $this->addBook($input);

        if ($response->status() === 200 || $response->status() === 204) {
            return $this->sendSuccess('Book created successfully');
        }

        return $this->sendError('Something went wrong while creating the book');
    }

    public function destroy($id)
    {
        $response = $this->deleteBook($id);

        if ($response->status() === 200 || $response->status() === 204) {
            return $this->sendSuccess('Book has been deleted successfully');
        }

        return $this->sendError('Something went wrong while deleting the book');
    }
}
