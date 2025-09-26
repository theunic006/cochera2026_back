# 🏢 **CRUD Companies - Sistema Completo con Gestión de Estados**

## ✅ **Resumen de lo Implementado:**

**¡Sistema CRUD completo para `companies` con gestión de estados y creación automática de usuarios administradores!**

### 📊 **Estructura de la Base de Datos:**
- **Tabla:** `companies` (renombrada desde `empresas`)
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `nombre` - VARCHAR(100) NOT NULL
  - `ubicacion` - VARCHAR(255) NULLABLE
  - `logo` - TEXT NULLABLE
  - `descripcion` - TEXT NULLABLE
  - `estado` - ENUM('activo', 'suspendido', 'inactivo', 'pendiente') DEFAULT 'activo' **[NUEVO]**
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

### 🎯 **Funcionalidades Principales:**
- ✅ **CRUD completo** (Crear, Leer, Actualizar, Eliminar)
- ✅ **Gestión de estados** (Activo, Suspendido, Inactivo, Pendiente)
- ✅ **Creación automática de usuario administrador** al crear company
- ✅ **Conteo de usuarios por company** en todas las respuestas **[NUEVO]**
- ✅ **Filtrado por estados** con paginación
- ✅ **Búsqueda avanzada** por nombre, ubicación y descripción
- ✅ **Validaciones robustas** en todos los endpoints
- ✅ **Autenticación requerida** para todas las operaciones

---

## 🚀 **APIs Disponibles (Requieren autenticación)**

### **Base URL:** `http://127.0.0.1:8000/api/companies`

---

## 📋 **CRUD Básico**

### **1. Listar Companies (GET) 🆕 Con Conteo de Usuarios**
```http
GET /api/companies?page=1&per_page=10
Authorization: Bearer {token}
```

**📌 Funcionalidad Nueva:**
- ✅ **Campo `users_count`** - Muestra la cantidad de usuarios que pertenecen a cada company
- ✅ **Optimización de consultas** - Usa `withCount()` para mejor rendimiento
- ✅ **Disponible en todas las APIs** de listado (index, search, by-status)

### **2. Crear Company (POST) 🆕 Con Usuario Admin Automático**
```http
POST /api/companies
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Tech Solutions S.A.",
    "ubicacion": "Madrid, España",
    "logo": "https://example.com/logo.png",
    "descripcion": "Empresa de soluciones tecnológicas",
    "estado": "activo"
}
```

**📌 Respuesta incluye:**
- ✅ Datos de la company creada
- ✅ **Usuario administrador creado automáticamente**
- ✅ **Password generado** (solo se muestra en creación)
- ✅ **Rol asignado:** Administrador General (ID: 2)

### **3. Ver Company Específica (GET)**
```http
GET /api/companies/{id}
Authorization: Bearer {token}
```

### **4. Actualizar Company (PUT)**
```http
PUT /api/companies/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Tech Solutions S.L. (Actualizada)",
    "ubicacion": "Barcelona, España",
    "estado": "suspendido"
}
```

### **5. Eliminar Company (DELETE)**
```http
DELETE /api/companies/{id}
Authorization: Bearer {token}
```

### **6. Buscar Companies (GET)**
```http
GET /api/companies/search?query=tecnología
Authorization: Bearer {token}
```

---

## 🔄 **Gestión de Estados (NUEVO)**

### **Estados Disponibles:**
- 🟢 **`activo`** - Company operativa (por defecto)
- 🟡 **`pendiente`** - En proceso de activación
- 🔴 **`suspendido`** - Temporalmente suspendida
- ⚫ **`inactivo`** - Desactivada

### **7. Obtener Estados Disponibles**
```http
GET /api/companies/statuses
Authorization: Bearer {token}
```

### **8. Filtrar por Estado**
```http
GET /api/companies/by-status?estado=activo&per_page=10
Authorization: Bearer {token}
```

### **9. Activar Company**
```http
PATCH /api/companies/{id}/activate
Authorization: Bearer {token}
```

### **10. Suspender Company**
```http
PATCH /api/companies/{id}/suspend
Authorization: Bearer {token}
```

### **11. Cambiar Estado Personalizado**
```http
PATCH /api/companies/{id}/change-status
Authorization: Bearer {token}
Content-Type: application/json

{
    "estado": "inactivo"
}
```

---

## 🔧 **Comandos de Consola**

### **Generar Companies de Prueba:**
```bash
# Generar 20 companies (por defecto)
php artisan companies:generate

# Generar cantidad específica
php artisan companies:generate 50

# Ejemplo de salida:
# 🏢 Generando 10 companies de prueba...
# ✅ 10 companies generadas exitosamente!
# 📊 Total de companies en la base de datos: 15
```

### **Listar Companies:**
```bash
# Ver primeras 10 companies
php artisan companies:list

# Ver página específica
php artisan companies:list --page=2

# Cambiar límite por página
php artisan companies:list --limit=20

# Buscar companies
php artisan companies:list --search="Tecnología"
```

---

## 📝 **Archivos Creados/Modificados:**

### **1. Modelo:**
- `app/Models/Company.php` - Modelo con gestión de estados y relaciones

### **2. Controlador:**
- `app/Http/Controllers/CompanyController.php` - CRUD completo + gestión de estados

### **3. Form Requests:**
- `app/Http/Requests/StoreCompanyRequest.php` - Validaciones para crear (incluye estado)
- `app/Http/Requests/UpdateCompanyRequest.php` - Validaciones para actualizar (incluye estado)

### **4. Resource:**
- `app/Http/Resources/CompanyResource.php` - Formato de respuestas JSON con estado

### **5. Rutas:**
- `routes/api.php` - Rutas protegidas bajo `/api/companies` + rutas de estados

### **6. Comandos:**
- `app/Console/Commands/GenerateCompanies.php` - Generar datos de prueba
- `app/Console/Commands/ListCompanies.php` - Listar companies

### **7. Migraciones:**
- `create_companies_table.php` - Tabla companies
- `add_estado_column_to_companies_table.php` - **[NUEVO]** Columna estado

### **8. Características del Modelo Company:**
- ✅ **Constantes de estados** definidas
- ✅ **Métodos útiles:** `isActive()`, `isSuspended()`, `activate()`, `suspend()`
- ✅ **Scopes:** `active()`, `suspended()`, `byEstado()`
- ✅ **Relación con Users** via `id_company`

---

## 🧪 **Pruebas con Thunder Client**

### **Paso 1: Login**
```
POST http://127.0.0.1:8000/api/auth/login
{
    "email": "admin@gmail.com",
    "password": "12345678"
}
```

### **Paso 2: Usar Token**
```
GET http://127.0.0.1:8000/api/companies
Authorization: Bearer {token_obtenido}
```

### **Paso 3: Crear Company**
```
POST http://127.0.0.1:8000/api/companies
Authorization: Bearer {token}
{
    "nombre": "Mi Empresa Test",
    "ubicacion": "Lima, Perú",
    "descripcion": "Empresa de prueba para testing"
}
```

---

## 🎯 **Validaciones Implementadas:**

### **Crear Company:**
- `nombre`: Requerido, máximo 100 caracteres
- `ubicacion`: Opcional, máximo 255 caracteres
- `logo`: Opcional, texto
- `descripcion`: Opcional, texto
- `estado`: Opcional, debe ser: `activo`, `suspendido`, `inactivo`, `pendiente` **[NUEVO]**

### **Actualizar Company:**
- `nombre`: Opcional pero requerido si se envía, máximo 100 caracteres
- `estado`: Opcional, debe ser uno de los estados válidos **[NUEVO]**
- Otros campos opcionales

### **Cambiar Estado:**
- `estado`: Requerido, debe ser uno de los estados válidos
- Validación automática contra constantes del modelo

---

## 📊 **Ejemplos de Respuestas JSON:**

### **1. Listar Companies (con Conteo de Usuarios):**
```json
{
    "success": true,
    "message": "Companies obtenidas exitosamente",
    "data": [
        {
            "id": 1,
            "nombre": "Tech Solutions S.A.",
            "ubicacion": "Madrid, España",
            "logo": "https://example.com/logo.png",
            "descripcion": "Empresa de soluciones tecnológicas",
            "estado": "activo",
            "estado_info": {
                "label": "Activo",
                "is_active": true,
                "is_suspended": false
            },
            "users_count": 5,
            "created_at": "2025-09-25 10:30:00",
            "updated_at": "2025-09-25 10:30:00"
        },
        {
            "id": 2,
            "nombre": "Logística Perú",
            "ubicacion": "Lima, Perú",
            "logo": null,
            "descripcion": "Empresa de logística y transporte",
            "estado": "suspendido",
            "estado_info": {
                "label": "Suspendido",
                "is_active": false,
                "is_suspended": true
            },
            "users_count": 12,
            "created_at": "2025-09-25 09:15:00",
            "updated_at": "2025-09-25 14:20:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 5,
        "per_page": 10,
        "total": 50,
        "from": 1,
        "to": 10
    }
}
```

### **2. Crear Company (con Usuario Admin Automático):**
```json
{
    "success": true,
    "message": "Company creada exitosamente con usuario administrador",
    "data": {
        "company": {
            "id": 14,
            "nombre": "Company Pendiente",
            "ubicacion": "Cusco, Peru",
            "logo": null,
            "descripcion": "Company en estado pendiente",
            "estado": "pendiente",
            "estado_info": {
                "label": "Pendiente",
                "is_active": false,
                "is_suspended": false
            },
            "users_count": 1,
            "created_at": "2025-09-25 17:56:47",
            "updated_at": "2025-09-25 17:56:47"
        },
        "admin_user": {
            "id": 109,
            "name": "Admin Company Pendiente",
            "email": "admin@company-pendiente.com",
            "password": "q*p#D1sxuLE8",
            "categoria": "Administrador",
            "role": "Administrador General"
        }
    }
}
```

### **2. Cambiar Estado:**
```json
{
    "success": true,
    "message": "Company suspendida exitosamente",
    "data": {
        "id": 13,
        "nombre": "Cochera Demo Company",
        "ubicacion": "Arequipa, Peru",
        "logo": null,
        "descripcion": "Segunda company de prueba",
        "estado": "suspendido",
        "estado_info": {
            "label": "Suspendido",
            "is_active": false,
            "is_suspended": true
        },
        "users_count": 3,
        "created_at": "2025-09-25 17:46:07",
        "updated_at": "2025-09-25 17:56:09"
    }
}
```

### **3. Estados Disponibles:**
```json
{
    "success": true,
    "message": "Estados disponibles obtenidos exitosamente",
    "data": {
        "estados": [
            "activo",
            "suspendido", 
            "inactivo",
            "pendiente"
        ],
        "constantes": {
            "ACTIVO": "activo",
            "SUSPENDIDO": "suspendido",
            "INACTIVO": "inactivo",
            "PENDIENTE": "pendiente"
        }
    }
}
```

---

## 🔧 **Tabla de Rutas Completa:**

| Método | Endpoint | Función | Autenticación |
|--------|----------|---------|---------------|
| `GET` | `/api/companies` | Listar companies | ✅ Bearer Token |
| `POST` | `/api/companies` | Crear company + usuario admin | ✅ Bearer Token |
| `GET` | `/api/companies/search` | Buscar companies | ✅ Bearer Token |
| `GET` | `/api/companies/statuses` | Estados disponibles | ✅ Bearer Token |
| `GET` | `/api/companies/by-status` | Filtrar por estado | ✅ Bearer Token |
| `PATCH` | `/api/companies/{id}/activate` | Activar company | ✅ Bearer Token |
| `PATCH` | `/api/companies/{id}/suspend` | Suspender company | ✅ Bearer Token |
| `PATCH` | `/api/companies/{id}/change-status` | Cambiar estado | ✅ Bearer Token |
| `GET` | `/api/companies/{id}` | Ver company específica | ✅ Bearer Token |
| `PUT` | `/api/companies/{id}` | Actualizar company | ✅ Bearer Token |
| `DELETE` | `/api/companies/{id}` | Eliminar company | ✅ Bearer Token |

---

## ✅ **Estado Actual:**

✅ **Tabla `companies` con columna estado**  
✅ **14 companies de prueba con diferentes estados**  
✅ **CRUD completo + gestión de estados implementado**  
✅ **Creación automática de usuarios administradores**  
✅ **109 usuarios creados (incluyendo admins de companies)**  
✅ **APIs protegidas con autenticación**  
✅ **Validaciones robustas implementadas**  
✅ **Filtrado y búsqueda avanzada**  
✅ **Comandos de consola funcionando**  
✅ **Respuestas JSON consistentes con estado**  
✅ **Sistema de transacciones para integridad**  
✅ **Servidor Laravel ejecutándose**  

---

## 🎉 **¡Sistema Completo de Companies Implementado!**

### **🆕 Funcionalidades Nuevas:**
- 🔄 **Gestión completa de estados** (activo, suspendido, inactivo, pendiente)
- 👤 **Creación automática de usuario administrador** al crear company
- 🔐 **Passwords fijos para administradores** (admin123456)
- 📧 **Emails únicos generados** basados en nombre de company
- 🎯 **Rol automático asignado:** Administrador General (ID: 2)
- 👥 **Conteo de usuarios por company** en todas las respuestas **[NUEVO]**
- 🔍 **Filtrado avanzado por estados** con paginación
- ⚡ **Endpoints específicos** para activar/suspender
- 🔄 **Cambio de estado personalizado** a cualquier estado válido
- 🛡️ **Transacciones BD** para garantizar integridad
- 📊 **Información detallada de estado** en respuestas

### **📈 Características principales:**
- ✅ **APIs RESTful completas** (11 endpoints)
- ✅ **Autenticación Bearer Token** requerida
- ✅ **Validaciones de datos robustas**
- ✅ **Comandos de consola para gestión**
- ✅ **Datos de prueba con estados diversos**
- ✅ **Paginación y búsqueda avanzada**
- ✅ **Manejo de errores completo**
- ✅ **Documentación actualizada**
- ✅ **Relaciones con usuarios implementadas**
- ✅ **Sistema escalable y mantenible**

### **🚀 Listo para producción:**
**¡Tu sistema de gestión de companies está completamente implementado y probado!**

---

## 🧪 **Ejemplos Prácticos Completos**

### **Flujo Completo: Crear Company → Cambiar Estados**

#### **1. Login y Obtener Token:**
```http
POST http://127.0.0.1:8000/api/auth/login
Content-Type: application/json

{
    "email": "admin@gmail.com",
    "password": "12345678"
}
```

#### **2. Crear Nueva Company (con Usuario Admin):**
```http
POST http://127.0.0.1:8000/api/companies
Authorization: Bearer {tu_token}
Content-Type: application/json

{
    "nombre": "Mi Cochera Ejemplo",
    "ubicacion": "Lima, Perú",
    "descripcion": "Cochera de ejemplo para documentación",
    "telefono": "+51999888777",
    "email": "info@micocheraejemplo.com",
    "estado": "pendiente"
}
```

#### **3. Activar la Company:**
```http
PATCH http://127.0.0.1:8000/api/companies/{id}/activate
Authorization: Bearer {tu_token}
```

#### **4. Ver Companies Activas:**
```http
GET http://127.0.0.1:8000/api/companies/by-status?estado=activo&per_page=5
Authorization: Bearer {tu_token}
```

#### **5. Suspender si es necesario:**
```http
PATCH http://127.0.0.1:8000/api/companies/{id}/suspend
Authorization: Bearer {tu_token}
```

### **💡 Comandos de Consola Útiles:**
```bash
# Ver companies por estado
php artisan companies:list --search="estado_activo"

# Ver usuarios administradores creados
php artisan users:list --search="Admin"

# Generar más companies de prueba
php artisan companies:generate 10
```

---

## 📞 **Información de Contacto del Sistema:**

### **🔑 Credenciales de Prueba:**
- **Usuario:** `admin@gmail.com`
- **Password:** `12345678`
- **Bearer Token:** `Bearer 19|h0dKDfm2CrRqJHJg7ivsKsxrL8WMsRJw1OYqJudvb32745e1`

### **📊 Estado Actual de la BD:**
- **Companies:** 14 (con diferentes estados)
- **Users:** 109 (incluyendo administradores de companies)
- **Roles:** 8 (incluyendo Administrador General)

**Usa Thunder Client, Postman o cualquier cliente HTTP para probar todas las funcionalidades** 🎯
