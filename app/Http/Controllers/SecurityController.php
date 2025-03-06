<?php

namespace App\Http\Controllers;

use App\Models\Employee;
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
      Permission::create($request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('permissions')->with('error', 'Error al crear el permiso' . $e->getMessage());
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
    try {
      DB::beginTransaction();
      $permission->update($request->all());
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('permissions')->with('error', 'Error al actualizar el permiso' . $e->getMessage());
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
      return redirect()->route('permissions')->with('error', 'Error al eliminar el permiso' . $e->getMessage());
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
        'guard_name' => $request->guard_name,
        'id_enterprises' => $request->enterpriseId
      ]);
      $permissions = $request->permissions ?? [];
      for ($i = 0; $i < count($permissions); $i++) {
        $permission = Permission::find($permissions[$i]);
        $role->givePermissionTo($permission);
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('roles')->with('error', 'Error al crear el rol' . $e->getMessage());
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
    try {
      DB::beginTransaction();
      $role->update($request->all());
      $role->permissions()->detach();
      $permissions = $request->permissions ?? [];
      for ($i = 0; $i < count($permissions); $i++) {
        $permission = Permission::find($permissions[$i]);
        $role->givePermissionTo($permission);
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('roles')->with('error', 'Error al actualizar el rol' . $e->getMessage());
    }

    return redirect()->route('roles')->with('success', 'Rol actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroyRole(Role $role)
  {
    try {
      DB::beginTransaction();
      if ($role->users->count() > 0) {
        return redirect()->route('roles')->with('error', 'El rol no se puede eliminar porque está asignado a un usuario');
      }
      $role->delete();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('roles')->with('error', 'Error al eliminar el rol' . $e->getMessage());
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
    $users = $this->userRepository->customSearchMultipleByEnterprise(['users.username', 'employees.fullname', 'employees.document_number'], $search, self::TAKE, $request->session()->get('enterpriseId'));
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

    $employees = $this->employeeRepository->allByEnterpriseNotUsers(request()->session()->get('enterpriseId'));
    $user = new User();
    $roles = Role::all();
    return view('security.users.create', compact('user', 'roles', 'employees'));
  }

  /**
   * Store a newly created resource in storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeUser(Request $request)
  {
    $this->employeeRepository->setEnterpriseId(request());
    $this->employeeRepository->setBranchId(request());
    if ($this->employeeRepository->existUser($request->employee_id)) {
      return redirect()->route('users.create')->with('info', 'El empleado ya tiene un usuario asignado')->withInput();
    }
    $employee = $this->employeeRepository->find($request->employee_id);
    $request->merge([
      'branch_id' => $employee->branch_id,
      'id_enterprises' => $employee->id_enterprises
    ]);
    $this->validate($request, User::$rules, User::$messages);
    try {
      DB::beginTransaction();
      $user = User::create([
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'branch_id' => $request->branch_id,
        'id_enterprises' => $request->id_enterprises
      ]);
      $roles = $request->role_id;
      foreach ($roles as $role) {
        $user->assignRole(Role::find($role));
      }
      $employee->user_id = $user->id;
      $employee->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('users.create')->with('error', 'Error al crear el usuario' . $e->getMessage())->withInput();
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
    return view('security.users.edit', compact('user', 'roles'));
  }

  /**
   * Update the specified resource in storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updateUser(Request $request, User $user)
  {
    try {
      DB::beginTransaction();
      if ($request->password == NULL) {
        $request->merge(['password' => $user->password]);
        $request->merge(['password_confirmation' => $user->password]);
      }
      $request->merge(['id_branches' => $user->id_branches]);
      $request->validate(User::$rules, User::$messages);
      if ($request->password != $request->password_confirmation) {
        return redirect()->route('users.edit', $user->id_users)->with('error', 'Las contraseñas no coinciden')->withInput();
      }
      $user->update($request->all());
      $roles = $request->id_roles;
      $user->roles()->detach();
      foreach ($roles as $role) {
        $user->assignRole(Role::find($role));
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('users')->with('error', 'Error al actualizar el usuario' . $e->getMessage());
    }

    return redirect()->route('users')->with('success', 'Usuario actualizado correctamente');
  }

  /**
   * Remove the specified resource from storage.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroyUser(User $user)
  {
    return redirect()->route('users')->with('success', 'Usuario eliminado correctamente');
  }
}
