# 🔐 Sistema de Permisos - Guía Completa

## 📋 Descripción General

El sistema de permisos permite controlar el acceso de los usuarios a diferentes páginas y funcionalidades del proyecto. Los permisos se asignan individualmente a cada usuario y se verifican mediante middleware y métodos del modelo User.

---

## 🗂️ Estructura del Sistema

### Tablas de Base de Datos

1. **`permissions`** - Almacena todos los permisos disponibles
   - `id`: ID único del permiso
   - `name`: Nombre legible del permiso (ej: "Ver Dashboard")
   - `slug`: Identificador único del permiso (ej: "dashboard.view")
   - `description`: Descripción del permiso
   - `module`: Módulo al que pertenece (ej: "Dashboard", "Usuarios")
   - `is_active`: Si el permiso está activo
   - `created_at`, `updated_at`: Timestamps

2. **`user_permissions`** - Tabla pivote para relacionar usuarios con permisos
   - `id`: ID único
   - `user_id`: ID del usuario
   - `permission_id`: ID del permiso
   - `created_at`, `updated_at`: Timestamps

---

## 📦 Permisos Disponibles

### Dashboard
- `dashboard.view` - Ver Dashboard

### Usuarios
- `users.view` - Ver Usuarios
- `users.create` - Crear Usuarios
- `users.edit` - Editar Usuarios
- `users.delete` - Eliminar Usuarios

### Roles
- `roles.view` - Ver Roles
- `roles.create` - Crear Roles
- `roles.edit` - Editar Roles
- `roles.delete` - Eliminar Roles

### Registros
- `registros.view` - Ver Registros
- `registros.create` - Crear Registros
- `registros.edit` - Editar Registros
- `registros.delete` - Eliminar Registros

### Ingresos
- `ingresos.view` - Ver Ingresos
- `ingresos.create` - Crear Ingresos
- `ingresos.edit` - Editar Ingresos
- `ingresos.delete` - Eliminar Ingresos

### Tolerancias
- `tolerancias.view` - Ver Tolerancias
- `tolerancias.edit` - Editar Tolerancias

### Tipos de Vehículo
- `tipos-vehiculo.view` - Ver Tipos de Vehículo
- `tipos-vehiculo.create` - Crear Tipos de Vehículo
- `tipos-vehiculo.edit` - Editar Tipos de Vehículo
- `tipos-vehiculo.delete` - Eliminar Tipos de Vehículo

### Vehículos
- `vehiculos.view` - Ver Vehículos
- `vehiculos.create` - Crear Vehículos
- `vehiculos.edit` - Editar Vehículos
- `vehiculos.delete` - Eliminar Vehículos

### Reportes
- `reportes.view` - Ver Reportes
- `reportes.export` - Exportar Reportes

### Salidas
- `salidas.view` - Ver Salidas
- `salidas.create` - Registrar Salidas

### Observaciones
- `observaciones.view` - Ver Observaciones
- `observaciones.create` - Crear Observaciones
- `observaciones.edit` - Editar Observaciones
- `observaciones.delete` - Eliminar Observaciones

### Permisos
- `permissions.view` - Ver Permisos
- `permissions.assign` - Asignar Permisos

---

## 🛠️ Uso del Sistema

### 1. Métodos del Modelo User

El modelo `User` tiene varios métodos helper para trabajar con permisos:

```php
// Verificar si un usuario tiene un permiso específico
$user->hasPermission('users.view');  // Retorna true o false

// Verificar si tiene alguno de los permisos dados
$user->hasAnyPermission(['users.view', 'users.create']);  // Retorna true o false

// Verificar si tiene todos los permisos dados
$user->hasAllPermissions(['users.view', 'users.create', 'users.edit']);  // Retorna true o false

// Asignar un permiso a un usuario
$user->givePermission('users.view');
// O pasando el objeto Permission
$permission = Permission::where('slug', 'users.view')->first();
$user->givePermission($permission);

// Revocar un permiso de un usuario
$user->revokePermission('users.view');

// Sincronizar permisos (reemplaza todos los permisos del usuario)
$user->syncPermissions([1, 2, 3, 4]);  // Array de IDs de permisos
```

### 2. Proteger Rutas con Middleware

Puedes proteger rutas usando el middleware `CheckPermission`:

```php
// En routes/api.php
Route::get('/usuarios', [UserController::class, 'index'])
    ->middleware(['auth:sanctum', 'permission:users.view']);

Route::post('/usuarios', [UserController::class, 'store'])
    ->middleware(['auth:sanctum', 'permission:users.create']);

// Proteger un grupo de rutas
Route::middleware(['auth:sanctum', 'permission:users.view'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::get('/usuarios/{id}', [UserController::class, 'show']);
});
```

### 3. Verificar Permisos en Controladores

```php
public function index()
{
    // Verificar manualmente en el controlador
    if (!auth()->user()->hasPermission('users.view')) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permiso para ver usuarios'
        ], 403);
    }

    // Tu lógica aquí...
}
```

### 4. Verificar Permisos en Blade (si usas vistas)

```php
@if(auth()->user()->hasPermission('users.create'))
    <button>Crear Usuario</button>
@endif

@if(auth()->user()->hasAnyPermission(['users.edit', 'users.delete']))
    <button>Acciones</button>
@endif
```

---

## 🌐 Endpoints de la API

### Listar todos los permisos
```http
GET /api/permissions
GET /api/permissions?module=Usuarios
GET /api/permissions?per_page=50
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Permisos obtenidos exitosamente",
  "data": [
    {
      "id": 1,
      "name": "Ver Dashboard",
      "slug": "dashboard.view",
      "description": "Permite ver el dashboard principal",
      "module": "Dashboard",
      "is_active": true
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 3,
    "per_page": 20,
    "total": 45
  }
}
```

### Obtener módulos disponibles
```http
GET /api/permissions/modules
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Módulos obtenidos exitosamente",
  "data": [
    "Dashboard",
    "Usuarios",
    "Roles",
    "Ingresos",
    "Vehículos"
  ]
}
```

### Obtener permisos de un usuario
```http
GET /api/permissions/users/{userId}
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Permisos del usuario obtenidos exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com"
    },
    "permissions": [
      {
        "id": 1,
        "name": "Ver Dashboard",
        "slug": "dashboard.view",
        "module": "Dashboard"
      }
    ]
  }
}
```

### Asignar permisos a un usuario
```http
POST /api/permissions/users/{userId}/assign
Content-Type: application/json

{
  "permission_ids": [1, 2, 3, 5, 7]
}
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Permisos asignados exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com"
    },
    "permissions": [...]
  }
}
```

### Dar un permiso específico a un usuario
```http
POST /api/permissions/users/{userId}/give
Content-Type: application/json

{
  "permission_id": 5
}
```

### Revocar un permiso de un usuario
```http
POST /api/permissions/users/{userId}/revoke
Content-Type: application/json

{
  "permission_id": 5
}
```

### Verificar si un usuario tiene un permiso
```http
GET /api/permissions/users/{userId}/check/{permissionSlug}

Ejemplo:
GET /api/permissions/users/1/check/users.view
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Verificación completada",
  "data": {
    "user_id": 1,
    "permission_slug": "users.view",
    "has_permission": true
  }
}
```

---

## 🎯 Ejemplo de Uso Completo

### 1. Asignar permisos a un usuario recién creado

```php
// En el controlador después de crear un usuario
$user = User::create([...]);

// Dar permisos básicos
$user->givePermission('dashboard.view');
$user->givePermission('ingresos.view');
$user->givePermission('ingresos.create');

// O asignar múltiples permisos de una vez
$permissionIds = Permission::whereIn('slug', [
    'dashboard.view',
    'ingresos.view',
    'ingresos.create'
])->pluck('id')->toArray();

$user->syncPermissions($permissionIds);
```

### 2. Proteger una ruta completa

```php
// En routes/api.php
Route::prefix('usuarios')->middleware(['auth:sanctum'])->group(function () {
    // Solo usuarios con permiso users.view pueden ver
    Route::get('/', [UserController::class, 'index'])
        ->middleware('permission:users.view');
    
    // Solo usuarios con permiso users.create pueden crear
    Route::post('/', [UserController::class, 'store'])
        ->middleware('permission:users.create');
    
    // Solo usuarios con permiso users.edit pueden editar
    Route::put('/{id}', [UserController::class, 'update'])
        ->middleware('permission:users.edit');
    
    // Solo usuarios con permiso users.delete pueden eliminar
    Route::delete('/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete');
});
```

### 3. Frontend React - Verificar permisos

```javascript
// En tu componente React
const userPermissions = user.permissions.map(p => p.slug);

// Verificar si tiene permiso
const canViewUsers = userPermissions.includes('users.view');
const canCreateUsers = userPermissions.includes('users.create');

// Renderizar condicionalmente
{canCreateUsers && (
  <button onClick={handleCreate}>Crear Usuario</button>
)}

// O crear un helper
const hasPermission = (permission) => {
  return userPermissions.includes(permission);
};

{hasPermission('users.view') && (
  <Link to="/usuarios">Ver Usuarios</Link>
)}
```

---

## 🔧 Registro del Middleware

El middleware ya está creado, pero asegúrate de que esté registrado en `bootstrap/app.php`:

```php
// bootstrap/app.php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

---

## 📊 Consultas Útiles

```php
// Obtener todos los permisos activos
$permissions = Permission::active()->get();

// Obtener permisos por módulo
$permisosUsuarios = Permission::byModule('Usuarios')->get();

// Obtener usuarios con un permiso específico
$usersWithPermission = User::whereHas('permissions', function($query) {
    $query->where('slug', 'users.view');
})->get();

// Contar cuántos permisos tiene un usuario
$count = $user->permissions()->count();

// Verificar si un usuario tiene permisos
$hasAnyPermission = $user->permissions()->exists();
```

---

## ⚠️ Notas Importantes

1. **Autenticación requerida**: Los endpoints de permisos requieren autenticación con `auth:sanctum`
2. **Super Administrador**: Considera crear un rol de super admin que tenga acceso a todo sin necesidad de verificar permisos
3. **Cache**: Para aplicaciones grandes, considera cachear los permisos del usuario
4. **Frontend**: Siempre sincroniza los permisos en el frontend al hacer login
5. **Seguridad**: Los permisos en frontend son solo para UX, siempre valida en backend

---

## 🚀 Siguientes Pasos

1. **Asignar permisos a usuarios existentes**
2. **Proteger todas las rutas con el middleware**
3. **Implementar verificación de permisos en frontend**
4. **Crear un panel de administración de permisos**
5. **Considerar implementar roles con permisos predefinidos**

---

## 📞 Soporte

Si tienes dudas sobre el sistema de permisos, revisa este documento o consulta el código en:
- `app/Models/Permission.php`
- `app/Models/User.php`
- `app/Http/Controllers/PermissionController.php`
- `app/Http/Middleware/CheckPermission.php`
