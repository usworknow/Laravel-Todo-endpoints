<?php

namespace App\Http\Controllers;

use App\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    protected $status_code = 200;
    
    public function createTodoItem(Request $request)
    {
        $todoData = array(
            'user_id'     => $request->user_id,
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => $request->status
        );

        $todo = TodoList::create($todoData);

        if (!is_null($todo)) {
            // Success
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Todo Item created successfully.", "todo" => $todo]);
        } else {
            // Error
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to create a new Todo."]);
        }

    }

    public function updateTodoItem(Request $request)
    {
        $status = TodoList::where('id', $request->id)->update($request->all());

        if ($status) {
            $todo = TodoList::where('id', $request->id)->get();
            // Success
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Todo Item updated successfully.", "todo" => $todo]);
        } else {
            // Error
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to update Todo Item."]);
        }
    }

    public function removeTodoItem($id)
    {
        $status = TodoList::where('id', $id)->update(['is_deleted' => 1]);

        if ($status) {
            // Success
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Todo Item removed successfully.", "data" => $status]);
        } else {
            // Error
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to remove Todo Item."]);
        }
    }

    public function getTodoItems()
    {
        $todos = TodoList::where('is_deleted', 0)->get();

        if (!is_null($todos)) {
            // Success
            return response()->json(["status" => $this->status_code, "success" => true, "todos" => $todos]);
        } else {
            // Error
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to get Todo items."]);
        }
    }

    public function getTodoItemsBySearch(Request $request)
    {
        $todos = TodoList::where('is_deleted', 0)
            ->where( 'title', 'like', '%' . $request->search_by . '%' )
            ->where( 'description', 'like', '%' . $request->search_by . '%' )->get();

        if (!is_null($todos)) {
            // Success
            return response()->json(["status" => $this->status_code, "success" => true, "todos" => $todos]);
        } else {
            // Error
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to get Todo items."]);
        }
    }

}
