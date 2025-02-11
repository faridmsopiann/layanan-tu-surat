@extends('admin.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container mt-4" style="font-family: 'Roboto', sans-serif;">
    <h2 style="font-weight: 700; color: #2C3E50;">Manajemen Pengguna</h2>

    @if(session('success'))
    <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tombol Tambah Pengguna -->
    <button class="btn btn-primary mb-3 shadow-sm" style="background-color: #3498DB; border: none; border-radius: 50px; padding: 8px 20px;" data-toggle="modal" data-target="#createUserModal">
        <i class="fas fa-plus-circle"></i> Add New User
    </button>

    <!-- Tabel Pengguna -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm" style="background-color: #f8f9fa; border-radius: 8px;">
            <thead style="background-color: #ECF0F1; color: #2C3E50;">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 1px; white-space: nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="align-middle" style="transition: background-color 0.3s;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td class="d-flex justify-content-center">
                            <!-- Tombol Edit dengan Modal -->
                            <button class="btn btn-outline-warning btn-sm mr-2" data-toggle="modal" data-target="#editUserModal{{ $user->id }}" title="Edit">
                                <i class="fas fa-user-edit"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit User -->
                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" onsubmit="return validatePassword(this);">
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
                                            {{-- <select class="form-control" id="role" name="role" required>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="pemohon" {{ $user->role == 'pemohon' ? 'selected' : '' }}>Pemohon</option>
                                                <option value="tu" {{ $user->role == 'tu' ? 'selected' : '' }}>TU</option>
                                                <option value="dekan" {{ $user->role == 'dekan' ? 'selected' : '' }}>Dekan</option>
                                                <option value="keuangan" {{ $user->role == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                                            </select> --}}

                                            <select name="role" class="form-control">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password:</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                        </div>

                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password:</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Leave blank to keep current password">
                                        </div>

                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Update User</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </tbody>
        </table>
        {!! $users->links('pagination::bootstrap-5') !!}
    </div>
</div>

<!-- Modal Create User -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.users.store') }}" method="POST" onsubmit="return validatePassword(this);">
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
                        <select name="role" class="form-control">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
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
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function validatePassword(form) {
        const password = form.password.value;
        const confirmation = form.password_confirmation.value;

        // Cek panjang password
        if (password.length < 8) {
            alert('Password must be at least 8 characters long.');
            return false; // Mencegah pengiriman form
        }

        // Cek kecocokan password dan konfirmasi
        if (password !== confirmation) {
            alert('Passwords do not match.');
            return false; // Mencegah pengiriman form
        }

        return true; // Mengizinkan pengiriman form jika valid
    }
</script>

@endsection

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successAlert = document.getElementById("success-alert");

        if (successAlert) {
            setTimeout(function() {
                successAlert.classList.add("fade-out");
                setTimeout(function() {
                    successAlert.remove();
                }, 500); // Hapus elemen setelah animasi selesai
            }, 1000);
        }
    });
</script>

<script>
    function validatePassword(form) {
    const password = form.password.value;
    const confirmation = form.password_confirmation.value;

    // Hanya validasi jika password diisi
    if (password) {
        if (password.length < 8) {
            alert('Password must be at least 8 characters long.');
            return false; // Mencegah pengiriman form
        }

        if (password !== confirmation) {
            alert('Passwords do not match.');
            return false; // Mencegah pengiriman form
        }
    }

    return true; // Mengizinkan pengiriman form jika valid
}
</script>

<!-- Tambahkan CSS untuk transisi -->
<style>
    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
</style>
@endpush