<?php

namespace App\Http\Controllers;

use App\Models\Subtasks;
use App\Models\Tasks;
use Illuminate\Http\Request;

class SubtasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        return response()->json(Subtasks::get());
    }

    public function create(Request $request)
    {
        $status = $request->input('status', 'pending');

        $subtask = Subtasks::create([
            'title' => $request->input('title'),
            'task_id' => $request->input('task_id'),
            'description' => $request->input('description'),
            'status' => $status

        ]);

        return response()->json([
            'message' => 'subtask adicionada!',
            'subtasks' => $subtask

        ]);
    }

    public function show($id)
    {
        $subtask = Subtasks::findOrFail($id);
        return response()->json($subtask);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subtasks $subtask, $id)
    {
        $request->validate(
            [
                'title' => 'string',
                'description' => 'nullable|string',
                'task_id' => 'numeric',
                'status' => 'nullable|string| in:pending,completed',

            ],
        );



        $subtask = Subtasks::findOrFail($id);
        $subtask->update($request->all());

        return response()->json([
            'message' => 'subtask atualizada com sucesso',
            'subtarefa' => $subtask
        ],);
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,completed',
        ]);

        $subtask = Subtasks::findOrFail($id);
        $subtask->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status da subtarefa atualizado com sucesso',
            'subtask' => $subtask,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtasks $subtask, $id)
    {
        //
        $subtask = Subtasks::findOrFail($id);
        $subtask->delete();
        return response()->json(['message' => 'Subtask excluida com sucesso!'], 200);
    }
}
