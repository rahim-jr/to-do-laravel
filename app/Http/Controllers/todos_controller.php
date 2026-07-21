<?php

namespace App\Http\Controllers;

use App\Models\tags;
use App\Models\todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class todos_controller extends Controller
{
    /**
     * Display a listing of the user's todos.
     */
    public function index(Request $request): View|JsonResponse
    {
        $todos = $request->user()->todos()
            ->with(['tags', 'attachments'])
            ->latest()
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $todos,
            ]);
        }

        return view('todo.index', [
            'todos' => $todos,
        ]);
    }

    /**
     * Show the form for creating a new todo.
     */
    public function create(Request $request): View|JsonResponse
    {
        $allTags = tags::all();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'tags' => $allTags,
                ],
            ]);
        }

        return view('todo.create', [
            'tags' => $allTags,
        ]);
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        /** @var todo $todo */
        $todo = $request->user()->todos()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_completed' => $validated['is_completed'] ?? false,
        ]);

        if (! empty($validated['tags'])) {
            $todo->tags()->sync($validated['tags']);
        }

        $todo->load(['tags', 'attachments']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Todo created successfully.',
                'data' => $todo,
            ], 201);
        }

        return redirect()->route('todos.index')->with('status', 'Todo created successfully.');
    }

    /**
     * Display the specified todo.
     */
    public function show(Request $request, todo $todo): View|JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this todo.');
        }

        $todo->load(['tags', 'attachments']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $todo,
            ]);
        }

        return view('todo.show', [
            'todo' => $todo,
        ]);
    }

    /**
     * Show the form for editing the specified todo.
     */
    public function edit(Request $request, todo $todo): View|JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this todo.');
        }

        $todo->load(['tags', 'attachments']);
        $allTags = tags::all();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'todo' => $todo,
                    'tags' => $allTags,
                ],
            ]);
        }

        return view('todo.edit', [
            'todo' => $todo,
            'tags' => $allTags,
        ]);
    }

    /**
     * Update the specified todo in storage.
     */
    public function update(Request $request, todo $todo): RedirectResponse|JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this todo.');
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $todo->update([
            'title' => $validated['title'] ?? $todo->title,
            'description' => array_key_exists('description', $validated) ? $validated['description'] : $todo->description,
            'is_completed' => array_key_exists('is_completed', $validated) ? $validated['is_completed'] : $todo->is_completed,
        ]);

        if (array_key_exists('tags', $validated)) {
            $todo->tags()->sync($validated['tags'] ?? []);
        }

        $todo->load(['tags', 'attachments']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Todo updated successfully.',
                'data' => $todo,
            ]);
        }

        return redirect()->route('todos.index')->with('status', 'Todo updated successfully.');
    }

    /**
     * Remove the specified todo from storage.
     */
    public function destroy(Request $request, todo $todo): RedirectResponse|JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this todo.');
        }

        $todo->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Todo deleted successfully.',
            ]);
        }

        return redirect()->route('todos.index')->with('status', 'Todo deleted successfully.');
    }

    /**
     * Toggle the completion status of the specified todo.
     */
    public function toggleComplete(Request $request, todo $todo): RedirectResponse|JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this todo.');
        }

        $todo->update([
            'is_completed' => ! $todo->is_completed,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Todo completion status updated.',
                'data' => $todo,
            ]);
        }

        return redirect()->back()->with('status', 'Todo completion status updated.');
    }
}
