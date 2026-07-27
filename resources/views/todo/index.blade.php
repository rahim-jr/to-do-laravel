<x-layouts::app :title="__('To-Do List')">
    <div class="flex h-full w-full flex-1 flex-col gap-6" x-data="{ filter: 'all' }">
        {{-- Flash Status Message --}}
        @if (session('status'))
            <div class="flex items-center justify-between rounded-lg border border-emerald-500/20 bg-emerald-50/80 p-4 text-emerald-900 shadow-xs dark:border-emerald-500/30 dark:bg-emerald-950/40 dark:text-emerald-200">
                <div class="flex items-center gap-3 min-w-0">
                    <flux:icon name="check-circle" class="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
                    <span class="text-sm font-medium break-words">{{ session('status') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="shrink-0 text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-200">
                    <flux:icon name="x-mark" class="size-4" />
                </button>
            </div>
        @endif

        @php
            $totalCount = $todos->count();
            $completedCount = $todos->where('is_completed', true)->count();
            $pendingCount = $todos->where('is_completed', false)->count();
        @endphp

        {{-- Header Section --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <flux:heading size="xl" class="font-bold tracking-tight">To-Do Manager</flux:heading>
                <flux:subheading>Organize your tasks, stay focused, and achieve your goals.</flux:subheading>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center w-full sm:w-auto">
                <div class="flex items-center gap-1 rounded-lg border border-zinc-200 bg-zinc-50 p-1 dark:border-zinc-700 dark:bg-zinc-900/50 w-full sm:w-auto">
                    <button
                        type="button"
                        @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-white text-zinc-900 shadow-xs dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white'"
                        class="flex-1 sm:flex-none justify-center rounded-md px-3 py-1.5 text-xs font-semibold transition-colors"
                    >
                        All ({{ $totalCount }})
                    </button>
                    <button
                        type="button"
                        @click="filter = 'pending'"
                        :class="filter === 'pending' ? 'bg-white text-zinc-900 shadow-xs dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white'"
                        class="flex-1 sm:flex-none justify-center rounded-md px-3 py-1.5 text-xs font-semibold transition-colors"
                    >
                        Pending ({{ $pendingCount }})
                    </button>
                    <button
                        type="button"
                        @click="filter = 'completed'"
                        :class="filter === 'completed' ? 'bg-white text-zinc-900 shadow-xs dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white'"
                        class="flex-1 sm:flex-none justify-center rounded-md px-3 py-1.5 text-xs font-semibold transition-colors"
                    >
                        Completed ({{ $completedCount }})
                    </button>
                </div>

                {{-- Sort Controls --}}
                <div class="flex items-center gap-1 rounded-lg border border-zinc-200 bg-zinc-50 p-1 dark:border-zinc-700 dark:bg-zinc-900/50 w-full sm:w-auto">
                    <a
                        href="{{ route('todos.index', ['sort' => 'desc']) }}"
                        class="flex flex-1 sm:flex-none items-center justify-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-semibold transition-colors {{ ($currentSort ?? 'latest') === 'latest' ? 'bg-white text-zinc-900 shadow-xs dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' }}"
                        title="Sort by newest created date first"
                    >
                        <flux:icon name="bars-arrow-down" class="size-3.5" />
                        Newest
                    </a>
                    <a
                        href="{{ route('todos.index', ['sort' => 'asc']) }}"
                        class="flex flex-1 sm:flex-none items-center justify-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-semibold transition-colors {{ ($currentSort ?? 'latest') === 'oldest' ? 'bg-white text-zinc-900 shadow-xs dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' }}"
                        title="Sort by oldest created date first"
                    >
                        <flux:icon name="bars-arrow-up" class="size-3.5" />
                        Oldest
                    </a>
                </div>

                <flux:button variant="primary" icon="plus" href="{{ route('todos.create') }}" class="w-full sm:w-auto justify-center">
                    New Task
                </flux:button>
            </div>
        </div>

        {{-- To-Do List Items --}}
        @if ($todos->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 p-6 sm:p-12 text-center dark:border-zinc-700">
                <div class="flex size-14 sm:size-16 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
                    <flux:icon name="clipboard-document-list" class="size-7 sm:size-8" />
                </div>
                <h3 class="mt-4 text-base font-semibold text-zinc-900 dark:text-white">No tasks created yet</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 max-w-sm">Get started by creating your very first to-do item.</p>
                <div class="mt-6 w-full sm:w-auto">
                    <flux:button variant="primary" icon="plus" href="{{ route('todos.create') }}" class="w-full sm:w-auto justify-center">
                        Create Task
                    </flux:button>
                </div>
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach ($todos as $todo)
                    <div
                        x-data="{
                            completed: {{ $todo->is_completed ? 'true' : 'false' }}
                        }"
                        x-show="filter === 'all' || (filter === 'pending' && !completed) || (filter === 'completed' && completed)"
                        x-transition
                        class="group relative flex flex-col gap-3.5 rounded-xl border border-zinc-200 bg-white p-3.5 sm:p-4 shadow-xs transition-all hover:border-zinc-300 hover:shadow-md dark:border-zinc-700/80 dark:bg-zinc-900 dark:hover:border-zinc-600 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="flex items-start gap-3 min-w-0 flex-1">
                            {{-- Checkbox Toggle Form --}}
                            <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="mt-0.5 shrink-0">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="flex size-6 items-center justify-center rounded-lg border transition-colors {{ $todo->is_completed ? 'border-emerald-600 bg-emerald-600 text-white dark:border-emerald-500 dark:bg-emerald-500' : 'border-zinc-300 bg-white hover:border-indigo-500 dark:border-zinc-600 dark:bg-zinc-800' }}"
                                    title="{{ $todo->is_completed ? 'Mark as incomplete' : 'Mark as complete' }}"
                                >
                                    @if ($todo->is_completed)
                                        <flux:icon name="check" class="size-4 stroke-[3]" />
                                    @endif
                                </button>
                            </form>

                            <div class="flex flex-col gap-1 min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a
                                        href="{{ route('todos.show', $todo) }}"
                                        class="text-base font-semibold break-words min-w-0 transition-colors hover:text-indigo-600 dark:hover:text-indigo-400 {{ $todo->is_completed ? 'text-zinc-400 line-through dark:text-zinc-500' : 'text-zinc-900 dark:text-white' }}"
                                    >
                                        {{ $todo->title }}
                                    </a>

                                    @if ($todo->is_completed)
                                        <flux:badge color="emerald" icon="check-circle" size="sm" class="shrink-0">Completed</flux:badge>
                                    @else
                                        <flux:badge color="amber" icon="clock" size="sm" class="shrink-0">Pending</flux:badge>
                                    @endif

                                    @foreach ($todo->tags as $tag)
                                        <flux:badge size="sm" color="zinc" class="shrink-0">{{ $tag->name }}</flux:badge>
                                    @endforeach
                                </div>

                                @if ($todo->description)
                                    <p class="line-clamp-2 text-xs break-words text-zinc-500 dark:text-zinc-400">
                                        {{ $todo->description }}
                                    </p>
                                @endif

                                <div class="flex flex-wrap items-center gap-3 pt-0.5 text-[11px] text-zinc-400 dark:text-zinc-500">
                                    <span class="flex items-center gap-1 shrink-0">
                                        <flux:icon name="calendar" class="size-3" />
                                        {{ $todo->created_at->diffForHumans() }}
                                    </span>

                                    @if ($todo->due_at)
                                        <span class="flex items-center gap-1 shrink-0 {{ $todo->due_at->isPast() && ! $todo->is_completed ? 'font-medium text-red-600 dark:text-red-400' : 'text-zinc-500 dark:text-zinc-400' }}" title="Due date: {{ $todo->due_at->format('M j, Y g:i A') }}">
                                            <flux:icon name="clock" class="size-3" />
                                            Due {{ $todo->due_at->diffForHumans() }}
                                        </span>
                                    @endif

                                    @if ($todo->attachments->isNotEmpty())
                                        <span class="flex items-center gap-1 font-medium text-indigo-500 shrink-0">
                                            <flux:icon name="paper-clip" class="size-3" />
                                            {{ $todo->attachments->count() }} {{ Str::plural('attachment', $todo->attachments->count()) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-1 sm:gap-2 border-t border-zinc-100 pt-2.5 dark:border-zinc-800 sm:border-t-0 sm:pt-0 shrink-0">
                            <flux:button variant="ghost" size="sm" icon="eye" href="{{ route('todos.show', $todo) }}" title="View details" />
                            <flux:button variant="ghost" size="sm" icon="pencil-square" href="{{ route('todos.edit', $todo) }}" title="Edit task" />

                            <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="ghost" size="sm" icon="trash" class="text-red-600 hover:text-red-700 dark:text-red-400" title="Delete task" />
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>
