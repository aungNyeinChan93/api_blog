<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoryShowResource;
use App\Http\Resources\CategoryStoreResource;
use App\Http\Resources\CategoryUpdateResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //index
    public function index(Request $request)
    {
        $categories = Category::query()->latest()->paginate(10)->withQueryString();

        return (CategoriesResource::collection($categories))
            ->additional(['message' => 'success'])
            ->response()->setStatusCode(200);
    }

    // store
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($fields);

        return (new CategoryStoreResource($category))
            ->additional(['message' => 'success'])
            ->response()
            ->setStatusCode(201);
    }

    // show
    public function show(Category $category)
    {
        return (new CategoryShowResource($category))
            ->additional(['message' => 'success'])
            ->response()->setStatusCode(200);
    }

    // update
    public function update(Request $request, Category $category)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $category->update($fields);

        return (new CategoryUpdateResource($category))
            ->additional(['message' => 'success'])
            ->response()
            ->setStatusCode(201);
    }

    // destroy
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
