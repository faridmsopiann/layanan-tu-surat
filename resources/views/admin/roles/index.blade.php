@extends('admin.layouts.app')

@section('content')
<div class="container mt-4" style="font-family: 'Roboto', sans-serif;">
    <h2 style="font-weight: 700; color: #2C3E50;">Manage Roles</h2>

    @if(session('success'))
    <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Button to Add Role -->
    <button class="btn btn-primary mb-3 shadow-sm" style="background-color: #3498DB; border: none; border-radius: 50px; padding: 8px 20px;" data-toggle="modal" data-target="#addRoleModal">
        <i class="fas fa-plus-circle"></i> Add New Role
    </button>

    <!-- Roles Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm" style="background-color: #f8f9fa; border-radius: 8px;">
            <thead style="background-color: #ECF0F1; color: #2C3E50;">
                <tr>
                    <th>No</th>
                    <th>Role Name</th>
                    <th style="width: 1px; white-space: nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="align-middle" style="transition: background-color 0.3s;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        <td class="d-flex justify-content-center">
                            <!-- Edit Button with Modal -->
                            <button class="btn btn-outline-warning btn-sm mr-2" data-toggle="modal" data-target="#editRoleModal{{ $role->id }}" title="Edit">
                                <i class="fas fa-user-edit"></i>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this role?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal for Editing Role -->
                    <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="name">Role Name:</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
                                        </div>

                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Role</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </tbody>
        </table>
        {!! $roles->links('pagination::bootstrap-5') !!}
    </div>
</div>

<!-- Modal for Adding Role -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                <h5 class="modal-title" id="addRoleModalLabel">Create New Role</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Role Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
