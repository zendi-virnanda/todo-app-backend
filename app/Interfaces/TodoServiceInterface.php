<?php

namespace App\Interfaces;

interface TodoServiceInterface
{
    public function getTodos();
    public function getById(int $id);
    public function create(array $data);

    public function update(int $id ,array $data);

    public function delete(int $id);
    public function searchTodo(string $search);
    public function complete(int $id);
    public function incomplete(int $id);
}
