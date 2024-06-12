<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth; 

class TodoController extends Controller
{
    // public function index()
    // {
    //     $token = env('BEARER_TOKEN');
    //     $headers = [
    //         'Authorization' => 'Bearer ' . $token,
    //     ];
        
    //     $response = Http::withHeaders($headers)->get('http://localhost:8000/api/todos');
        
    //     // Process the response
    //     return $response->json();
    // }

    public function index()
{
    $todos = Todo::all();

    $todos->transform(function ($todo) {
        $todo->completed = $todo->completed ? 'yes' : 'no';
        return $todo;
    });

    return response()->json(['todos' => $todos]);
}
    
public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string',
        'description' => 'sometimes|string',
    ]);

    // The validation passed, no need to check $request->fails()

    $todo = Todo::create([
        'title' => $validatedData['title'],
        'description' => $validatedData['description'],
        'completed' => false,
        'user_id' => Auth::id(),
    ]);

    return response()->json($todo, 201);
}

    
    public function show(Todo $todo)
    {
        $token = env('BEARER_TOKEN');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
        ];
    
        $response = Http::withHeaders($headers)->get("http://localhost:8000/api/todos/{$todo->id}");
        
        // Process the response
        return $response->json();
    }
    
    // public function update(Request $request, Todo $todo)
    // {
    //     $token = env('BEARER_TOKEN');
    //     $headers = [
    //         'Authorization' => 'Bearer ' . $token,
    //     ];
    
    //     $response = Http::withHeaders($headers)->put("http://localhost:8000/api/todos/{$todo->id}", $request->all());
        
    //     // Process the response
    //     return $response->json();
    // }

    public function update(Request $request, Todo $todo )
    {
        $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'completed' => 'required|boolean',
        ]);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'completed' => $request->completed,
        ]);

        return response()->json([$todo]);
    }
    
    public function destroy($id)
    {
        $todo = Todo::find($id);

        if(!$todo){
            return response()->json(['message' => 'wala'], 404 );
        }

        $todo->delete();

        return response()->json(['message'=> 'Burado ka na!'], 200);
      
    }   
}
