<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller {

    /**
     * @var Book
     */
    private $book;

    /**
     * @param Book $book
     */
    public function __construct(Book $book) {
        $this->book = $book;
    }

    /**
     * @return array
     */
    public function all(): array {
        return $this->book->newQuery()->get()->toArray();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {
        $books = $this->book->newQuery()->paginate($request->per_page ?? 5);
        $books = BookResource::collection($books)->resource;
        $this->setData(['books' => $books]);
        return $this->getApiResponse();
    }

    /**
     * @param StoreBookRequest $request
     * @return JsonResponse
     */
    public function store(StoreBookRequest $request): JsonResponse {
        $book = $this->book->create($request->validated());
        if ($book) {
            $this->setMessage('Book created!');
            $this->setData(['book' => $book]);
        } else {
            $this->setMessage('There is error while creating book, please try again!');
            $this->setStatus(false);
            $this->setStatusCode(400);
        }
        return $this->getApiResponse();
    }

    /**
     * @param StoreBookRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function update(StoreBookRequest $request, Book $book): JsonResponse {
        $book->update($request->validated());
        $this->setMessage('Book updated!');
        $this->setData(['author' => new BookResource($book)]);
        return $this->getApiResponse();
    }

    /**
     * @param Book $book
     * @return JsonResponse
     */
    public function destroy(Book $book): JsonResponse {
        $book->delete();
        $this->setMessage('Book deleted!');
        return $this->getApiResponse();
    }
}
