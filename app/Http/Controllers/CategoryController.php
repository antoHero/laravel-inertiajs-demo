<?php

namespace App\Http\Controllers;

use App\Http\Requests\{CreateCategoryRequest, UpdateCategoryRequest};
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Display all categories.
     * @return Inertia\Inertia;
     */
    public function index(): Response
    {
        $categories = Category::select(['name', 'slug'])->get();
        return Inertia::render('Category/Categories', [
            'categories' => $categories,
            'status' => session('status'),
        ]);
    }

    /**
     * Store cateegory
     * @param App\Http\Requests\CreateCategoryRequest @request
     * @return Illuminate\Support\Facades\Redirect;
    */

    public function store(CreateCategoryRequest $request)
    {
        return DB::transaction(function() use($request) {
            
            Category::create($request->validated());

            return Redirect::route('categories.index');
        });
    }

    /**
     * show category
     * @param App\Models\Category $category
     * @return Inertia\Inertia;
    */

    public function show(Category $category) {
        return Inertia::render('Category/Show', [
            'category' => $category
        ]);
    }

    /**
     * Update category
     * @param App\Http\Requests\UpdateCategoryRequest $request
     * @param App\Models\Category $category
     * @return Illuminate\Support\Facades\Redirect;
    */

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        return DB::transaction(function() use($request, $category) {
            $category->update($request->validated());

            return Redirect::route('categories.index');
        });
    }

    /** 
     * Delete category
     * @param App\Models\Category $category
     * @return Illuminate\Support\Facades\Redirect;
    */

    public function destroy(Category $category)
    {
        $category->delete();

        return Redirect::route('categories.index');
    }
}
