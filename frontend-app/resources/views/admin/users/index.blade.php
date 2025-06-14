@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">User Management</h2>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
        <div class="container-fluid">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">Add New User</a>


            <div class="d-flex ms-auto">
                <a href="{{ route('frontend.dashboard') }}" class="btn btn-outline-secondary" style="margin-right:5px;">Back</a>
                <form method="POST" action="{{ route('frontend.logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>


    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <thead >
            <tr>
                <th></th>
                <th>
                    <input type="text" class="form-control" id="name" placeholder="Search by name">
                </th>
                <th>
                    <input type="text" class="form-control" id="email" placeholder="Search by email">
                </th>
                <th>
                    <input type="text" class="form-control" id="role" placeholder="Search by role">
                </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users['data'] as $user)
            <tr>
                <td>{{ $user['id'] }}</td>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['email'] }}</td>
                <td>{{ $user['role'] }}</td>
                <td>
                    <a href="{{ route('admin.users.view', $user['id']) }}" class="btn btn-sm btn-primary">View</a>
                    <a href="{{ route('admin.users.edit', $user['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user['id']) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this user?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const token = "{{ session('token') }}";
        console.log(token);
        function fetchFilteredUsers() {
            const name = $('#name').val();
            const email = $('#email').val();
            const role = $('#role').val();

            $.ajax({
                url: `http://127.0.0.1:8000/api/admin/users/search`,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                },
                data: {
                    name: name,
                    email: email,
                    role: role
                },
                success: function (response) {
                    const tbody = $('tbody');
                    tbody.empty();

                    if (response.data.length === 0) {
                        tbody.append('<tr><td colspan="5">No users found.</td></tr>');
                    } else {
                        response.data.forEach(user => {
                            tbody.append(`
                                <tr>
                                    <td>${user.id}</td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td>${user.role}</td>
                                    <td>
                                        <a href="/admin/users/${user.id}" class="btn btn-sm btn-primary">View</a>
                                        <a href="/admin/users/${user.id}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="/admin/users/${user.id}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Delete this user?')" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                },
                error: function () {
                    alert('Search failed. Try again.');
                }
            });
        }

        // Trigger search on input change with delay
        let typingTimer;
        const doneTypingInterval = 500;

        $('#name, #email, #role').on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchFilteredUsers, doneTypingInterval);
        });
    });
</script>
@endpush
