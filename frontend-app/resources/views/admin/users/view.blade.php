@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Top Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-primary" href="#">User View</a>

            <div class="d-flex ms-auto">
                <form method="POST" action="{{ route('frontend.logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-center text-primary">👤 Profile Overview</h3>

                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Name</strong>
                            <span>{{ $user['name'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Email</strong>
                            <span>{{ $user['email'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Role</strong>
                            <span class="badge bg-info text-dark">{{ ucfirst($user['role'] ?? 'N/A') }}</span>
                        </li>
                    </ul>

                   <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
