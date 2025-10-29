# 🔐 Sistema de Permisos - README

## ✅ Estado: Implementado y Funcional

El sistema de permisos está completamente implementado y listo para usar. Este sistema permite controlar el acceso de los usuarios a diferentes páginas y funcionalidades del proyecto.

---

## 📊 Resumen Ejecutivo

- **40 permisos** predefinidos
- **12 módulos** cubiertos
- **7 endpoints** de API
- **Sistema completo** de gestión de permisos
- **Middleware** para protección de rutas
- **Métodos helper** en el modelo User
- **Documentación completa**

---

## 🚀 Inicio Rápido

### 1. Las migraciones ya están ejecutadas ✅

```bash
# Ya ejecutado:
php artisan migrate --path=database/migrations/2025_10_22_015204_create_permissions_table.php
php artisan migrate --path=database/migrations/2025_10_22_015229_create_user_permissions_table.php
```

### 2. Los permisos ya están creados ✅

```bash
# Ya ejecutado:
php artisan db:seed --class=PermissionSeeder
```

### 3. Asignar permisos a un usuario (ejemplo)

```bash
# Desde tinker:
php artisan tinker

# Dar todos los permisos al usuario con ID 1
$user = User::find(1);
$allPermissions = Permission::pluck('id')->toArray();
$user->syncPermissions($allPermissions);
exit;
```

O desde la API:

```bash
# Usando curl (reemplaza TOKEN y USER_ID)
curl -X POST http://127.0.0.1:8000/api/permissions/users/1/assign \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"permission_ids": [1,2,3,4,5,6,7,8,9,10]}'
```

---

## 📚 Documentación Disponible

1. **PERMISOS_RESUMEN.md** - Resumen completo de la implementación
2. **PERMISOS_GUIDE.md** - Guía de uso detallada
3. **PERMISOS_TESTING.md** - Guía de pruebas con ejemplos
4. **PERMISOS_FRONTEND_EJEMPLO.jsx** - Ejemplos de implementación en React

---

## 🎯 Uso Básico

### En el Backend (Laravel)

```php
// Verificar permiso en un controlador
if (!auth()->user()->hasPermission('users.view')) {
    return response()->json(['message' => 'Sin permiso'], 403);
}

// Proteger una ruta
Route::get('/usuarios', [UserController::class, 'index'])
    ->middleware(['auth:sanctum', 'permission:users.view']);

// Asignar permiso a un usuario
$user->givePermission('users.view');

// Revocar permiso
$user->revokePermission('users.view');

// Sincronizar permisos (reemplaza todos)
$user->syncPermissions([1, 2, 3, 4, 5]);
```

### En el Frontend (React)

```javascript
// Verificar permiso
const { hasPermission } = usePermissions();

{hasPermission('users.view') && (
  <Link to="/usuarios">Ver Usuarios</Link>
)}

// Proteger una ruta
<Route path="/usuarios" element={
  <ProtectedRoute permission="users.view">
    <Users />
  </ProtectedRoute>
} />

// Mostrar componente condicionalmente
<Can perform="users.create">
  <button>Crear Usuario</button>
</Can>
```

---

## 🌐 Endpoints de API

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/permissions` | Listar todos los permisos |
| GET | `/api/permissions/modules` | Listar módulos |
| GET | `/api/permissions/users/{userId}` | Permisos de un usuario |
| POST | `/api/permissions/users/{userId}/assign` | Asignar permisos |
| POST | `/api/permissions/users/{userId}/give` | Dar un permiso |
| POST | `/api/permissions/users/{userId}/revoke` | Revocar permiso |
| GET | `/api/permissions/users/{userId}/check/{slug}` | Verificar permiso |

---

## 📋 Lista de Permisos

### Dashboard (1)
- `dashboard.view` - Ver Dashboard

### Usuarios (4)
- `users.view` - Ver Usuarios
- `users.create` - Crear Usuarios
- `users.edit` - Editar Usuarios
- `users.delete` - Eliminar Usuarios

### Roles (4)
- `roles.view` - Ver Roles
- `roles.create` - Crear Roles
- `roles.edit` - Editar Roles
- `roles.delete` - Eliminar Roles

### Registros (4)
- `registros.view` - Ver Registros
- `registros.create` - Crear Registros
- `registros.edit` - Editar Registros
- `registros.delete` - Eliminar Registros

### Ingresos (4)
- `ingresos.view` - Ver Ingresos
- `ingresos.create` - Crear Ingresos
- `ingresos.edit` - Editar Ingresos
- `ingresos.delete` - Eliminar Ingresos

### Tolerancias (2)
- `tolerancias.view` - Ver Tolerancias
- `tolerancias.edit` - Editar Tolerancias

### Tipos de Vehículo (4)
- `tipos-vehiculo.view` - Ver Tipos de Vehículo
- `tipos-vehiculo.create` - Crear Tipos de Vehículo
- `tipos-vehiculo.edit` - Editar Tipos de Vehículo
- `tipos-vehiculo.delete` - Eliminar Tipos de Vehículo

### Vehículos (4)
- `vehiculos.view` - Ver Vehículos
- `vehiculos.create` - Crear Vehículos
- `vehiculos.edit` - Editar Vehículos
- `vehiculos.delete` - Eliminar Vehículos

### Reportes (2)
- `reportes.view` - Ver Reportes
- `reportes.export` - Exportar Reportes

### Salidas (2)
- `salidas.view` - Ver Salidas
- `salidas.create` - Registrar Salidas

### Observaciones (4)
- `observaciones.view` - Ver Observaciones
- `observaciones.create` - Crear Observaciones
- `observaciones.edit` - Editar Observaciones
- `observaciones.delete` - Eliminar Observaciones

### Permisos (2)
- `permissions.view` - Ver Permisos
- `permissions.assign` - Asignar Permisos

---

## 🔧 Archivos Importantes

```
app/
├── Models/
│   ├── Permission.php          # Modelo de permisos
│   └── User.php               # Métodos de permisos agregados
├── Http/
│   ├── Controllers/
│   │   └── PermissionController.php  # CRUD de permisos
│   └── Middleware/
│       └── CheckPermission.php      # Middleware de verificación
database/
├── migrations/
│   ├── 2025_10_22_015204_create_permissions_table.php
│   └── 2025_10_22_015229_create_user_permissions_table.php
└── seeders/
    └── PermissionSeeder.php    # Permisos predefinidos
routes/
└── api.php                     # Rutas de permisos
bootstrap/
└── app.php                     # Registro del middleware
documentations/
├── PERMISOS_RESUMEN.md        # Este archivo
├── PERMISOS_GUIDE.md          # Guía completa
├── PERMISOS_TESTING.md        # Guía de pruebas
└── PERMISOS_FRONTEND_EJEMPLO.jsx  # Ejemplos React
```

---

## 🧪 Probar el Sistema

1. **Iniciar el servidor** (ya está corriendo):
```bash
php artisan serve
```

2. **Hacer login** para obtener token:
```bash
POST http://127.0.0.1:8000/api/auth/login
{
  "email": "tu@email.com",
  "password": "tupassword"
}
```

3. **Listar permisos**:
```bash
GET http://127.0.0.1:8000/api/permissions
Authorization: Bearer TU_TOKEN
```

4. **Asignar permisos**:
```bash
POST http://127.0.0.1:8000/api/permissions/users/1/assign
Authorization: Bearer TU_TOKEN
{
  "permission_ids": [1, 2, 3, 4, 5]
}
```

---

## ⚠️ Notas Importantes

1. **Seguridad**: Siempre valida permisos en el backend, no solo en frontend
2. **Token**: Todas las rutas de permisos requieren autenticación con token
3. **Cache**: Para mejor rendimiento, considera cachear los permisos del usuario
4. **Super Admin**: Considera crear una verificación especial para super admins

---

## 🎓 Ejemplos Prácticos

### Ejemplo 1: Proteger CRUD de Usuarios

```php
// En routes/api.php
Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'index'])
        ->middleware('permission:users.view');
    
    Route::post('/', [UserController::class, 'store'])
        ->middleware('permission:users.create');
    
    Route::put('/{id}', [UserController::class, 'update'])
        ->middleware('permission:users.edit');
    
    Route::delete('/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete');
});
```

### Ejemplo 2: Verificar en Controlador

```php
public function index()
{
    // Opción 1: Verificación manual
    if (!auth()->user()->hasPermission('users.view')) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permiso para ver usuarios'
        ], 403);
    }

    // Opción 2: Usando abort
    abort_unless(auth()->user()->hasPermission('users.view'), 403);

    // Tu lógica aquí...
}
```

### Ejemplo 3: Asignar permisos al crear usuario

```php
public function store(Request $request)
{
    $user = User::create($request->validated());
    
    // Dar permisos básicos a un usuario nuevo
    $user->givePermission('dashboard.view');
    $user->givePermission('ingresos.view');
    $user->givePermission('ingresos.create');
    
    return response()->json(['success' => true, 'user' => $user]);
}
```

---

## 📞 Soporte

Para más información, consulta:
- `documentations/PERMISOS_GUIDE.md` - Guía detallada
- `documentations/PERMISOS_TESTING.md` - Ejemplos de pruebas
- `documentations/PERMISOS_FRONTEND_EJEMPLO.jsx` - Implementación en React

---

## ✨ ¡Todo Listo!

El sistema de permisos está completamente funcional y listo para usar. 

**Siguiente paso recomendado**: Asignar permisos a tus usuarios existentes y proteger las rutas importantes de tu aplicación.

---

**Creado**: 22 de octubre de 2025  
**Versión**: 1.0  
**Laravel**: 12  
**Estado**: ✅ Producción
