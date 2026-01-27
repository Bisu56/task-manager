<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')

    <input
        name="name"
        value="{{ old('name', $user->name) }}"
        placeholder="Name"
        required
    >

    <input
        name="email"
        value="{{ old('email', $user->email) }}"
        placeholder="Email"
        required
    >

    <select name="role" required>
        <option value="">Select Role</option>
        <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>
            Manager
        </option>
        <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>
            Staff
        </option>
    </select>

    <select name="department_id">
        <option value="">Select Department</option>
        @foreach($departments as $dept)
            <option
                value="{{ $dept->id }}"
                {{ $user->department_id == $dept->id ? 'selected' : '' }}
            >
                {{ $dept->name }}
            </option>
        @endforeach
    </select>

    <!-- Optional password -->
    <input
        type="password"
        name="password"
        placeholder="New Password (leave blank to keep current)"
    >

    <button type="submit">Update</button>
</form>
