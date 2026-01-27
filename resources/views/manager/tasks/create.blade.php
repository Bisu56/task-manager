<h2>Create New Task</h2>

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

<form method="POST" action="{{ route('manager.tasks.store') }}">
    @csrf

    {{-- Task Title --}}
    <div>
        <label>Title</label><br>
        <input
            type="text"
            name="title"
            value="{{ old('title') }}"
            placeholder="Task title"
            required
        >
    </div>
    <br>

    {{-- Task Description --}}
    <div>
        <label>Description</label><br>
        <textarea
            name="description"
            placeholder="Task description"
            required
        >{{ old('description') }}</textarea>
    </div>
    <br>

    {{-- Assign to Staff --}}
    <div>
        <label>Assign To (Staff)</label><br>
        <select name="assigned_to">
            <option value="">-- Select Staff --</option>
            @foreach($staffs as $staff)
                <option value="{{ $staff->id }}">
                    {{ $staff->name }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    {{-- Priority --}}
    <div>
        <label>Priority</label><br>
        <select name="priority" required>
            <option value="">-- Select Priority --</option>
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
        </select>
    </div>
    <br>

    {{-- Due Date --}}
    <div>
        <label>Due Date</label><br>
        <input
            type="date"
            name="due_date"
            value="{{ old('due_date') }}"
        >
    </div>
    <br>

    <button type="submit">Create Task</button>
</form>

<a href="{{ route('manager.tasks.index') }}">⬅ Back</a>
