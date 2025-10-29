# 🧪 Pruebas del Sistema de Permisos

Este documento contiene ejemplos de peticiones HTTP para probar el sistema de permisos.

---

## 📝 Prerrequisitos

1. Tener las migraciones ejecutadas
2. Tener el seeder de permisos ejecutado
3. Tener un usuario autenticado con token

---

## 🔐 Obtener Token de Autenticación

Primero necesitas autenticarte para obtener un token:

```http
POST http://127.0.0.1:8000/api/auth/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "user": {...},
    "token": "1|abcdef123456..."
  }
}
```

Usa este token en todas las peticiones siguientes:
```
Authorization: Bearer 1|abcdef123456...
```

---

## 1️⃣ Listar Todos los Permisos

```http
GET http://127.0.0.1:8000/api/permissions
Authorization: Bearer TU_TOKEN_AQUI
```

**Con filtros:**
```http
GET http://127.0.0.1:8000/api/permissions?module=Usuarios&per_page=10
Authorization: Bearer TU_TOKEN_AQUI
```

---

## 2️⃣ Obtener Módulos Disponibles

```http
GET http://127.0.0.1:8000/api/permissions/modules
Authorization: Bearer TU_TOKEN_AQUI
```

**Respuesta esperada:**
```json
{
  "success": true,
  "message": "Módulos obtenidos exitosamente",
  "data": [
    "Dashboard",
    "Usuarios",
    "Roles",
    "Registros",
    "Ingresos",
    "Tolerancias",
    "Tipos de Vehículo",
    "Vehículos",
    "Reportes",
    "Salidas",
    "Observaciones",
    "Permisos"
  ]
}
```

---

## 3️⃣ Obtener Permisos de un Usuario

Reemplaza `{userId}` con el ID del usuario que quieres consultar:

```http
GET http://127.0.0.1:8000/api/permissions/users/1
Authorization: Bearer TU_TOKEN_AQUI
```

**Respuesta esperada:**
```json
{
  "success": true,
  "message": "Permisos del usuario obtenidos exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com"
    },
    "permissions": []
  }
}
```

---

## 4️⃣ Asignar Permisos a un Usuario

```http
POST http://127.0.0.1:8000/api/permissions/users/1/assign
Authorization: Bearer TU_TOKEN_AQUI
Content-Type: application/json

{
  "permission_ids": [1, 2, 3, 5, 7, 9, 13, 17, 21]
}
```

**Explicación de IDs (basados en el seeder):**
- 1: dashboard.view
- 2: users.view
- 3: users.create
- 5: users.delete
- 7: roles.create
- 9: roles.delete
- 13: ingresos.view
- 17: tolerancias.view
- 21: vehiculos.view

**Respuesta esperada:**
```json
{
  "success": true,
  "message": "Permisos asignados exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com"
    },
    "permissions": [
      {
        "id": 1,
        "name": "Ver Dashboard",
        "slug": "dashboard.view",
        "module": "Dashboard"
      },
      ...
    ]
  }
}
```

---

## 5️⃣ Dar un Permiso Específico

```http
POST http://127.0.0.1:8000/api/permissions/users/1/give
Authorization: Bearer TU_TOKEN_AQUI
Content-Type: application/json

{
  "permission_id": 10
}
```

---

## 6️⃣ Revocar un Permiso

```http
POST http://127.0.0.1:8000/api/permissions/users/1/revoke
Authorization: Bearer TU_TOKEN_AQUI
Content-Type: application/json

{
  "permission_id": 10
}
```

---

## 7️⃣ Verificar si un Usuario Tiene un Permiso

```http
GET http://127.0.0.1:8000/api/permissions/users/1/check/users.view
Authorization: Bearer TU_TOKEN_AQUI
```

**Respuesta esperada:**
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

## 8️⃣ Probar Middleware de Permisos

### Ejemplo 1: Sin protección (funciona)
```http
GET http://127.0.0.1:8000/api/users
Authorization: Bearer TU_TOKEN_AQUI
```

### Ejemplo 2: Con protección (agrega el middleware a la ruta)

Modifica `routes/api.php`:
```php
Route::get('/', [UserController::class, 'index'])
    ->middleware('permission:users.view');
```

Luego prueba:

**Usuario CON permiso:**
```http
GET http://127.0.0.1:8000/api/users
Authorization: Bearer TU_TOKEN_AQUI
```
✅ Respuesta: 200 OK

**Usuario SIN permiso:**
```http
GET http://127.0.0.1:8000/api/users
Authorization: Bearer TU_TOKEN_AQUI
```
❌ Respuesta: 403 Forbidden
```json
{
  "success": false,
  "message": "No tienes permiso para acceder a este recurso",
  "required_permission": "users.view"
}
```

---

## 🎯 Casos de Prueba Recomendados

### Caso 1: Asignar Permisos Completos de Dashboard
```json
{
  "permission_ids": [1]
}
```

### Caso 2: Asignar Permisos Completos de Usuarios
```json
{
  "permission_ids": [2, 3, 4, 5]
}
```

### Caso 3: Asignar Permisos Solo de Lectura
```json
{
  "permission_ids": [1, 2, 6, 10, 14, 18, 22, 26, 30, 34, 38]
}
```

### Caso 4: Asignar Todos los Permisos (Super Admin)
```json
{
  "permission_ids": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40]
}
```

---

## 🐛 Troubleshooting

### Error: "No autenticado"
- Verifica que estás enviando el header `Authorization: Bearer TOKEN`
- Verifica que el token es válido

### Error: "Usuario no encontrado"
- Verifica que el ID del usuario existe
- Usa `GET /api/users` para ver los usuarios disponibles

### Error: "Permiso no encontrado"
- Verifica que ejecutaste el seeder: `php artisan db:seed --class=PermissionSeeder`
- Usa `GET /api/permissions` para ver los permisos disponibles

### Middleware no funciona
- Verifica que registraste el alias en `bootstrap/app.php`
- Verifica que la ruta tiene el middleware: `->middleware('permission:slug')`

---

## 📊 Consultar Base de Datos Directamente

Si quieres verificar en la base de datos:

```sql
-- Ver todos los permisos
SELECT * FROM permissions;

-- Ver permisos de un usuario
SELECT u.name, p.name as permission, p.slug 
FROM users u
JOIN user_permissions up ON u.id = up.user_id
JOIN permissions p ON up.permission_id = p.id
WHERE u.id = 1;

-- Ver usuarios con un permiso específico
SELECT u.id, u.name, u.email
FROM users u
JOIN user_permissions up ON u.id = up.user_id
JOIN permissions p ON up.permission_id = p.id
WHERE p.slug = 'users.view';
```

---

## ✅ Checklist de Pruebas

- [ ] Listar todos los permisos
- [ ] Listar módulos
- [ ] Obtener permisos de un usuario
- [ ] Asignar múltiples permisos a un usuario
- [ ] Dar un permiso específico
- [ ] Revocar un permiso
- [ ] Verificar permiso de un usuario
- [ ] Probar middleware con permiso válido (debe permitir)
- [ ] Probar middleware sin permiso (debe denegar 403)
- [ ] Verificar que los permisos persisten después de logout/login

---

## 🚀 Próximos Pasos

1. Implementar verificación de permisos en frontend React
2. Crear interfaz gráfica para gestionar permisos
3. Implementar roles con permisos predefinidos
4. Agregar logs de cambios de permisos
5. Implementar caché de permisos para mejor rendimiento
