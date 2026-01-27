<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <input name="name" placeholder="Name" required>
    <input name="email" placeholder="Email" required>

    <select name="role" required>
        <option value="">Select Role</option>
        <option value="manager">Manager</option>
        <option value="staff">Staff</option>
    </select>

    <select name="department_id">
        <option value="">Select Department</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
        @endforeach
    </select>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Save</button>
</form>
