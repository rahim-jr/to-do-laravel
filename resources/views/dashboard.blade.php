<x-layouts::app :title="__('Dashboard')">
    @php
        $user = auth()->user();
        $todos = $user->todos()->with(['tags'])->latest()->get();
        $recentTodos = $todos->take(5);
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Welcome Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="xl" class="font-bold tracking-tight">Welcome back, {{ $user->name }}!</flux:heading>
                <flux:subheading>Here is an overview of your active tasks and productivity.</flux:subheading>
            </div>
            <div class="flex items-center gap-3">
                <flux:button variant="primary" icon="plus" href="{{ route('todos.create') }}">
                    New Task
                </flux:button>
                <flux:button variant="filled" icon="list-bullet" href="{{ route('todos.index') }}">
                    View All Tasks
                </flux:button>
            </div>
        </div>

        {{-- Recent To-Dos Card --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-zinc-100 pb-4 dark:border-zinc-800">
                <div>
                    <h2 class="text-base font-bold text-zinc-900 dark:text-white">Recent To-Dos</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Your latest updated tasks</p>
                </div>
                <flux:button variant="ghost" size="sm" icon-trailing="chevron-right" href="{{ route('todos.index') }}">
                    View All
                </flux:button>
            </div>

            @if ($recentTodos->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <flux:icon name="clipboard-document-list" class="size-10 text-zinc-300 dark:text-zinc-600" />
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">You don't have any tasks yet.</p>
                    <flux:button variant="primary" size="sm" icon="plus" class="mt-4" href="{{ route('todos.create') }}">
                        Create Task
                    </flux:button>
                </div>
            @else
                <div class="mt-4 flex flex-col divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($recentTodos as $todo)
                        <div class="flex items-center justify-between gap-3 py-3">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="flex size-5 items-center justify-center rounded-md border transition-colors {{ $todo->is_completed ? 'border-emerald-600 bg-emerald-600 text-white dark:border-emerald-500 dark:bg-emerald-500' : 'border-zinc-300 bg-white hover:border-indigo-500 dark:border-zinc-600 dark:bg-zinc-800' }}"
                                    >
                                        @if ($todo->is_completed)
                                            <flux:icon name="check" class="size-3.5 stroke-[3]" />
                                        @endif
                                    </button>
                                </form>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('todos.show', $todo) }}" class="text-sm font-semibold break-words text-zinc-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400 {{ $todo->is_completed ? 'line-through text-zinc-400 dark:text-zinc-500' : '' }}">
                                        {{ $todo->title }}
                                    </a>
                                    <div class="flex flex-wrap items-center gap-2 pt-0.5 text-xs text-zinc-400 dark:text-zinc-500">
                                        @if ($todo->is_completed)
                                            <flux:badge color="emerald" icon="check-circle" size="sm" class="shrink-0">Completed</flux:badge>
                                        @else
                                            <flux:badge color="amber" icon="clock" size="sm" class="shrink-0">Pending</flux:badge>
                                        @endif
                                        <span class="shrink-0">{{ $todo->created_at->diffForHumans() }}</span>
                                        @foreach ($todo->tags as $tag)
                                            <flux:badge size="sm" color="zinc" class="shrink-0">{{ $tag->name }}</flux:badge>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <flux:button variant="ghost" size="sm" icon="chevron-right" href="{{ route('todos.show', $todo) }}" class="shrink-0" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
