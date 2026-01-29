<h3>Comments</h3>

<div id="comments">
@foreach($task->comments as $comment)
    <div id="comment-{{ $comment->id }}" style="margin-bottom:10px;">
        <strong>{{ $comment->user->name }}</strong><br>

        <span class="comment-text">{{ $comment->comment }}</span><br>

        <small>{{ $comment->created_at->diffForHumans() }}</small>

        @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
            <br>
            <button onclick="editComment('{{ $comment->id }}')" data-comment-id="{{ $comment->id }}">Edit</button>
            <button onclick="deleteComment('{{ $comment->id }}')" data-comment-id="{{ $comment->id }}">Delete</button>
        @endif
    </div>
@endforeach
</div>


<form id="commentForm">
    @csrf
    <textarea name="comment" id="commentText" required></textarea>
    <button type="submit">Send</button>
</form>
<script>
document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    fetch("{{ route('tasks.comments.store', $task) }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            comment: document.getElementById('commentText').value
        })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('comments').innerHTML +=
            `<p><strong>${data.user}</strong>: ${data.comment} <small>(${data.time})</small></p>`;
        document.getElementById('commentText').value = '';
    });

    
});
</script>

<h3>Attachments</h3>

<form method="POST" action="{{ route('tasks.files.store', $task) }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>
<ul>
@foreach($task->files as $file)
    <li>
        <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank">
            {{ $file->file_name }}
        </a>

        @if(auth()->id() === $file->user_id || auth()->user()->role === 'admin')
            <form method="POST" action="{{ route('tasks.files.destroy', $file) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        @endif
    </li>
@endforeach
</ul>

<script>
function editComment(id) {
    let newText = prompt("Edit your comment:");
    if (!newText) return;

    fetch(`/comments/${id}`, {
        method: "PUT",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ comment: newText })
    })
    .then(res => res.json())
    .then(() => {
        document.querySelector(`#comment-${id} .comment-text`).innerText = newText;
    });
}

function deleteComment(id) {
    if (!confirm("Delete this comment?")) return;

    fetch(`/comments/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(res => res.json())
    .then(() => {
        document.getElementById(`comment-${id}`).remove();
    });
}
</script>

