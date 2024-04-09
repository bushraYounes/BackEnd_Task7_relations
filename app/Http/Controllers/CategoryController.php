<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::with('projects')->get();
            return response()->json([
                'status' => 'success',
                'categories' => $categories,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'failed',
                'error'=>$th
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $category = Category::create([
                'name' => $request->name
            ]);
            $category->projects()->attach($request->project_ids);

            DB::commit();
            return response()->json([
                'statuse' => 'Store Success',
                'categories' => $category
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json([
                'statuse' => 'Store Failed',
                'error'=>$th
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'status' => 'success',
            'Category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        try {
            DB::beginTransaction();
            $newData = [];

            if (isset($request->name)) {
                $newData['name'] = $request->name;
            }
           
            

            $category->update($newData);

            if (isset($request->project_ids)) {
                $category->projects()->sync($request->project_ids);
            }

            DB::commit();
            return response()->json([
                'statuse' => 'Update Success',
                'project' => $category,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json([
                'statuse' => 'Update Failed',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            $category->projects()->detach();
            return response()->json([
                'status' => 'Delete Success',
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'Delete Failed',

            ]);
        }
    }
}
