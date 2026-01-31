<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main content -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h3>
                                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                        <span>Priority: <span class="font-semibold">{{ ucfirst($task->priority) }}</span></span>
                                        <span>Status: <span class="font-semibold">{{ ucfirst($task->status) }}</span></span>
                                        @if($task->due_date)
                                            <span>Due: <span class="font-semibold">{{ $task->due_date->format('M d, Y') }}</span></span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('manager.tasks.index') }}" class="text-sm text-blue-600 hover:underline">Back to tasks</a>
                            </div>

                            <div class="mt-6 prose max-w-none">
                                {!! nl2br(e($task->description)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center">
                                <h5 class="text-lg font-medium text-gray-900">
                                    Comments 
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">{{ $task->comments->count() }}</span>
                                </h5>
                                <small class="text-sm text-gray-500">Most recent first</small>
                            </div>

                            <div id="comments-container" class="mt-4 space-y-6" style="max-height: 500px; overflow-y: auto;">
                                @forelse ($task->comments()->latest()->get() as $comment)
                                    <div class="flex space-x-3" id="comment-{{ $comment->id }}">
                                        <div class="flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}" alt="">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <span class="font-semibold text-gray-900">{{ $comment->user->name ?? 'Unknown User' }}</span>
                                                    <span class="ml-2 text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                    @if ($comment->created_at->gt(now()->subHour()))
                                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">New</span>
                                                    @endif
                                                </div>
                                                @canany(['update-comment', 'delete-comment'], $comment)
                                                    <div class="flex space-x-2">
                                                        <button onclick="startEditComment({{ $comment->id }})" class="text-sm font-medium text-blue-600 hover:text-blue-800">Edit</button>
                                                        <button onclick="deleteComment({{ $comment->id }})" class="text-sm font-medium text-red-600 hover:text-red-800">Delete</button>
                                                    </div>
                                                @endcanany
                                            </div>
                                            <div class="mt-1 text-gray-700 comment-content" data-original="{{ e($comment->comment) }}">
                                                {!! nl2br(e($comment->comment)) !!}
                                            </div>
                                            <!-- Edit form (hidden by default) -->
                                            <form class="edit-comment-form mt-2 hidden" id="edit-form-{{ $comment->id }}">
                                                @csrf
                                                @method('PUT')
                                                <textarea class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" rows="3" name="comment"></textarea>
                                                <div class="mt-2 flex items-center space-x-2">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Save</button>
                                                    <button type="button" onclick="cancelEdit({{ $comment->id }})" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12 text-gray-500">
                                        <p>No comments yet. Start the conversation!</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Add new comment -->
                            <div class="mt-6">
                                <form id="new-comment-form">
                                    @csrf
                                    <div>
                                        <label for="new-comment-text" class="sr-only">Add a comment</label>
                                        <textarea id="new-comment-text" name="comment" rows="3" class="shadow-sm block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" placeholder="Write your comment here..." required></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Post Comment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right sidebar -->
                <div class="space-y-6">
                    <!-- Attachments -->
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h5 class="text-lg font-medium text-gray-900">Attachments</h5>
                            <div class="mt-4">
                                <form action="{{ route('tasks.files.store', $task) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex items-center">
                                        <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                                        <button type="submit" class="ml-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Upload</button>
                                    </div>
                                </form>
                            </div>

                            @if ($task->files->isNotEmpty())
                                <ul role="list" class="mt-4 divide-y divide-gray-200">
                                    @foreach ($task->files as $file)
                                        <li class="py-3 flex justify-between items-center">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                   <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        <a href="{{ $file->url }}" target="_blank" class="hover:underline">{{ $file->original_name ?? $file->file_name }}</a>
                                                    </p>
                                                    <p class="text-sm text-gray-500">{{ $file->size_human ?? '—' }} • {{ $file->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            @canany(['delete-file'], $file)
                                                <form action="{{ route('tasks.files.destroy', $file) }}" method="POST" onsubmit="return confirm('Delete this file permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                    </button>
                                                </form>
                                            @endcanany
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mt-4 text-center text-sm text-gray-500">No files attached yet</p>
                            @endif
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h5 class="text-lg font-medium text-gray-900">Activity Log</h5>
                            @if ($task->activities->isEmpty())
                                <p class="mt-4 text-center text-sm text-gray-500">No activity recorded yet</p>
                            @else
                                <ul role="list" class="mt-4 space-y-4">
                                    @foreach ($task->activities->sortByDesc('created_at') as $activity)
                                        <li class="flex space-x-3">
                                            <div class="flex-1 space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="text-sm font-medium">{{ $activity->causer?->name ?? 'System' }}</h3>
                                                    <p class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                                </div>
                                                <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // Add new comment (AJAX)
    document.getElementById('new-comment-form')?.addEventListener('submit', async e => {
        e.preventDefault();
        const form = e.target;
        const textarea = form.querySelector('textarea');
        const text = textarea.value.trim();

        if (!text) return;

        try {
            const response = await fetch("{{ route('tasks.comments.store', $task) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ comment: text })
            });

            if (!response.ok) throw new Error(await response.text());

            const data = await response.json();

            const commentHtml = `
                <div class="flex space-x-3" id="comment-${data.id}">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.user_name)}" alt="">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-semibold text-gray-900">${data.user_name}</span>
                                <span class="ml-2 text-sm text-gray-500">just now</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">New</span>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="startEditComment(${data.id})" class="text-sm font-medium text-blue-600 hover:text-blue-800">Edit</button>
                                <button onclick="deleteComment(${data.id})" class="text-sm font-medium text-red-600 hover:text-red-800">Delete</button>
                            </div>
                        </div>
                        <div class="mt-1 text-gray-700 comment-content" data-original="${data.comment}">${data.comment.replace(/\n/g, '<br>')}</div>
                        <form class="edit-comment-form mt-2 hidden" id="edit-form-${data.id}">
                            @csrf
                            @method('PUT')
                            <textarea class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" rows="3" name="comment"></textarea>
                            <div class="mt-2 flex items-center space-x-2">
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Save</button>
                                <button type="button" onclick="cancelEdit(${data.id})" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>`;

            document.getElementById('comments-container').insertAdjacentHTML('afterbegin', commentHtml);
            textarea.value = '';
        } catch (err) {
            alert('Failed to post comment: ' + err.message);
        }
    });

    // Edit comment
    window.startEditComment = function(id) {
        const commentDiv = document.getElementById(`comment-${id}`);
        const contentDiv = commentDiv.querySelector('.comment-content');
        const original = contentDiv.dataset.original;

        const form = commentDiv.querySelector('.edit-comment-form');
        form.classList.remove('hidden');
        form.querySelector('textarea').value = original;
        contentDiv.classList.add('hidden');
    };

    window.cancelEdit = function(id) {
        const commentDiv = document.getElementById(`comment-${id}`);
        commentDiv.querySelector('.edit-comment-form').classList.add('hidden');
        commentDiv.querySelector('.comment-content').classList.remove('hidden');
    };

    // Delete comment
    window.deleteComment = async function(id) {
        if (!confirm('Delete this comment permanently?')) return;

        try {
            const res = await fetch(`/comments/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            if (res.ok) {
                document.getElementById(`comment-${id}`).remove();
            } else {
                alert('Could not delete comment');
            }
        } catch (err) {
            alert('Network error: ' + err.message);
        }
    };
    </script>
    @endpush
</x-app-layout>
