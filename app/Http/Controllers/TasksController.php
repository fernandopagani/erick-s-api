<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\models\Subtasks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        return response()->json(Tasks::with("subtasks")->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'required|date_format:d/m/Y',

            'status' => 'nullable|string|in:pending,completed'
        ]);

        $due_date = Carbon::createFromFormat('d/m/Y', $request->input('due_date'));
        $status = $request->input('status', 'pending');

        $tasks = Tasks::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' =>  $due_date,
            'status' => $status

        ]);

        $tasks->due_date = $tasks->due_date->format('d/m/Y');

        return response()->json([
            'message' => 'task adicionada!',
            'tarefa' => $tasks

        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tasks = Tasks::findOrFail($id);

        return response()->json($tasks);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tasks $task, $id)
    {
        //
        $task = Tasks::findOrFail($id);


        $request->validate(
            [
                'title' => 'required|string',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
                'status' => 'nullable|string| in:pending,completed',

            ],

        );

        $task->fill($request->all());
        $task->save();




        return response()->json([
            'message' => 'task atualizada com sucesso',
            'tarefa' => $task
        ],);
    }


    public function patch(Request $request, $id)
    {
        $tasks = Tasks::findOrFail($id);

        $request->validate([
            'due_date' => 'required|date_format:d/m/Y'
        ]);
        $tasks->due_date = Carbon::createFromFormat('d/m/Y', $request->input('due_date'));
        $tasks->save();

        return response()->json([
            'message' => 'Due date atualizada com sucesso!',
            'tarefa' => $tasks
        ], 200);
    }


    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|string|in:pending,completed'
        ]);

        $task = Tasks::findOrFail($id);

        $task->status = $request->input('status');
        $task->save();

        if ($task->status === 'completed') {
            $task->subtasks()->update(['status' => 'completed']);
        }

        return response()->json([
            'message' => 'Status da tarefa e suas subtarefas atualizados com sucesso!',
            'task' => $task
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tasks $task, $id)
    {

        $task->where('id', $id)->delete();
        return response()->json(['message' => 'Task excluida com sucesso!'], 200);
    }

    public function filterToday(Tasks $task)
    {

        $tasks = Tasks::with("subtasks")->whereDate('due_date', Carbon::today())->get();

         Log::info('Query filterToday: ', $tasks->toArray());
        
        return response()->json($tasks);
    }

    public function filterOverdue(Tasks $task)
    {
       
        $tasks = Tasks::with("subtasks")->whereDate('due_date', '<', Carbon::today())->get();

        Log::info('Query filterOverdue: ', $tasks->toArray());
    

        return response()->json($tasks);
    }
}
