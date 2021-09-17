<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller {

    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function all(): array {
        return $this->user->newQuery()->get()->toArray();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {
        $users = $this->user->newQuery()->paginate($request->per_page ?? 5);
        $users = UserResource::collection($users)->resource;
        $this->setData(['users' => $users]);
        return $this->getApiResponse();
    }

    public function store(StoreUserRequest $request) {

    }

}
