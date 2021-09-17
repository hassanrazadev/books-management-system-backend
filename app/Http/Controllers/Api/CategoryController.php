<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller {

    protected $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category) {
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function all(): array {
        return $this->category->newQuery()->get()->toArray();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {
        $categories = $this->category->newQuery()->paginate($request->per_page ?? 5);
        $categories = CategoryResource::collection($categories)->resource;
        $this->setData(['categories' => $categories]);
        return $this->getApiResponse();
    }

    /**
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse {
        $fields = $request->validated();
        $fields['user_id'] = auth()->id();
        $category = $this->category->create($fields);
        if ($category) {
            $this->setMessage('Category created!');
            $this->setData([
                'category' => new CategoryResource($category)
            ]);
        } else {
            $this->setMessage('There is error while creating category, please try again!');
            $this->setStatus(false);
            $this->setStatusCode(400);
        }
        return $this->getApiResponse();
    }

    /**
     * @param StoreCategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(StoreCategoryRequest $request, Category $category): JsonResponse {
        $category->update($request->validated());
        $this->setMessage('Category updated!');
        $this->setData([
            'category' => new CategoryResource($category)
        ]);
        return $this->getApiResponse();
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse {
        $category->delete();
        $this->setMessage('Category deleted!');
        return $this->getApiResponse();
    }
}
