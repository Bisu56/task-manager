<h2>Add Department</h2>

<form method="POST" action="{{ route('admin.departments.store') }}">
    @csrf

    <input type="text" name="name" placeholder="Department name" required>
    <br><br>
    <textarea name="description" placeholder="Description"></textarea>
    <br><br>

    <button type="submit">Save</button>
</form>
