<?php

namespace App\Http\Controllers;

use App\Traits\CandidateApiTrait;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    use CandidateApiTrait;

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        return $this->fetchAuthors([
            'limit' => 10,
            'page'  => $page,
        ]);
    }

    public function show($id)
    {
        return $this->fetchAuthor($id);
    }

    public function destroy(Request $request)
    {
        $response = $this->fetchAuthor($request->id);
        if ($response->status() !== 200) {
            return $this->sendError('Something went wrong while retrieving the authors.');
        }

        $author = $response->object();
        $books = $author->books;
        if (!empty($books)) {
            return $this->sendError('Unable to delete author because they have associated books.');
        }

        $response = $this->deleteAuthor($request->id);
        if ($response->status() === 200 || $response->status() === 204) {
            return $this->sendSuccess('Author has been deleted successfully');
        }

        return $this->sendError('Something went wrong while deleting the author');
    }
}
