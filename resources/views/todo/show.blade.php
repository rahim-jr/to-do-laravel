<x-layouts::app :title="$todo->title">
    <div class="mx-auto max-w-3xl space-y-6">
        {{-- Navigation Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <flux:button variant="ghost" size="sm" icon="arrow-left" href="{{ route('todos.index') }}">
                Back to list
            </flux:button>

            <div class="flex items-center gap-2 self-end sm:self-auto">
                <flux:button variant="filled" size="sm" icon="pencil-square" href="{{ route('todos.edit', $todo) }}">
                    Edit
                </flux:button>
                <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                    @csrf
                    @method('DELETE')
                    <flux:button type="submit" variant="ghost" size="sm" icon="trash" class="text-red-600 hover:text-red-700 dark:text-red-400">
                        Delete
                    </flux:button>
                </form>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 sm:p-6 md:p-8 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 border-b border-zinc-100 pb-6 dark:border-zinc-800 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1 min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-3">
                        @if ($todo->is_completed)
                            <flux:badge color="emerald" icon="check-circle">Completed</flux:badge>
                        @else
                            <flux:badge color="amber" icon="clock">Pending</flux:badge>
                        @endif
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">Task #{{ $todo->id }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold break-words text-zinc-900 dark:text-white {{ $todo->is_completed ? 'line-through text-zinc-400 dark:text-zinc-500' : '' }}">
                        {{ $todo->title }}
                    </h1>
                </div>

                {{-- Status Toggle Form --}}
                <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="w-full sm:w-auto shrink-0">
                    @csrf
                    @method('PATCH')
                    @if ($todo->is_completed)
                        <flux:button type="submit" variant="filled" icon="arrow-path" class="w-full sm:w-auto justify-center">
                            Mark as Pending
                        </flux:button>
                    @else
                        <flux:button type="submit" variant="primary" icon="check" class="w-full sm:w-auto justify-center">
                            Mark as Complete
                        </flux:button>
                    @endif
                </form>
            </div>

            {{-- Description Section --}}
            <div class="py-6">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Description</h3>
                @if ($todo->description)
                    <div class="mt-3 whitespace-pre-line text-sm leading-relaxed break-words text-zinc-700 dark:text-zinc-300">
                        {{ $todo->description }}
                    </div>
                @else
                    <p class="mt-3 italic text-xs text-zinc-400 dark:text-zinc-500">No description provided for this task.</p>
                @endif
            </div>

            {{-- Due Date Section --}}
            @if ($todo->due_at)
                <div class="border-t border-zinc-100 py-5 dark:border-zinc-800">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Due Date</h3>
                    <p class="mt-2 text-sm font-medium flex items-center gap-2 {{ $todo->due_at->isPast() && ! $todo->is_completed ? 'text-red-600 dark:text-red-400' : 'text-zinc-800 dark:text-zinc-200' }}">
                        <flux:icon name="calendar" class="size-4" />
                        {{ $todo->due_at->format('F j, Y \a\t g:i A') }} ({{ $todo->due_at->diffForHumans() }})
                    </p>
                </div>
            @endif

            {{-- Tags Section --}}
            @if ($todo->tags->isNotEmpty())
                <div class="border-t border-zinc-100 py-5 dark:border-zinc-800">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Tags</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($todo->tags as $tag)
                            <flux:badge size="md" color="indigo">{{ $tag->name }}</flux:badge>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Attachments Section --}}
            @if ($todo->attachments->isNotEmpty())
                <div class="border-t border-zinc-100 py-5 dark:border-zinc-800">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Attachments</h3>
                    <ul class="mt-3 divide-y divide-zinc-100 rounded-lg border border-zinc-200 dark:divide-zinc-800 dark:border-zinc-700">
                        @foreach ($todo->attachments as $attachment)
                            <li class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 text-sm">
                                <div class="flex items-center gap-3 min-w-0">
                                    <flux:icon name="paper-clip" class="size-4 text-zinc-400 shrink-0" />
                                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate break-all min-w-0">{{ $attachment->original_name }}</span>
                                </div>
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="shrink-0 text-xs font-semibold text-indigo-600 hover:underline dark:text-indigo-400">
                                    View / Download
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Timestamps Metadata --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-t border-zinc-100 pt-5 text-xs text-zinc-400 dark:border-zinc-800 dark:text-zinc-500">
                <div>Created: {{ $todo->created_at->format('M j, Y \a\t g:i A') }} ({{ $todo->created_at->diffForHumans() }})</div>
                <div>Last Updated: {{ $todo->updated_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
</x-layouts::app>
