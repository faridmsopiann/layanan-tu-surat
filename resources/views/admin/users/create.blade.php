@extends('admin.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
    <div class="container mt-4">
        <h2>Create New User</h2>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="pemohon">Pemohon</option>
                    <option value="tu">TU</option>
                    <option value="dekan">Dekan</option>
                    <option value="keuangan">Keuangan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary">Create User</button>
        </form>
    </div>
@endsection
