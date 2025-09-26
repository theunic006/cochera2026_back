# Tabla Sessions - Sistema Híbrido de Autenticación

## 🔍 **¿Qué es la tabla `sessions`?**

La tabla `sessions` en Laravel almacena las sesiones de usuarios cuando usas el driver de sesiones `database`. Es diferente a los tokens de Sanctum y te permite tener un sistema híbrido.

### 📊 **Estructura de la tabla `sessions`:**

```sql
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,           -- ID único de la sesión
    user_id BIGINT UNSIGNED NULL,          -- ID del usuario (si está autenticado)
    ip_address VARCHAR(45) NULL,           -- Dirección IP del usuario
    user_agent TEXT NULL,                  -- Información del navegador
    payload LONGTEXT NOT NULL,             -- Datos serializados de la sesión
    last_activity INT NOT NULL             -- Timestamp de última actividad
);
```

## 🔄 **Sistemas de Autenticación Disponibles:**

### 1. **Sanctum (Tokens) - Para APIs**
- ✅ **Stateless** (sin servidor de estado)
- ✅ Perfecto para **móviles** y **SPAs**
- ✅ Tokens que **no expiran** automáticamente
- ✅ Cada dispositivo puede tener su token

### 2. **Sessions (Sesiones) - Para Web Tradicional**
- ✅ **Stateful** (almacena estado en servidor)
- ✅ Perfecto para **aplicaciones web** tradicionales
- ✅ Sesiones que **expiran** automáticamente
- ✅ Manejo de **"Recordarme"**

## 🚀 **Nuevas APIs para Sesiones Tradicionales:**

### 1. Iniciar Sesión con Sesiones
**POST** `/api/auth/session-login`

```json
{
    "email": "admin@gmail.com",
    "password": "12345678",
    "remember": true
}
```

**Respuesta:**
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
        "session_id": "abc123def456...",
        "remember": true
    }
}
```

### 2. Verificar Usuario por Sesión
**GET** `/api/auth/session-user`

```json
{
    "success": true,
    "message": "Usuario autenticado",
    "data": {
        "user": {
            "id": 1,
            "name": "Administrador",
            "email": "admin@gmail.com"
        },
        "session_id": "abc123def456...",
        "authenticated": true
    }
}
```

### 3. Cerrar Sesión Tradicional
**POST** `/api/auth/session-logout`

```json
{
    "success": true,
    "message": "Sesión cerrada exitosamente"
}
```

### 4. Ver Sesiones y Tokens Activos
**GET** `/api/auth/active-sessions` (Requiere token Sanctum)

```json
{
    "success": true,
    "message": "Sesiones activas obtenidas",
    "data": {
        "tokens": [
            {
                "id": 1,
                "name": "auth_token",
                "last_used_at": "2025-09-24 15:30:00",
                "created_at": "2025-09-24 14:00:00",
                "type": "token"
            }
        ],
        "sessions": [
            {
                "id": "abc123def456...",
                "ip_address": "127.0.0.1",
                "user_agent": "Mozilla/5.0...",
                "last_activity": "2025-09-24 15:30:00",
                "type": "session"
            }
        ],
        "total_tokens": 1,
        "total_sessions": 1
    }
}
```

## 🔧 **Configuración Actual:**

Tu Laravel está configurado para usar `database` como driver de sesiones en `config/session.php`:

```php
'driver' => env('SESSION_DRIVER', 'database'),
```

## 📱 **¿Cuándo usar cada método?**

### **Usa Sanctum (Tokens) para:**
- 📱 **Aplicaciones móviles** (iOS, Android)
- 🖥️ **SPAs** (React, Vue, Angular)
- 🔗 **APIs públicas**
- 🔄 **Integraciones de terceros**

### **Usa Sessions para:**
- 🌐 **Aplicaciones web tradicionales**
- 👤 **Paneles de administración**
- 🔐 **Autenticación temporal**
- 💾 **Cuando necesites "Recordarme"**

## 🧪 **Ejemplo de Uso en Thunder Client:**

### Para Tokens (Sanctum):
```bash
# 1. Login
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
    "email": "admin@gmail.com",
    "password": "12345678"
}

# 2. Usar en requests protegidos
GET http://localhost:8000/api/auth/profile
Authorization: Bearer {token_obtenido}
```

### Para Sesiones:
```bash
# 1. Login con sesión
POST http://localhost:8000/api/auth/session-login
Content-Type: application/json

{
    "email": "admin@gmail.com",
    "password": "12345678",
    "remember": true
}

# 2. Verificar sesión (automático con cookies)
GET http://localhost:8000/api/auth/session-user
```

## 🔍 **Monitoreo de la tabla `sessions`:**

Puedes consultar directamente la tabla para ver sesiones activas:

```sql
-- Ver todas las sesiones activas
SELECT 
    id,
    user_id,
    ip_address,
    FROM_UNIXTIME(last_activity) as last_activity_readable,
    user_agent
FROM sessions 
WHERE user_id IS NOT NULL 
ORDER BY last_activity DESC;

-- Contar sesiones por usuario
SELECT 
    user_id,
    COUNT(*) as session_count
FROM sessions 
WHERE user_id IS NOT NULL 
GROUP BY user_id;
```

## 🚨 **Seguridad:**

- ✅ **Sessions expiran automáticamente** según configuración
- ✅ **IP y User-Agent** se registran para auditoría
- ✅ **Regeneración de ID** de sesión tras login
- ✅ **Invalidación completa** en logout

¡Ahora tienes un **sistema híbrido completo** que usa tanto la tabla `sessions` como tokens Sanctum! 🎉
