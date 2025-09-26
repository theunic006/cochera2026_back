# 🔗 **CRUD Vehículo-Propietario - Sistema de Relaciones Temporales**

## ✅ **Resumen de lo Implementado:**

**¡Sistema CRUD completo para gestión de relaciones temporales entre vehículos y propietarios!**

### 📊 **Estructura de la Base de Datos:**
- **Tabla:** `vehiculo_propietario`
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `vehiculo_id` - FK → vehiculos(id)
  - `propietario_id` - FK → propietarios(id)
  - `fecha_inicio` - DATE NOT NULL
  - `fecha_fin` - DATE NULLABLE
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

### 🎯 **Funcionalidades Principales:**
- ✅ **CRUD completo** para relaciones vehículo-propietario
- ✅ **Gestión temporal** con fechas de inicio y fin
- ✅ **Validación de solapamientos** de fechas
- ✅ **Consultas por vigencia** (activos/históricos)
- ✅ **Filtrado por vehículo** o propietario específico
- ✅ **Búsqueda por rangos** de fechas
- ✅ **Paginación automática** (15 elementos por página)
- ✅ **Relaciones eager loading** incluidas
- ✅ **Autenticación Bearer Token** requerida

---

## 🚀 **APIs Disponibles (Requieren autenticación)**

### **Base URL:** `http://127.0.0.1:8000/api/vehiculo-propietario`

---

## 📋 **CRUD Básico**

### **1. Listar Relaciones (GET)**
```http
GET /api/vehiculo-propietario?page=1&per_page=15
Authorization: Bearer {token}
```

**Parámetros de filtrado:**
- `vehiculo_id` - Filtrar por vehículo específico
- `propietario_id` - Filtrar por propietario específico
- `activos` - Solo relaciones vigentes (fecha_fin es NULL o futura)
- `historicos` - Solo relaciones finalizadas
- `fecha_desde` - Relaciones desde una fecha específica
- `fecha_hasta` - Relaciones hasta una fecha específica

**Ejemplos con filtros:**
```http
GET /api/vehiculo-propietario?activos=1&vehiculo_id=3
GET /api/vehiculo-propietario?historicos=1&propietario_id=2
GET /api/vehiculo-propietario?fecha_desde=2025-01-01&fecha_hasta=2025-12-31
```

### **2. Crear Relación (POST)**
```http
POST /api/vehiculo-propietario
Authorization: Bearer {token}
Content-Type: application/json

{
    "vehiculo_id": 1,
    "propietario_id": 2,
    "fecha_inicio": "2025-01-15",
    "fecha_fin": null
}
```

### **3. Ver Relación Específica (GET)**
```http
GET /api/vehiculo-propietario/{id}
Authorization: Bearer {token}
```

### **4. Actualizar Relación (PUT)**
```http
PUT /api/vehiculo-propietario/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "vehiculo_id": 1,
    "propietario_id": 2,
    "fecha_inicio": "2025-01-15",
    "fecha_fin": "2025-12-31"
}
```

### **5. Eliminar Relación (DELETE)**
```http
DELETE /api/vehiculo-propietario/{id}
Authorization: Bearer {token}
```

---

## 🎯 **Validaciones Implementadas:**

### **Crear/Actualizar Relación:**
- `vehiculo_id`: Requerido, debe existir en tabla vehiculos
- `propietario_id`: Requerido, debe existir en tabla propietarios
- `fecha_inicio`: Requerido, formato fecha (Y-m-d), no puede ser futura
- `fecha_fin`: Opcional, formato fecha, debe ser posterior a fecha_inicio
- **Validación especial**: No puede haber solapamiento de fechas para el mismo vehículo

### **Reglas de Negocio:**
- Un vehículo puede tener múltiples propietarios en diferentes períodos
- No puede haber solapamiento de fechas para el mismo vehículo
- Si fecha_fin es NULL, la relación está activa
- La fecha_inicio no puede ser mayor a fecha_fin

---

## 📊 **Ejemplos de Respuestas JSON:**

### **1. Listar Relaciones (con vehículo y propietario):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "vehiculo_id": 1,
            "propietario_id": 2,
            "fecha_inicio": "2025-01-15",
            "fecha_fin": null,
            "activo": true,
            "dias_vigencia": 250,
            "vehiculo": {
                "id": 1,
                "placa": "ABC123",
                "marca": "Toyota",
                "modelo": "Corolla",
                "color": "Blanco",
                "anio": 2020
            },
            "propietario": {
                "id": 2,
                "nombres": "Juan Carlos",
                "apellidos": "García López",
                "documento": "12345678",
                "email": "juan@example.com",
                "telefono": "555-0123"
            },
            "created_at": "2025-09-25 21:45:00",
            "updated_at": "2025-09-25 21:45:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 5,
        "last_page": 1
    }
}
```

### **2. Crear Relación:**
```json
{
    "success": true,
    "message": "Relación vehículo-propietario creada exitosamente",
    "data": {
        "id": 6,
        "vehiculo_id": 3,
        "propietario_id": 1,
        "fecha_inicio": "2025-02-01",
        "fecha_fin": "2025-12-31",
        "activo": true,
        "dias_vigencia": 333,
        "vehiculo": {
            "id": 3,
            "placa": "XYZ789",
            "marca": "Honda",
            "modelo": "Civic"
        },
        "propietario": {
            "id": 1,
            "nombres": "María",
            "apellidos": "González",
            "documento": "87654321"
        },
        "created_at": "2025-09-25 22:30:00",
        "updated_at": "2025-09-25 22:30:00"
    }
}
```

### **3. Error de Validación (Solapamiento):**
```json
{
    "success": false,
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "fecha_inicio": ["Ya existe una relación activa para este vehículo en las fechas especificadas"],
        "fecha_fin": ["La fecha fin debe ser posterior a la fecha inicio"]
    }
}
```

---

## 🔧 **Archivos del Sistema:**

### **1. Modelo:**
- `app/Models/VehiculoPropietario.php` - Modelo con relaciones y scopes temporales

### **2. Controlador:**
- `app/Http/Controllers/VehiculoPropietarioController.php` - CRUD con filtros temporales

### **3. Form Requests:**
- `app/Http/Requests/StoreVehiculoPropietarioRequest.php` - Validaciones para crear
- `app/Http/Requests/UpdateVehiculoPropietarioRequest.php` - Validaciones para actualizar

### **4. Resource:**
- `app/Http/Resources/VehiculoPropietarioResource.php` - Formato JSON con relaciones

### **5. Migración:**
- `create_vehiculo_propietario_table.php` - Tabla intermedia con temporal

---

## 📈 **Funcionalidades del Modelo:**

### **Relaciones:**
- `vehiculo()` - Relación belongsTo con vehiculos
- `propietario()` - Relación belongsTo con propietarios

### **Métodos Útiles:**
- `getActivoAttribute()` - Determina si la relación está activa
- `getDiasVigenciaAttribute()` - Calcula días de vigencia
- `getFechaInicioFormateadaAttribute()` - Fecha inicio en formato d/m/Y
- `getFechaFinFormateadaAttribute()` - Fecha fin en formato d/m/Y
- `estaVigente()` - Verificar si la relación está activa
- `finalizarRelacion($fechaFin)` - Finalizar relación con fecha específica

### **Scopes de Consulta:**
- `activos()` - Solo relaciones vigentes (sin fecha_fin o futura)
- `historicos()` - Solo relaciones finalizadas
- `porVehiculo($vehiculoId)` - Filtrar por vehículo
- `porPropietario($propietarioId)` - Filtrar por propietario
- `enRangoFechas($desde, $hasta)` - Filtrar por rango de fechas
- `vigentesEn($fecha)` - Relaciones vigentes en fecha específica

---

## 🕐 **Gestión Temporal:**

### **Estados de Relación:**
- **Activa**: `fecha_fin` es NULL o fecha futura
- **Histórica**: `fecha_fin` es fecha pasada
- **Futura**: `fecha_inicio` es fecha futura

### **Validaciones Temporales:**
- No solapamiento de fechas para el mismo vehículo
- fecha_inicio ≤ fecha_fin
- fecha_inicio no puede ser futura al crear
- Solo una relación activa por vehículo

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

### **Paso 2: Crear Relación Activa**
```
POST http://127.0.0.1:8000/api/vehiculo-propietario
Authorization: Bearer {token_obtenido}
{
    "vehiculo_id": 1,
    "propietario_id": 2,
    "fecha_inicio": "2025-01-15",
    "fecha_fin": null
}
```

### **Paso 3: Listar Relaciones Activas**
```
GET http://127.0.0.1:8000/api/vehiculo-propietario?activos=1
Authorization: Bearer {token}
```

### **Paso 4: Filtrar por Vehículo**
```
GET http://127.0.0.1:8000/api/vehiculo-propietario?vehiculo_id=1
Authorization: Bearer {token}
```

### **Paso 5: Finalizar Relación**
```
PUT http://127.0.0.1:8000/api/vehiculo-propietario/1
Authorization: Bearer {token}
{
    "vehiculo_id": 1,
    "propietario_id": 2,
    "fecha_inicio": "2025-01-15",
    "fecha_fin": "2025-09-30"
}
```

---

## ✅ **Estado Actual:**

✅ **Tabla `vehiculo_propietario` creada** con campos temporales  
✅ **CRUD completo implementado** (5 endpoints)  
✅ **Validaciones temporales** robustas implementadas  
✅ **Filtros por vigencia** (activos/históricos)  
✅ **Prevención de solapamientos** de fechas  
✅ **Relaciones eager loading** con vehículos y propietarios  
✅ **Cálculos automáticos** de días de vigencia  
✅ **Scopes de consulta** especializados  
✅ **Autenticación** Bearer Token requerida  
✅ **3 registros de prueba** creados y funcionando  

---

## 🎉 **¡Sistema de Relaciones Vehículo-Propietario Completamente Implementado!**

### **🔍 Características Destacadas:**
- 🕐 **Gestión temporal completa** con fechas de inicio y fin
- 🚫 **Prevención de conflictos** con validación de solapamientos
- 📊 **Consultas especializadas** por vigencia y rangos
- 🔄 **Historial completo** de propietarios por vehículo
- 📅 **Cálculos automáticos** de períodos de vigencia
- 🔍 **Filtrado avanzado** por múltiples criterios
- 📱 **API RESTful** siguiendo mejores prácticas
- 🛡️ **Seguridad robusta** con autenticación requerida

### **🎯 Casos de Uso:**
1. **Registro de nuevos propietarios** con fecha de inicio
2. **Transferencia de vehículos** finalizando relación anterior
3. **Consulta de historial** de propietarios por vehículo
4. **Control de vigencia** de relaciones actuales
5. **Auditoría temporal** de cambios de propiedad

**¡El sistema de relaciones temporales está listo y completamente funcional!** 🎯
