# Sistema de Registro Protegido - API Routes Mejorado

## 🔒 **Nuevo Sistema de Seguridad**

Ahora el sistema requiere que **solo usuarios autenticados puedan registrar nuevos usuarios**. Esto mejora la seguridad ya que evita registros no autorizados.

## 📋 **Estructura Actualizada de Rutas**

### 🔓 **Rutas Públicas (No requieren autenticación):**
```
GET  /api/suscribers     - Listar suscriptores
POST /api/suscribers     - Crear suscriptor
POST /api/auth/login     - Iniciar sesión (ÚNICA ruta pública de auth)
```

### 🔐 **Rutas con Sesiones (Tabla sessions):**
```
POST /api/auth/session-login   - Login con sesiones
POST /api/auth/session-logout  - Logout de sesiones  
GET  /api/auth/session-user    - Verificar usuario por sesión
GET  /api/auth/test-session    - Debug de sesiones
```

### 🔒 **Rutas Protegidas (Requieren token de autenticación):**

#### **Gestión de Usuarios:**
```
POST /api/auth/register        - ⚠️ PROTEGIDO: Solo usuarios autenticados pueden registrar otros
POST /api/users               - Crear usuario (alternativa a register)
GET  /api/users               - Listar usuarios  
GET  /api/users/{id}          - Ver usuario específico
PUT  /api/users/{id}          - Actualizar usuario
DELETE /api/users/{id}        - Eliminar usuario
GET  /api/users/search        - Buscar usuarios
```

#### **Gestión de Perfil:**
```
GET  /api/auth/profile         - Ver perfil actual
PUT  /api/auth/profile         - Actualizar perfil
POST /api/auth/change-password - Cambiar contraseña
```

#### **Gestión de Sesiones:**
```
POST /api/auth/logout         - Cerrar sesión actual
POST /api/auth/logout-all     - Cerrar todas las sesiones
GET  /api/auth/verify-token   - Verificar token
GET  /api/auth/active-sessions - Ver sesiones activas
```

## 🚀 **Flujo de Trabajo Inicial**

### **Paso 1: Crear el Primer Usuario (Comando)**
Como ya no hay registro público, usa este comando para crear el primer usuario administrador:

```bash
# Comando interactivo
php artisan user:create-first

# Comando con parámetros
php artisan user:create-first --email=admin@gmail.com --name="Administrador" --password=12345678
```

### **Paso 2: Hacer Login**
```bash
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
    "email": "admin@gmail.com",
    "password": "12345678"
}
```

**Respuesta:**
```json
{
    "success": true,
    "message": "Inicio de sesión exitoso",
    "data": {
        "user": { ... },
        "access_token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### **Paso 3: Registrar Nuevos Usuarios (Con Token)**
```bash
POST http://localhost:8000/api/auth/register
Authorization: Bearer 1|abc123...
Content-Type: application/json

{
    "name": "Nuevo Usuario",
    "email": "nuevo@gmail.com",  
    "password": "password123"
}
```

## 🛡️ **Beneficios de Seguridad**

### ✅ **Ventajas:**
- **Control total** sobre quién puede crear usuarios
- **Previene registros masivos** no autorizados
- **Auditoría completa** de creación de usuarios
- **Separación de responsabilidades** clara

### 🔐 **Casos de Uso:**
- **Sistema administrativo** - Solo admins crean usuarios
- **Sistema corporativo** - Solo RRHH crea empleados  
- **Sistema escolar** - Solo directores crean profesores
- **API empresarial** - Control estricto de acceso

## 📊 **Comandos Útiles**

### **Verificar usuarios existentes:**
```bash
php artisan tinker --execute="echo 'Total usuarios: ' . App\Models\User::count();"
```

### **Listar usuarios:**
```bash
php artisan tinker --execute="App\Models\User::select('id', 'name', 'email', 'created_at')->get()->each(function(\$u) { echo \$u->id . ' - ' . \$u->name . ' (' . \$u->email . ')' . PHP_EOL; });"
```

### **Crear usuario de emergencia (si es necesario):**
```bash
php artisan tinker --execute="App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('emergency123')]); echo 'Usuario de emergencia creado';"
```

## 🧪 **Ejemplo Completo en Thunder Client**

### **1. Crear primer usuario (Terminal):**
```bash
php artisan user:create-first --email=admin@gmail.com --name="Super Admin" --password=12345678
```

### **2. Login (Thunder Client):**
```
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
    "email": "admin@gmail.com",
    "password": "12345678"
}
```

### **3. Registrar nuevo usuario (Thunder Client):**
```
POST http://localhost:8000/api/auth/register  
Authorization: Bearer {token_del_paso_2}
Content-Type: application/json

{
    "name": "Juan Pérez",
    "email": "juan@gmail.com",
    "password": "password123"
}
```

### **4. Listar usuarios (Thunder Client):**
```
GET http://localhost:8000/api/users
Authorization: Bearer {token_del_paso_2}
```

## 🚨 **Solución de Problemas**

### **Error: "Unauthenticated"**
- Verifica que estés enviando el header `Authorization: Bearer {token}`
- Confirma que el token sea válido con `/api/auth/verify-token`

### **Error: "Ya existen usuarios"**
- Si ya tienes usuarios, usa login normal
- No necesitas crear primer usuario nuevamente

### **Error: "Session store not set"**
- Solo afecta rutas `/session-*`
- Las rutas normales usan tokens, no sesiones

## 🎯 **¡Tu API está ahora mucho más segura!**

- ✅ Registro controlado  
- ✅ Auditoría completa
- ✅ Flujo claro y documentado
- ✅ Comando para primer usuario
- ✅ Sistema híbrido (tokens + sesiones)
