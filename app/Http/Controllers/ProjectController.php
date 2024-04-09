<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $projects = Project::with(['user','categories'])->get();

            return response()->json([
                'status' => 'success',
                'projects' => $projects,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'failed',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $project = Project::create([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'date' => $request->date,
                'brief' => $request->brief,
                'user_id' => (int)$request->user_id
            ]);

            $project->categories()->attach($request->category_ids);

            DB::commit();
            return response()->json([
                'statuse' => 'Store Success',
                'projects' => $project
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json([
                'statuse' => 'Store Failed',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return response()->json([
            'status' => 'success',
            'projects' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        try {
            DB::beginTransaction();
            $newData = [];

            if (isset($request->title)) {
                $newData['title'] = $request->title;
            }
            if (isset($request->subtitle)) {
                $newData['subtitle'] = $request->subtitle;
            }
            if (isset($request->date)) {
                $newData['date'] = $request->date;
            }
            if (isset($request->brief)) {
                $newData['brief'] = $request->brief;
            }
            if (isset($request->user_id)) {
                $newData['user_id'] = $request->user_id;
            }
            

            $project->update($newData);

            if (isset($request->category_ids)) {
                $project->categories()->sync($request->category_ids);
            }

            DB::commit();
            return response()->json([
                'statuse' => 'Update Success',
                'project' => $project,
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
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            $project->categories()->detach();
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
