<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Enterprise;
use App\Models\User;
use App\Repository\EmployeeRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SecurityController extends Controller
{

  protected UserRepository $userRepository;
  protected EmployeeRepository $employeeRepository;
  /**
   * Constructor
   */
  public function __construct(UserRepository $userRepository, EmployeeRepository $employeeRepository)
  {
    $this->userRepository = $userRepository;
    $this->employeeRepository = $employeeRepository;
  }


  /**
   * Module Security
   * Permissions
   * Roles
   */
  private $rulesPermission = [
    'description' => 'required',
    'name' => 'required | unique:permissions',
    'group' => 'required'
  ];

  private $messagesPermission = [
    'description.required' => 'La descripción del permiso es requerida',
    'name.required' => 'El nombre del permiso es requerido',
    'name.unique' => 'El nombre del permiso ya existe',
    'group.required' => 'El grupo del permiso es requerido'
  ];

  private $rulesRole = [
    'description' => 'required',
    'name' => 'required | unique:roles'
  ];

  private $messagesRole = [
    'description.required' => 'La descripción del rol es requerida',
    'name.required' => 'El nombre del rol es requerido',
    'name.unique' => 'El nombre del rol ya existe'
  ];

  /**
   * List all permissions
   * @return \Illuminate\View\View
   */
  public function permissions(Request $request)
  {
    $search = $request->search ?? '';
    $permissions = Permission::where('name', 'like', "%$search%")
      ->orWhere('description', 'like', "%$search%")
      ->paginate(self::TAKE);
    $permission = new Permission();
    return view('security.permissions.index', compact('permissions', 'permission'));
  }

  /**
   * Show the form for creating a new resource.
   * @return \Illuminate\View\View
   */
  public function createPermission()
  {
    $permission = new Permission();
    return view('security.permissions.create', compact('permission'));
  }

  /**
   * Store a newly created resource in storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storePermission(Request $request)
  {
    $request->validate($this->rulesPermission, $this->messagesPermission);
    try {
      DB::beginTransaction();
      Permission::create($request->only(['name', 'description', 'group', 'subname', 'guard_name']));
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('permissions')->with('error', 'Error al crear el permiso: ' . $e->getMessage());
    }

    return redirect()->route('permissions')->with('success', 'Permiso creado correctamente');
  }

  /**
   * Show the form for editing the specified resource.
   * @return \Illuminate\View\View
   */
  public function editPermission(Permission $permission)
  {
    return view('security.permissions.edit', compact('permission'));
  }

  /**
   * Update the specified resource in storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updatePermission(Request $request, Permission $permission)
  {
    $request->validate([
      'description' => 'required',
      'name' => 'required|unique:permissions,name,' . $permission->id,
      'group' => 'required',
    ], $this->messagesPermission);

    try {
      DB::beginTransaction();
      $permission->update($request->only(['name', 'description', 'group', 'subname', 'guard_name']));
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('permissions')->with('error', 'Error al actualizar el permiso: ' . $e->getMessage());
    }

    return redirect()->route('permissions')->with('success', 'Permiso actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   * @return \Illuminate\Http\RedirectResponse
   */

  public function destroyPermission(Permission $permission)
  {
    try {
      DB::beginTransaction();
      if ($permission->roles->count() > 0) {
        return redirect()->route('permissions')->with('error', 'El permiso no se puede eliminar porque está asignado a un rol');
      }
      $permission->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('permissions')->with('error', 'Error al eliminar el permiso: ' . $e->getMessage());
    }
    return redirect()->route('permissions')->with('success', 'Permiso eliminado correctamente');
  }

  /**
   * List all roles
   * @return \Illuminate\View\View
   */
  public function roles(Request $request)
  {
    $search = $request->search ?? '';

    $roles = Role::where('name', 'like', "%$search%")
      ->orWhere('description', 'like', "%$search%")
      ->paginate(self::TAKE);
    $role = new Role();
    return view('security.roles.index', compact('roles', 'role'));
  }

  /**
   * Show the form for creating a new resource.
   * @return \Illuminate\View\View
   */
  public function createRole()
  {
    $role = new Role();
    $permissions = Permission::all()->groupBy('group');
    return view('security.roles.create', compact('role', 'permissions'));
  }

  /**
   * Store a newly created resource in storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeRole(Request $request)
  {
    $this->validate($request, $this->rulesRole, $this->messagesRole);
    try {
      DB::beginTransaction();
      $role = Role::create([
        'name' => $request->name,
        'description' => $request->description,
        'guard_name' => $request->guard_name ?? 'web',
      ]);
      $permissionIds = $request->permissions ?? [];
      $role->syncPermissions(Permission::whereIn('id', $permissionIds)->get());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('roles')->with('error', 'Error al crear el rol: ' . $e->getMessage());
    }

    return redirect()->route('roles')->with('success', 'Rol creado correctamente');
  }

  /**
   * Show the form for editing the specified resource.
   * @return \Illuminate\View\View
   */
  public function editRole(Role $role)
  {
    $permissions = Permission::all()->groupBy('group');
    $rolePermissions = $role->permissions->pluck('id')->toArray();
    $checkGroup = $role->permissions->groupBy('group');
    return view('security.roles.edit', compact('role', 'permissions', 'rolePermissions', 'checkGroup'));
  }

  /**
   * Update the specified resource in storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updateRole(Request $request, Role $role)
  {
    $request->validate([
      'description' => 'required',
      'name' => 'required|unique:roles,name,' . $role->id,
    ], $this->messagesRole);

    if ($role->name === 'master') {
      return redirect()->route('roles')->with('error', 'El rol master no puede modificarse.');
    }

    try {
      DB::beginTransaction();
      $role->update($request->only(['name', 'description', 'guard_name']));
      $permissionIds = $request->permissions ?? [];
      $role->syncPermissions(Permission::whereIn('id', $permissionIds)->get());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('roles')->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
    }

    return redirect()->route('roles')->with('success', 'Rol actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroyRole(Role $role)
  {
    if ($role->name === 'master') {
      return redirect()->route('roles')->with('error', 'El rol master no puede eliminarse.');
    }

    try {
      DB::beginTransaction();
      if ($role->users->count() > 0) {
        return redirect()->route('roles')->with('error', 'El rol no se puede eliminar porque está asignado a un usuario');
      }
      $role->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('roles')->with('error', 'Error al eliminar el rol: ' . $e->getMessage());
    }
    return redirect()->route('roles')->with('success', 'Rol eliminado correctamente');
  }

  /**
   * List all users
   * @return \Illuminate\View\View
   */
  public function users(Request $request)
  {
    $search = $request->search ?? '';
    $users = User::whereNull('cuid_deleted')
      ->where(function ($q) use ($search) {
        if ($search) {
          $q->where('username', 'like', "%{$search}%")
            ->orWhere('fullname', 'like', "%{$search}%");
        }
      })
      ->paginate(self::TAKE);
    $roles = Role::all();
    return view('security.users.index', compact('users', 'roles'));
  }

  /**
   * Show the form for show the specified resource.
   * @return \Illuminate\View\View
   */
  public function showUser(User $user)
  {
    $roles = Role::all();
    return view('security.users.show', compact('user', 'roles'));
  }

  /**
   * Show the form for creating a new resource.
   * @return \Illuminate\View\View
   */
  public function createUser()
  {
    $user = new User();
    $roles = Role::all();
    $enterprises = Enterprise::whereNull('cuid_deleted')->get();
    return view('security.users.create', compact('user', 'roles', 'enterprises'));
  }

  /**
   * Store a newly created resource in storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeUser(Request $request)
  {
    $request->validate([
      'username' => 'required|string|max:16',
      'password' => 'required|string|min:8|max:255',
      'fullname' => 'required|string|max:128',
      'id_enterprises' => 'nullable|exists:enterprises,id_enterprises',
      'role_id' => 'required|array|min:1',
      'role_id.*' => 'exists:roles,id',
    ], [
      'username.required' => 'El nombre de usuario es requerido.',
      'username.max' => 'El nombre de usuario no debe exceder los 16 caracteres.',
      'password.required' => 'La contraseña es requerida.',
      'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
      'fullname.required' => 'El nombre completo es requerido.',
      'role_id.required' => 'Debe seleccionar al menos un rol.',
    ]);

    $exists = User::where('username', $request->username)->whereNull('cuid_deleted')->first();
    if ($exists) {
      return redirect()->route('users.create')->with('error', 'El nombre de usuario ya existe.')->withInput();
    }

    try {
      DB::beginTransaction();
      $user = User::create([
        'fullname' => $request->fullname,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'id_enterprises' => $request->id_enterprises,
        'status' => 1,
      ]);

      $user->syncRoles(Role::whereIn('id', $request->role_id)->get());

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('users.create')->with('error', 'Error al crear el usuario: ' . $e->getMessage())->withInput();
    }

    return redirect()->route('users')->with('success', 'Usuario creado correctamente');
  }

  /**
   * Show the form for editing the specified resource.
   * @return \Illuminate\View\View
   */
  public function editUser(User $user)
  {
    $roles = Role::all();
    $enterprises = Enterprise::whereNull('cuid_deleted')->get();
    return view('security.users.edit', compact('user', 'roles', 'enterprises'));
  }

  /**
   * Update the specified resource in storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updateUser(Request $request, User $user)
  {
    $request->validate([
      'fullname' => 'required|string|max:128',
      'id_enterprises' => 'nullable|exists:enterprises,id_enterprises',
      'role_id' => 'required|array|min:1',
      'role_id.*' => 'exists:roles,id',
      'password' => 'nullable|string|min:8|max:255|confirmed',
    ], [
      'fullname.required' => 'El nombre completo es requerido.',
      'role_id.required' => 'Debe seleccionar al menos un rol.',
      'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
      'password.confirmed' => 'Las contraseñas no coinciden.',
    ]);

    try {
      DB::beginTransaction();

      $user->fullname = $request->fullname;
      $user->id_enterprises = $request->id_enterprises;

      if ($request->password) {
        $user->password = Hash::make($request->password);
      }

      $user->save();

      $user->syncRoles(Role::whereIn('id', $request->role_id)->get());

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('users')->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
    }

    return redirect()->route('users')->with('success', 'Usuario actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroyUser(User $user)
  {
    if ($user->id_users == auth()->id()) {
      return redirect()->route('users')->with('error', 'No puedes eliminar tu propio usuario.');
    }
    if ($user->hasRole('master')) {
      return redirect()->route('users')->with('error', 'No se puede eliminar a un usuario con rol master.');
    }

    try {
      DB::beginTransaction();

      $employee = Employee::where('id_users', $user->id_users)->first();
      if ($employee) {
        $employee->id_users = null;
        $employee->save();
      }

      $user->roles()->detach();
      $user->permissions()->detach();
      $this::softDelete($user);

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('users')->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
    }
    return redirect()->route('users')->with('success', 'Usuario eliminado correctamente');
  }
}
