<?php

namespace App\Services;

use App\Interfaces\TodoServiceInterface;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class TodoService implements TodoServiceInterface
{
    public function __construct(private readonly Task $task)
    {
    }

    public function getTodos()
    {
        return $this->task->where('user_id', auth()->user()->id)->get();
    }
    public function getById(int $id)
    {
        $task=$this->task->find($id);
        // check if user can view the task
        if(!Gate::allows('view', $task)){
            return null;
        }
        return $task;
    }

    public function create(array $data)
    {
        return $this->task->create(array_merge($data, ['user_id' => auth()->user()->id]));
    }

    public function update(int $id, array $data)
    {
        $update=$this->task->find($id);
        // check if user can update the task
        if(!Gate::allows('update', $update)){
            return false;
        }
        $update->update($data);
        return $update;
    }

    public function delete(int $id)
    {
        $delete=$this->task->find($id);
        // check if user can delete the task
        if(!Gate::allows('delete', $delete)){
            return false;
        }
        return $delete->delete();
    }

    public function complete(int $id)
    {
        $update=$this->task->where([
            'id'=> $id,
            'user_id' => auth()->user()->id
        ])->first();
        $update->update([
            'completed' => true,
            'completed_at' => now()
        ]);
        return $update;
    }

    public function incomplete(int $id)
    {
        $update=$this->task->where([
            'id'=> $id,
            'user_id' => auth()->user()->id
        ])->first();
        $update->update([
            'completed' => false,
            'completed_at' => null
        ]);
        return $update;
    }

    public function searchTodo(string $search)
    {
        return $this->task->where('user_id', auth()->user()->id)->where('title', 'like', '%' . $search . '%')->get();
    }
}
