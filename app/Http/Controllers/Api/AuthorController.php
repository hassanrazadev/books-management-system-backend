<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller {

    protected $author;

    /**
     * @param Author $author
     */
    public function __construct(Author $author) {
        $this->author = $author;
    }

    /**
     * @return array
     */
    public function all(): array {
        return $this->author->newQuery()->get()->toArray();
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection {
        $authors = $this->author->newQuery()->when($request->order, function ($q) use ($request) {
            $q->orderBy($request->column, $request->order);
        })->paginate($request->per_page ?? 5);
        return AuthorResource::collection($authors);
    }

    /**
     * @param StoreAuthorRequest $request
     * @return JsonResponse
     */
    public function store(StoreAuthorRequest $request): JsonResponse {
        $author = $this->author->create($request->validated());
        if ($author) {
            $this->setMessage('Author created!');
            $this->setData(['author' => $author]);
        } else {
            $this->setMessage('There is error while creating author, please try again!');
            $this->setStatus(false);
            $this->setStatusCode(400);
        }
        return $this->getApiResponse();
    }

    /**
     * @param StoreAuthorRequest $request
     * @param Author $author
     * @return JsonResponse
     */
    public function update(StoreAuthorRequest $request, Author $author): JsonResponse {
        $author->update($request->validated());
        $this->setMessage('Author updated!');
        $this->setData(['author' => new AuthorResource($author)]);
        return $this->getApiResponse();
    }

    /**
     * @param Author $author
     * @return JsonResponse
     */
    public function destroy(Author $author): JsonResponse {
        $author->delete();
        $this->setMessage('Author deleted!');
        return $this->getApiResponse();
    }

}
