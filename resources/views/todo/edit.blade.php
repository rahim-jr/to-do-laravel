<x-layouts::app :title="__('Edit To-Do')">
    <div class="mx-auto max-w-3xl space-y-6">
        {{-- Navigation Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <flux:button variant="ghost" size="sm" icon="arrow-left" href="{{ route('todos.index') }}">
                    Back to list
                </flux:button>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 sm:p-6 md:p-8 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="border-b border-zinc-100 pb-5 dark:border-zinc-800">
                <flux:heading size="xl" class="font-bold tracking-tight">Edit Task</flux:heading>
                <flux:subheading>Update details, tags, or status for this to-do item.</flux:subheading>
            </div>

            <form action="{{ route('todos.update', $todo) }}" method="POST" class="mt-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Task Title --}}
                <flux:field>
                    <flux:label>Title <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        name="title"
                        type="text"
                        required
                        value="{{ old('title', $todo->title) }}"
                        placeholder="Task title..."
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
                    >{{ old('description', $todo->description) }}</flux:textarea>
                    <flux:error name="description" />
                </flux:field>

                {{-- Tag Selection --}}
                @if ($tags->isNotEmpty())
                    @php
                        $selectedTagIds = old('tags', $todo->tags->pluck('id')->toArray());
                    @endphp
                    <flux:field>
                        <flux:label>Tags</flux:label>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <label class="inline-flex cursor-pointer select-none items-center gap-2 rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-xs font-medium text-zinc-700 transition-colors hover:border-indigo-400 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    <input
                                        type="checkbox"
                                        name="tags[]"
                                        value="{{ $tag->id }}"
                                        {{ in_array($tag->id, $selectedTagIds) ? 'checked' : '' }}
                                        class="size-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900"
                                    />
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <flux:error name="tags" />
                    </flux:field>
                @endif

                {{-- Completion Status --}}
                <div class="pt-2">
                    <label class="inline-flex cursor-pointer select-none items-start sm:items-center gap-3">
                        <input
                            type="checkbox"
                            name="is_completed"
                            value="1"
                            {{ old('is_completed', $todo->is_completed) ? 'checked' : '' }}
                            class="mt-0.5 sm:mt-0 size-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900"
                        />
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Mark task as completed</span>
                    </label>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:button href="{{ route('todos.index') }}" variant="filled" class="w-full sm:w-auto justify-center">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="check" class="w-full sm:w-auto justify-center">
                        Save Changes
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
