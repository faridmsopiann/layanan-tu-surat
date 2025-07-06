<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Menampilkan daftar role
    public function index()
    {
        $roles = Role::paginate(5);
        return view('admin.roles.index', compact('roles'));
    }

    // Menyimpan role baru (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create($request->only('name'));
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    // Mengambil data role untuk edit (AJAX)
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    // Mengupdate role (AJAX)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->only('name'));
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    // Menghapus role (AJAX)
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
