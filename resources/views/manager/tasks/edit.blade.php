<h2>Edit Task</h2>

{{-- Validation Errors --}}
@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('manager.tasks.update', $task) }}">
    @csrf
    @method('PUT')

    {{-- Task Title --}}
    <div>
        <label>Title</label><br>
        <input
            type="text"
            name="title"
            value="{{ old('title', $task->title) }}"
            required
        >
    </div>
    <br>

    {{-- Task Description --}}
    <div>
        <label>Description</label><br>
        <textarea
            name="description"
            required
        >{{ old('description', $task->description) }}</textarea>
    </div>
    <br>

    {{-- Assign to Staff --}}
    <div>
        <label>Assign To (Staff)</label><br>
        <select name="assigned_to">
            <option value="">-- Select Staff --</option>
            @foreach($staffs as $staff)
                <option
                    value="{{ $staff->id }}"
                    {{ $task->assigned_to == $staff->id ? 'selected' : '' }}
                >
                    {{ $staff->name }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    {{-- Status --}}
    <div>
        <label>Status</label><br>
        <select name="status" required>
            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>
                Pending
            </option>
            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>
                In Progress
            </option>
            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>
                Completed
            </option>
        </select>
    </div>
    <br>

    {{-- Priority --}}
    <div>
        <label>Priority</label><br>
        <select name="priority" required>
            <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
        </select>
    </div>
    <br>

    {{-- Due Date --}}
    <div>
        <label>Due Date</label><br>
        <input
            type="date"
            name="due_date"
            value="{{ old('due_date', $task->due_date) }}"
        >
    </div>
    <br>

    <button type="submit">Update Task</button>
</form>

<a href="{{ route('manager.tasks.index') }}">⬅ Back</a>
su