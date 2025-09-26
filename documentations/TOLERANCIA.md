# ⏱️ **CRUD Tolerancia - Sistema de Gestión de Tiempos**

## ✅ **Resumen de lo Implementado:**

**¡Sistema CRUD completo para gestión de tolerancias de tiempo por tipo de vehículo!**

### 📊 **Estructura de la Base de Datos:**
- **Tabla:** `tolerancia`
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `minutos` - INT NOT NULL
  - `tipo_vehiculo_id` - FK → tipo_vehiculos(id) UNIQUE
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

### 🎯 **Funcionalidades Principales:**
- ✅ **CRUD completo** (Crear, Leer, Actualizar, Eliminar)
- ✅ **Tolerancia única** por tipo de vehículo
- ✅ **Validación de minutos** con rangos específicos
- ✅ **Relación directa** con tipos de vehículo
- ✅ **Filtrado por tipo** de vehículo
- ✅ **Búsqueda por rangos** de minutos
- ✅ **Paginación automática** (15 elementos por página)
- ✅ **Formateo automático** de tiempo (X minutos)
- ✅ **Autenticación Bearer Token** requerida

---

## 🚀 **APIs Disponibles (Requieren autenticación)**

### **Base URL:** `http://127.0.0.1:8000/api/tolerancias`

---

## 📋 **Lista Completa de Endpoints**

| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| **GET** | `/api/tolerancias` | Listar todas las tolerancias con filtros | ✅ Bearer Token |
| **POST** | `/api/tolerancias` | Crear nueva tolerancia | ✅ Bearer Token |
| **GET** | `/api/tolerancias/{id}` | Obtener tolerancia específica | ✅ Bearer Token |
| **PUT** | `/api/tolerancias/{id}` | Actualizar tolerancia existente | ✅ Bearer Token |
| **DELETE** | `/api/tolerancias/{id}` | Eliminar tolerancia | ✅ Bearer Token |

### **Filtros Disponibles:**
- `?tipo_vehiculo_id={id}` - Filtrar por tipo de vehículo
- `?minutos_min={numero}` - Tolerancia mínima en minutos
- `?minutos_max={numero}` - Tolerancia máxima en minutos  
- `?search={texto}` - Buscar por nombre de tipo de vehículo
- `?page={numero}` - Página específica (paginación)
- `?per_page={numero}` - Elementos por página (máx. 100)

---

## 📋 **CRUD Básico**

### **1. Listar Tolerancias (GET)**
```http
GET /api/tolerancias?page=1&per_page=15
Authorization: Bearer {token}
```

**Parámetros de filtrado:**
- `tipo_vehiculo_id` - Filtrar por tipo de vehículo específico
- `minutos_min` - Tolerancia mínima en minutos
- `minutos_max` - Tolerancia máxima en minutos
- `search` - Buscar por nombre de tipo de vehículo

**Ejemplos con filtros:**
```http
GET /api/tolerancias?tipo_vehiculo_id=6
GET /api/tolerancias?minutos_min=10&minutos_max=30
GET /api/tolerancias?search=auto
```

### **2. Crear Tolerancia (POST)**
```http
POST /api/tolerancias
Authorization: Bearer {token}
Content-Type: application/json

{
    "minutos": 15,
    "tipo_vehiculo_id": 6
}
```

**Ejemplo de Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Tolerancia creada exitosamente",
    "data": {
        "id": 7,
        "minutos": 15,
        "tipo_vehiculo_id": 6,
        "created_at": "2025-09-25T23:45:19.000000Z",
        "updated_at": "2025-09-25T23:45:19.000000Z",
        "tipo_vehiculo": {
            "id": 6,
            "nombre": "Auto",
            "valor": 3
        },
        "tiempo_formateado": "15 minutos",
        "descripcion_completa": "Auto: 15 minutos de tolerancia"
    }
}
```

### **3. Ver Tolerancia Específica (GET)**
```http
GET /api/tolerancias/{id}
Authorization: Bearer {token}
```

### **4. Actualizar Tolerancia (PUT)**
```http
PUT /api/tolerancias/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "minutos": 20,
    "tipo_vehiculo_id": 6
}
```

**Ejemplo de Actualización:**
```http
PUT /api/tolerancias/7
Authorization: Bearer {token}
Content-Type: application/json

{
    "minutos": 40,
    "tipo_vehiculo_id": 6
}
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Tolerancia actualizada exitosamente",
    "data": {
        "id": 7,
        "minutos": 40,
        "tipo_vehiculo_id": 6,
        "created_at": "2025-09-25T23:45:19.000000Z",
        "updated_at": "2025-09-25T23:47:22.000000Z",
        "tipo_vehiculo": {
            "id": 6,
            "nombre": "Auto",
            "valor": 3
        },
        "tiempo_formateado": "40 minutos",
        "descripcion_completa": "Auto: 40 minutos de tolerancia"
    }
}
```

### **5. Eliminar Tolerancia (DELETE)**
```http
DELETE /api/tolerancias/{id}
Authorization: Bearer {token}
```

**Ejemplo de Eliminación:**
```http
DELETE /api/tolerancias/7
Authorization: Bearer {token}
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Tolerancia eliminada exitosamente"
}
```

**Verificación de Eliminación (404):**
```http
GET /api/tolerancias/7
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
    "success": false,
    "message": "Tolerancia no encontrada"
}
```

---

## 🎯 **Validaciones Implementadas:**

### **Crear/Actualizar Tolerancia:**
- `minutos`: Requerido, entero, rango 1-120 minutos (1 minuto a 2 horas)
- `tipo_vehiculo_id`: Requerido, debe existir en tipo_vehiculos, único (un tipo = una tolerancia)

### **Reglas de Negocio:**
- Cada tipo de vehículo puede tener solo una tolerancia
- Los minutos deben estar en el rango de 1 a 120
- No se permite duplicar tolerancias para el mismo tipo

---

## 📊 **Ejemplos de Respuestas JSON:**

### **1. Listar Tolerancias (con tipo de vehículo):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "minutos": 15,
            "tipo_vehiculo_id": 6,
            "tiempo_formateado": "15 minutos",
            "tipo_vehiculo": {
                "id": 6,
                "nombre": "Auto",
                "valor": 25.50
            },
            "descripcion_completa": "Auto: 15 minutos de tolerancia",
            "created_at": "2025-09-25 21:45:00",
            "updated_at": "2025-09-25 21:45:00"
        },
        {
            "id": 2,
            "minutos": 30,
            "tipo_vehiculo_id": 2,
            "tiempo_formateado": "30 minutos",
            "tipo_vehiculo": {
                "id": 2,
                "nombre": "Bicicleta",
                "valor": 5.00
            },
            "descripcion_completa": "Bicicleta: 30 minutos de tolerancia",
            "created_at": "2025-09-25 21:50:00",
            "updated_at": "2025-09-25 21:50:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 2,
        "last_page": 1
    }
}
```

### **2. Crear Tolerancia:**
```json
{
    "success": true,
    "message": "Tolerancia creada exitosamente",
    "data": {
        "id": 3,
        "minutos": 10,
        "tipo_vehiculo_id": 7,
        "tiempo_formateado": "10 minutos",
        "tipo_vehiculo": {
            "id": 7,
            "nombre": "Camioneta",
            "valor": 30.00
        },
        "descripcion_completa": "Camioneta: 10 minutos de tolerancia",
        "created_at": "2025-09-25 22:30:00",
        "updated_at": "2025-09-25 22:30:00"
    }
}
```

### **3. Ver Tolerancia Específica:**
```json
{
    "success": true,
    "data": {
        "id": 7,
        "minutos": 20,
        "tipo_vehiculo_id": 7,
        "created_at": "2025-09-25T23:45:19.000000Z",
        "updated_at": "2025-09-25T23:45:19.000000Z",
        "tipo_vehiculo": {
            "id": 7,
            "nombre": "Camioneta",
            "valor": 4.5
        },
        "tiempo_formateado": "20 minutos",
        "descripcion_completa": "Camioneta: 20 minutos de tolerancia"
    }
}
```

### **4. Error de Validación (Duplicado):**
```json
{
    "success": false,
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "tipo_vehiculo_id": ["Ya existe una tolerancia para este tipo de vehículo"],
        "minutos": ["Los minutos deben estar entre 1 y 1440"]
    }
}
```

### **5. Error de Validación (Datos Inválidos):**
```json
{
    "success": false,
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "minutos": [
            "Los minutos de tolerancia son obligatorios",
            "Los minutos deben ser un número entero",
            "Los minutos deben ser al menos 1"
        ],
        "tipo_vehiculo_id": [
            "El tipo de vehículo es obligatorio",
            "El tipo de vehículo seleccionado no existe"
        ]
    }
}
```

### **6. Error 404 (Tolerancia No Encontrada):**
```json
{
    "success": false,
    "message": "Tolerancia no encontrada"
}
```

---

## 🔧 **Archivos del Sistema:**

### **1. Modelo:**
- `app/Models/Tolerancia.php` - Modelo con relación a tipo de vehículo

### **2. Controlador:**
- `app/Http/Controllers/ToleranciaController.php` - CRUD completo con filtros

### **3. Form Requests:**
- `app/Http/Requests/StoreToleranciaRequest.php` - Validaciones para crear
- `app/Http/Requests/UpdateToleranciaRequest.php` - Validaciones para actualizar

### **4. Resource:**
- `app/Http/Resources/ToleranciaResource.php` - Formato JSON con tipo de vehículo

### **5. Migración:**
- `create_tolerancia_table.php` - Estructura con constraint único

---

## 📈 **Funcionalidades del Modelo:**

### **Relaciones:**
- `tipoVehiculo()` - Relación belongsTo con tipo_vehiculos

### **Métodos Útiles:**
- `getTiempoFormateadoAttribute()` - Formato "X minutos"
- `getDescripcionCompletaAttribute()` - Tipo: X minutos de tolerancia
- `esValida()` - Verificar si está en rango válido
- `tiempoEnHoras()` - Convertir minutos a horas decimales
- `aplicarTolerancia($tiempoBase)` - Sumar tolerancia a tiempo base

### **Scopes de Consulta:**
- `porTipoVehiculo($tipoId)` - Filtrar por tipo de vehículo
- `enRangoMinutos($min, $max)` - Filtrar por rango de minutos
- `mayorA($minutos)` - Tolerancias mayores a X minutos
- `menorA($minutos)` - Tolerancias menores a X minutos

---

## ⏰ **Rangos de Tolerancia por Tipo:**

### **Configuraciones Típicas:**
- **Bicicleta**: 30-60 minutos (mayor tolerancia)
- **Auto**: 10-20 minutos (tolerancia estándar)
- **Camioneta**: 5-15 minutos (menor tolerancia)
- **Couster**: 5-10 minutos (tolerancia mínima)

### **Casos de Uso:**
- **Control de tiempo** en estacionamientos
- **Cálculo de multas** por exceso
- **Configuración flexible** por tipo de vehículo
- **Gestión automática** de tiempos de gracia

---

## 🧪 **Ejemplos Prácticos con Thunder Client:**

### **🔐 Paso 1: Autenticación**
```http
POST http://127.0.0.1:8000/api/auth/login
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
        "user": {
            "id": 1,
            "name": "Administrador",
            "email": "admin@gmail.com"
        },
        "access_token": "46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08",
        "token_type": "Bearer"
    }
}
```

---

### **📝 Paso 2: Crear Tolerancias**

#### **Crear Tolerancia para Auto (15 minutos):**
```http
POST http://127.0.0.1:8000/api/tolerancias
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
Content-Type: application/json

{
    "minutos": 15,
    "tipo_vehiculo_id": 6
}
```

#### **Crear Tolerancia para Bicicleta (45 minutos):**
```http
POST http://127.0.0.1:8000/api/tolerancias
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
Content-Type: application/json

{
    "minutos": 45,
    "tipo_vehiculo_id": 2
}
```

#### **Crear Tolerancia para Camioneta (20 minutos):**
```http
POST http://127.0.0.1:8000/api/tolerancias
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
Content-Type: application/json

{
    "minutos": 20,
    "tipo_vehiculo_id": 7
}
```

---

### **📋 Paso 3: Consultar Tolerancias**

#### **Listar Todas las Tolerancias:**
```http
GET http://127.0.0.1:8000/api/tolerancias
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
```

#### **Filtrar por Tipo de Vehículo (Auto):**
```http
GET http://127.0.0.1:8000/api/tolerancias?tipo_vehiculo_id=6
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
```

#### **Filtrar por Rango de Minutos (10-30 minutos):**
```http
GET http://127.0.0.1:8000/api/tolerancias?minutos_min=10&minutos_max=30
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
```

#### **Buscar por Nombre de Tipo:**
```http
GET http://127.0.0.1:8000/api/tolerancias?search=bicicleta
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
```

---

### **✏️ Paso 4: Actualizar Tolerancia**

```http
PUT http://127.0.0.1:8000/api/tolerancias/1
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
Content-Type: application/json

{
    "minutos": 25,
    "tipo_vehiculo_id": 6
}
```

---

### **🗑️ Paso 5: Eliminar Tolerancia**

```http
DELETE http://127.0.0.1:8000/api/tolerancias/1
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
```

---

### **⚠️ Paso 6: Probar Validaciones**

#### **Error por Tipo Duplicado:**
```http
POST http://127.0.0.1:8000/api/tolerancias
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
Content-Type: application/json

{
    "minutos": 30,
    "tipo_vehiculo_id": 6
}
```

**Respuesta de Error:**
```json
{
    "success": false,
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "tipo_vehiculo_id": ["Ya existe una tolerancia para este tipo de vehículo"]
    }
}
```

#### **Error por Minutos Inválidos:**
```http
POST http://127.0.0.1:8000/api/tolerancias
Authorization: Bearer 46|uJKB4PvVJpbWgOEWrAjPPgMwXi0kkSmBUPBW8JRDd6599f08
Content-Type: application/json

{
    "minutos": 200,
    "tipo_vehiculo_id": 8
}
```

**Respuesta de Error:**
```json
{
    "success": false,
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "minutos": ["Los minutos no pueden exceder 1440 (24 horas)"]
    }
}
```

---

## ✅ **Estado Actual - Verificado y Funcional:**

✅ **Tabla `tolerancia` creada** con constraint único en tipo_vehiculo_id  
✅ **CRUD completo implementado** (5 endpoints probados exitosamente)  
✅ **Validaciones de rango** (1-1440 minutos = 24 horas máximo)  
✅ **Unicidad por tipo** de vehículo garantizada y validada  
✅ **Relación con tipos** de vehículo funcionando con eager loading  
✅ **Filtros especializados** por minutos, tipo y búsqueda  
✅ **Formateo automático** de tiempo ("X minutos")  
✅ **Métodos de cálculo** y descripción completa incluidos  
✅ **Autenticación** Bearer Token requerida y funcionando  
✅ **Resource mejorado** con nombre de tipo de vehículo incluido  
✅ **Múltiples registros de prueba** creados y funcionando  
✅ **Todos los endpoints probados** con token real del usuario  
✅ **Paginación automática** configurada (15 elementos por página)  
✅ **Manejo de errores** implementado con mensajes personalizados  

---

## 🎉 **¡Sistema de Tolerancias Completamente Implementado!**

### **🔍 Características Destacadas:**
- ⏱️ **Gestión precisa de tiempos** con validaciones robustas
- 🎯 **Unicidad garantizada** por tipo de vehículo
- 📊 **Rangos configurables** de 1 a 120 minutos
- 🔄 **Flexibilidad total** para diferentes tipos
- 🧮 **Cálculos automáticos** y formateo de tiempo
- 🔍 **Filtrado especializado** por rangos y tipos
- 📱 **API RESTful** siguiendo mejores prácticas
- 🛡️ **Seguridad robusta** con autenticación requerida

### **🎯 Casos de Uso:**
1. **Configuración de tiempos** de gracia por tipo
2. **Control automático** de estacionamientos
3. **Cálculo de multas** por exceso de tiempo
4. **Gestión diferenciada** según tipo de vehículo
5. **Reportes de tolerancia** y cumplimiento

**¡El sistema de tolerancias está listo y completamente funcional!** 🎯
