# 👥 **CRUD Propietarios - Sistema de Gestión de Propietarios**

## ✅ **Resumen de lo Implementado:**

**¡Sistema CRUD completo para gestión de propietarios con validaciones robustas y búsqueda avanzada!**

### 📊 **Estructura de la Base de Datos:**
- **Tabla:** `propietarios`
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `nombres` - VARCHAR(100) NOT NULL
  - `apellidos` - VARCHAR(100) NOT NULL
  - `documento` - VARCHAR(20) UNIQUE NOT NULL
  - `telefono` - VARCHAR(15) NULLABLE
  - `email` - VARCHAR(100) UNIQUE NOT NULL
  - `direccion` - TEXT NULLABLE
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

### 🎯 **Funcionalidades Principales:**
- ✅ **CRUD completo** (Crear, Leer, Actualizar, Eliminar)
- ✅ **Validación de documentos únicos** con formato específico
- ✅ **Validación de emails únicos** con formato correcto
- ✅ **Búsqueda avanzada** por nombres, apellidos, documento o email
- ✅ **Filtrado específico** por documento
- ✅ **Paginación automática** (15 elementos por página)
- ✅ **Relaciones con vehículos** (many-to-many)
- ✅ **Autenticación Bearer Token** requerida

---

## 🚀 **APIs Disponibles (Requieren autenticación)**

### **Base URL:** `http://127.0.0.1:8000/api/propietarios`

---

## 📋 **CRUD Básico**

### **1. Listar Propietarios (GET)**
```http
GET /api/propietarios?page=1&per_page=15
Authorization: Bearer {token}
```

**Parámetros de filtrado:**
- `search` - Buscar en nombres, apellidos, documento o email
- `documento` - Filtrar por documento específico

**Ejemplo con filtros:**
```http
GET /api/propietarios?search=juan&page=1
GET /api/propietarios?documento=12345678
```

### **2. Crear Propietario (POST)**
```http
POST /api/propietarios
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombres": "Juan Carlos",
    "apellidos": "Pérez López",
    "documento": "12345678",
    "telefono": "555-1234",
    "email": "juan.perez@example.com",
    "direccion": "Calle 123 #45-67, Lima"
}
```

### **3. Ver Propietario Específico (GET)**
```http
GET /api/propietarios/{id}
Authorization: Bearer {token}
```

### **4. Actualizar Propietario (PUT)**
```http
PUT /api/propietarios/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombres": "Juan Carlos Eduardo",
    "apellidos": "Pérez López",
    "telefono": "555-5678",
    "direccion": "Nueva dirección actualizada"
}
```

### **5. Eliminar Propietario (DELETE)**
```http
DELETE /api/propietarios/{id}
Authorization: Bearer {token}
```

---

## 🎯 **Validaciones Implementadas:**

### **Crear Propietario:**
- `nombres`: Requerido, máximo 100 caracteres
- `apellidos`: Requerido, máximo 100 caracteres
- `documento`: Requerido, único, máximo 20 caracteres, solo números
- `telefono`: Opcional, máximo 15 caracteres
- `email`: Requerido, único, formato email válido
- `direccion`: Opcional, texto libre

### **Actualizar Propietario:**
- Mismas validaciones que crear, pero todos los campos son opcionales
- `documento` y `email` deben seguir siendo únicos si se actualizan

---

## 📊 **Ejemplos de Respuestas JSON:**

### **1. Listar Propietarios:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nombres": "Juan Carlos",
            "apellidos": "Pérez López",
            "documento": "12345678",
            "telefono": "555-1234",
            "email": "juan.perez@test.com",
            "direccion": "Calle 123 #45-67",
            "nombre_completo": "Juan Carlos Pérez López",
            "documento_formateado": "12345678",
            "created_at": "2025-09-25 21:30:00",
            "updated_at": "2025-09-25 21:30:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 3,
        "last_page": 1
    }
}
```

### **2. Crear Propietario:**
```json
{
    "success": true,
    "message": "Propietario creado exitosamente",
    "data": {
        "id": 4,
        "nombres": "Carlos Alberto",
        "apellidos": "Rodríguez Martín",
        "documento": "11223344",
        "telefono": "555-9012",
        "email": "carlos.rodriguez@test.com",
        "direccion": "Carrera 456 #78-90",
        "nombre_completo": "Carlos Alberto Rodríguez Martín",
        "documento_formateado": "11223344",
        "created_at": "2025-09-25 22:15:00",
        "updated_at": "2025-09-25 22:15:00"
    }
}
```

### **3. Error de Validación:**
```json
{
    "success": false,
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "documento": ["Ya existe un propietario con este documento"],
        "email": ["Ya existe un propietario con este email"]
    }
}
```

---

## 🔧 **Archivos del Sistema:**

### **1. Modelo:**
- `app/Models/Propietario.php` - Modelo con relaciones many-to-many con vehículos

### **2. Controlador:**
- `app/Http/Controllers/PropietarioController.php` - CRUD completo con filtros

### **3. Form Requests:**
- `app/Http/Requests/StorePropietarioRequest.php` - Validaciones para crear
- `app/Http/Requests/UpdatePropietarioRequest.php` - Validaciones para actualizar

### **4. Resource:**
- `app/Http/Resources/PropietarioResource.php` - Formato JSON de respuestas

### **5. Migraciones:**
- `create_propietarios_table.php` - Estructura inicial
- `add_columns_to_propietarios_table.php` - Columnas específicas

---

## 🧪 **Pruebas con Thunder Client:**

### **Paso 1: Login**
```
POST http://127.0.0.1:8000/api/auth/login
{
    "email": "admin@gmail.com",
    "password": "12345678"
}
```

### **Paso 2: Crear Propietario**
```
POST http://127.0.0.1:8000/api/propietarios
Authorization: Bearer {token_obtenido}
{
    "nombres": "María Elena",
    "apellidos": "González Rivera",
    "documento": "87654321",
    "telefono": "555-5678",
    "email": "maria.gonzalez@test.com",
    "direccion": "Avenida 789 #12-34"
}
```

### **Paso 3: Buscar Propietarios**
```
GET http://127.0.0.1:8000/api/propietarios?search=maría
Authorization: Bearer {token}
```

---

## 📈 **Funcionalidades del Modelo:**

### **Relaciones:**
- `vehiculos()` - Relación many-to-many con vehículos
- `vehiculosActivos()` - Solo vehículos con relación vigente
- `vehiculoPropietarios()` - Registros de la tabla intermedia

### **Métodos Útiles:**
- `getNombreCompletoAttribute()` - Nombres + apellidos
- `getDocumentoFormateadoAttribute()` - Documento en mayúsculas
- `tieneVehiculos()` - Verificar si tiene vehículos asociados
- `cantidadVehiculosActivos()` - Contar vehículos activos

### **Scopes de Consulta:**
- `byNombres($nombres)` - Filtrar por nombres
- `byApellidos($apellidos)` - Filtrar por apellidos
- `byDocumento($documento)` - Filtrar por documento
- `byEmail($email)` - Filtrar por email

---

## ✅ **Estado Actual:**

✅ **Tabla `propietarios` creada** con estructura completa  
✅ **CRUD completo implementado** (5 endpoints)  

### **📋 Los 5 Endpoints CRUD Implementados:**

1. **GET** `/api/propietarios` - Listar propietarios con paginación y filtros
2. **POST** `/api/propietarios` - Crear nuevo propietario
3. **GET** `/api/propietarios/{id}` - Ver propietario específico
4. **PUT** `/api/propietarios/{id}` - Actualizar propietario existente  
5. **DELETE** `/api/propietarios/{id}` - Eliminar propietario


✅ **Validaciones robustas** para integridad de datos  
✅ **Búsqueda y filtrado** avanzado implementado  
✅ **Relaciones con vehículos** configuradas  
✅ **Paginación automática** para mejor rendimiento  
✅ **Form Requests** con mensajes personalizados  
✅ **Resource** para respuestas JSON consistentes  
✅ **Autenticación** Bearer Token requerida  
✅ **3 registros de prueba** creados y funcionando  

---

## 🎉 **¡Sistema de Propietarios Completamente Implementado!**

### **🔍 Características Destacadas:**
- 👤 **Gestión completa de propietarios** con datos personales
- 🔐 **Validación de documentos únicos** para evitar duplicados
- 📧 **Emails únicos** con validación de formato
- 🔍 **Búsqueda inteligente** en múltiples campos
- 🚗 **Integración con vehículos** para gestión completa
- 📱 **API RESTful** siguiendo mejores prácticas
- 🛡️ **Seguridad robusta** con autenticación requerida

### **🎯 Casos de Uso:**
1. **Registro de propietarios** con documentación completa
2
.I **B
ú
# 📋 **Lista Completa de Endpoints CRUD Implementados**#squeda rápida** por cualquier campo relevante
3. **Actua
l
## **📊 Resumen Técnico:**#ización de datos** sin afect
| Categorí | Endpoints | Estado | Funcionalidad |aar relaciones
4. **Gestión de múltiples vehículos*
|----------|-----------|--------|---------------|
| **CRU Básico** | 5 | ✅ | Operaciones fundamentales |D-* por propietario
5. **Control de unicidad** en documentos y emails

**¡El sistema de propietarios está 
| **Búsqueda/Filtros* | 
 GET /api/propietarios                           # Listar todos✅5 | ✅ | Consultas avanzadas |*listo y completamente funcional!** 🎯
