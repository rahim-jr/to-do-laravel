<x-layouts::app :title="__('Create To-Do')">
    <div class="mx-auto max-w-3xl space-y-6">
        {{-- Navigation & Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <flux:button variant="ghost" size="sm" icon="arrow-left" href="{{ route('todos.index') }}">
                    Back to list
                </flux:button>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900 sm:p-8">
            <div class="border-b border-zinc-100 pb-5 dark:border-zinc-800">
                <flux:heading size="xl" class="font-bold tracking-tight">Create New Task</flux:heading>
                <flux:subheading>Add a new to-do item to your personal task list.</flux:subheading>
            </div>

            <form action="{{ route('todos.store') }}" method="POST" class="mt-6 space-y-6">
                @csrf

                {{-- Task Title --}}
                <flux:field>
                    <flux:label>Title <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        name="title"
                        type="text"
                        required
                        autofocus
                        value="{{ old('title') }}"
                        placeholder="e.g., Complete quarterly budget review"
                    />
                    <flux:error name="title" />
                </flux:field>

                {{-- Task Description --}}
                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea
                        name="description"
                        rows="4"
                        placeholder="Provide extra details, steps, or references for this task..."
                    >{{ old('description') }}</flux:textarea>
                    <flux:error name="description" />
                </flux:field>

                {{-- Tag Selection --}}
                @if ($tags->isNotEmpty())
                    <flux:field>
                        <flux:label>Tags</flux:label>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-1.5 text-xs font-medium text-zinc-700 transition-colors hover:border-indigo-400 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    <input
                                        type="checkbox"
                                        name="tags[]"
                                        value="{{ $tag->id }}"
                                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                        class="size-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900"
                                    />
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <flux:error name="tags" />
                    </flux:field>
                @endif

                {{-- Initial Status --}}
                <div class="pt-2">
                    <label class="inline-flex cursor-pointer items-center gap-3">
                        <input
                            type="checkbox"
                            name="is_completed"
                            value="1"
                            {{ old('is_completed') ? 'checked' : '' }}
                            class="size-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900"
                        />
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Mark as completed upon creation</span>
                    </label>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:button href="{{ route('todos.index') }}" variant="filled">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="plus">
                        Create Task
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
