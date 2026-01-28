<h3>Comments</h3>

<div id="comments">
    @foreach($task->comments as $comment)
        <p>
            <strong>{{ $comment->user->name }}</strong>:
            {{ $comment->comment }}
            <small>({{ $comment->created_at->diffForHumans() }})</small>
        </p>
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
