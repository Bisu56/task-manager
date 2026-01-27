<h2>Edit Department</h2>

<form method="POST" action="{{ route('admin.departments.update', $department) }}">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $department->name }}" required>
    <br><br>
    <textarea name="description">{{ $department->description }}</textarea>
    <br><br>

    <button type="submit">Update</button>
</form>
