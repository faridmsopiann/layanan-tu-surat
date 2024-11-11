@extends('admin.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
    <div class="container mt-4">
        <h2>Edit User</h2>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pemohon" {{ $user->role == 'pemohon' ? 'selected' : '' }}>Pemohon</option>
                    <option value="tu" {{ $user->role == 'tu' ? 'selected' : '' }}>TU</option>
                    <option value="dekan" {{ $user->role == 'dekan' ? 'selected' : '' }}>Dekan</option>
                    <option value="keuangan" {{ $user->role == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
@endsection
