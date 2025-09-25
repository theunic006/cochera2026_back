# API de Autenticación - Laravel 12 con Sanctum

Esta documentación describe todas las APIs disponibles para autenticación, registro, inicio/cierre de sesión y gestión de perfil de usuario.

## Base URL
```
http://localhost:8000/api/auth
```

## 🔓 Endpoints Públicos (No requieren autenticación)

### 1. Registrar Usuario
**POST** `/api/auth/register`

Registra un nuevo usuario y devuelve un token de acceso.

**Parámetros requeridos:**
```json
{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123"
}
```

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123"
  }'
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Usuario registrado exitosamente",
    "data": {
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com",
            "email_verified_at": null,
            "created_at": "2025-09-24 10:30:00",
            "updated_at": "2025-09-24 10:30:00"
        },
        "access_token": "1|abcdef123456...",
        "token_type": "Bearer"
    }
}
```

### 2. Iniciar Sesión
**POST** `/api/auth/login`

Autentica un usuario y devuelve un token de acceso.

**Parámetros requeridos:**
```json
{
    "email": "admin@gmail.com",
    "password": "12345678"
}
```

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@gmail.com",
    "password": "12345678"
  }'
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Inicio de sesión exitoso",
    "data": {
        "user": {
            "id": 1,
            "name": "Administrador",
            "email": "admin@gmail.com",
            "email_verified_at": null,
            "created_at": "2025-09-24 10:30:00",
            "updated_at": "2025-09-24 10:30:00"
        },
        "access_token": "2|xyz789abc456...",
        "token_type": "Bearer"
    }
}
```

## 🔒 Endpoints Protegidos (Requieren autenticación)

Para usar estos endpoints, debes incluir el token en el header:
```
Authorization: Bearer {tu_token_aqui}
```

### 3. Obtener Perfil
**GET** `/api/auth/profile`

Obtiene la información del usuario autenticado.

**Ejemplo con cURL:**
```bash
curl -X GET http://localhost:8000/api/auth/profile \
  -H "Authorization: Bearer 2|xyz789abc456..." \
  -H "Accept: application/json"
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Perfil obtenido exitosamente",
    "data": {
        "id": 1,
        "name": "Administrador",
        "email": "admin@gmail.com",
        "email_verified_at": null,
        "created_at": "2025-09-24 10:30:00",
        "updated_at": "2025-09-24 10:30:00"
    }
}
```

### 4. Actualizar Perfil
**PUT** `/api/auth/profile`

Actualiza la información del usuario autenticado.

**Parámetros opcionales:**
```json
{
    "name": "Juan Carlos Pérez",
    "email": "juancarlos@example.com",
    "current_password": "password123",
    "password": "newpassword456"
}
```

**Ejemplo con cURL:**
```bash
curl -X PUT http://localhost:8000/api/auth/profile \
  -H "Authorization: Bearer 2|xyz789abc456..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Carlos Pérez",
    "email": "juancarlos@example.com"
  }'
```

### 5. Cambiar Contraseña
**POST** `/api/auth/change-password`

Cambia la contraseña del usuario autenticado.

**Parámetros requeridos:**
```json
{
    "current_password": "password123",
    "new_password": "newpassword456",
    "new_password_confirmation": "newpassword456"
}
```

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8000/api/auth/change-password \
  -H "Authorization: Bearer 2|xyz789abc456..." \
  -H "Content-Type: application/json" \
  -d '{
    "current_password": "12345678",
    "new_password": "newpassword456",
    "new_password_confirmation": "newpassword456"
  }'
```

### 6. Cerrar Sesión
**POST** `/api/auth/logout`

Cierra la sesión actual revocando el token usado.

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer 2|xyz789abc456..." \
  -H "Accept: application/json"
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Sesión cerrada exitosamente"
}
```

### 7. Cerrar Todas las Sesiones
**POST** `/api/auth/logout-all`

Cierra todas las sesiones del usuario revocando todos sus tokens.

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8000/api/auth/logout-all \
  -H "Authorization: Bearer 2|xyz789abc456..." \
  -H "Accept: application/json"
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Todas las sesiones han sido cerradas exitosamente"
}
```

### 8. Verificar Token
**GET** `/api/auth/verify-token`

Verifica si el token actual es válido.

**Ejemplo con cURL:**
```bash
curl -X GET http://localhost:8000/api/auth/verify-token \
  -H "Authorization: Bearer 2|xyz789abc456..." \
  -H "Accept: application/json"
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Token válido",
    "data": {
        "user": {
            "id": 1,
            "name": "Administrador",
            "email": "admin@gmail.com",
            "email_verified_at": null,
            "created_at": "2025-09-24 10:30:00",
            "updated_at": "2025-09-24 10:30:00"
        },
        "token_valid": true
    }
}
```

## 📋 Ejemplos de Uso en Thunder Client

### 1. **Registro de Usuario:**
- **URL:** `http://localhost:8000/api/auth/register`
- **Método:** `POST`
- **Headers:** `Content-Type: application/json`
- **Body:**
```json
{
    "name": "chicho",
    "email": "chicho@gmail.com",
    "password": "12345678"
}
```

### 2. **Inicio de Sesión:**
- **URL:** `http://localhost:8000/api/auth/login`
- **Método:** `POST`
- **Headers:** `Content-Type: application/json`
- **Body:**
```json
{
    "email": "admin@gmail.com",
    "password": "12345678"
}
```

### 3. **Obtener Perfil (con token):**
- **URL:** `http://localhost:8000/api/auth/profile`
- **Método:** `GET`
- **Headers:** 
  - `Authorization: Bearer {tu_token}`
  - `Accept: application/json`

### 4. **Cerrar Sesión:**
- **URL:** `http://localhost:8000/api/auth/logout`
- **Método:** `POST`
- **Headers:** 
  - `Authorization: Bearer {tu_token}`
  - `Accept: application/json`

## 🔐 Seguridad y Tokens

- **Los tokens son generados usando Laravel Sanctum**
- **Los tokens no tienen expiración por defecto** (configurable)
- **Las contraseñas se encriptan con Hash de Laravel**
- **Los tokens se pueden revocar individualmente o todos a la vez**

## ❌ Códigos de Error

- **200 OK**: Operación exitosa
- **201 Created**: Usuario registrado exitosamente
- **401 Unauthorized**: Credenciales incorrectas o token inválido
- **422 Unprocessable Entity**: Errores de validación
- **500 Internal Server Error**: Error interno del servidor

## 🚦 Flujo de Autenticación Recomendado

1. **Registro/Login** → Obtener token
2. **Guardar token** en el cliente (localStorage, etc.)
3. **Incluir token** en todas las peticiones protegidas
4. **Verificar token** periódicamente
5. **Logout** cuando sea necesario

## 🛡️ Protección de Rutas

Todas las rutas del CRUD de usuarios ahora están protegidas y requieren autenticación:
- `GET /api/users` - Listar usuarios
- `POST /api/users` - Crear usuario
- `GET /api/users/{id}` - Ver usuario
- `PUT /api/users/{id}` - Actualizar usuario
- `DELETE /api/users/{id}` - Eliminar usuario

**¡Tu sistema de autenticación está listo para usar!** 🎉
