<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\TodoRequest;
use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use App\Interfaces\TodoServiceInterface;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function __construct(private readonly TodoServiceInterface $todoService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->todoService->getTodos();
        return ApiResponseClass::sendResponse(TodoResource::collection($data), 'Todos retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        DB::beginTransaction();
        try {
            $todo=$this->todoService->create($request->validated());
            DB::commit();
            return ApiResponseClass::sendResponse(TodoResource::make($todo), 'Todo created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponseClass::sendError($th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $data = $this->todoService->getById($id);
        if($data->isEmpty()){
            return ApiResponseClass::sendError('No data found.', 404);
        }else{
            return ApiResponseClass::sendResponse(TodoResource::make($data), 'Todo retrieved successfully.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, int $id)
    {
        DB::beginTransaction();
        try {
            $todo=$this->todoService->update($id, $request->validated());

            // return response unauthorized if $todo is false
            if(!$todo){
                return ApiResponseClass::sendError('Unauthorized', 401);
            }
            DB::commit();
            return ApiResponseClass::sendResponse(TodoResource::make($todo), 'Todo updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponseClass::sendError($th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $todo=$this->todoService->delete($id);
            // return response unauthorized if $todo is false
            if(!$todo){
                return ApiResponseClass::sendError('Unauthorized', 401);
            }
            DB::commit();
            return ApiResponseClass::sendResponse([], 'Todo deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponseClass::sendError($th->getMessage(), 500);
        }
    }

    /**
     * Marks a Todo item as completed.
     */
    public function complete(int $id){
        DB::beginTransaction();
        try {
            $todo=$this->todoService->complete($id);
            DB::commit();
            return ApiResponseClass::sendResponse(TodoResource::make($todo), 'Todo completed successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponseClass::sendError($th->getMessage(), 500);
        }
    }

    /**
     * Marks a Todo item as incomplete.
     */
    public function incomplete(int $id){
        DB::beginTransaction();
        try {
            $todo=$this->todoService->incomplete($id);
            DB::commit();
            return ApiResponseClass::sendResponse(TodoResource::make($todo), 'Todo completed successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponseClass::sendError($th->getMessage(), 500);
        }
    }

    /**
     * Searches for Todo items based on the provided search query.
     */
    public function searchTodo(Request $request){
        $data=$this->todoService->searchTodo($request->search);
        if($data->isEmpty()){
            return ApiResponseClass::sendResponse([], 'No data found.');
        }
        return ApiResponseClass::sendResponse(TodoResource::collection($data), 'Todos retrieved successfully.');
    }
}
