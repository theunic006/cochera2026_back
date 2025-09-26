# 🚗 **CRUD Vehículos y Propietarios - Sistema Completo de Gestión**

## ✅ **Resumen de lo Implementado:**

**¡Sistema CRUD completo para gestión de vehículos, propietarios, relaciones y tolerancias con validaciones robustas!**

### 📊 **Estructura de las Bases de Datos:**

#### **1. Tabla `propietarios`:**
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

#### **2. Tabla `vehiculos`:**
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `placa` - VARCHAR(15) UNIQUE NOT NULL
  - `modelo` - VARCHAR(50) NULLABLE
  - `marca` - VARCHAR(50) NULLABLE
  - `color` - VARCHAR(30) NULLABLE
  - `anio` - YEAR NOT NULL
  - `tipo_vehiculo_id` - FK → tipo_vehiculos(id)
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

#### **3. Tabla `vehiculo_propietario` (Intermedia):**
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `vehiculo_id` - FK → vehiculos(id)
  - `propietario_id` - FK → propietarios(id)
  - `fecha_inicio` - DATE NOT NULL
  - `fecha_fin` - DATE NULLABLE
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

#### **4. Tabla `tolerancia`:**
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `minutos` - INT NOT NULL
  - `tipo_vehiculo_id` - FK → tipo_vehiculos(id) UNIQUE
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

### 🎯 **Funcionalidades Principales:**
- ✅ **CRUD completo** para todas las entidades (Crear, Leer, Actualizar, Eliminar)
- ✅ **Relaciones Many-to-Many** entre vehículos y propietarios
- ✅ **Gestión de tolerancias** por tipo de vehículo
- ✅ **Validaciones robustas** con Form Requests personalizados
- ✅ **Filtrado y búsqueda avanzada** en todos los endpoints
- ✅ **Paginación** automática para mejor rendimiento
- ✅ **Relaciones cargadas** (eager loading) para optimización
- ✅ **Autenticación requerida** para todas las operaciones

---

## 🚀 **APIs Disponibles (Requieren autenticación)**

### **Base URL:** `http://127.0.0.1:8000/api/`

---

## 👥 **CRUD PROPIETARIOS**

### **1. Listar Propietarios (GET)**
```http
GET /api/propietarios?page=1&per_page=15
Authorization: Bearer {token}
```

**Parámetros de filtrado:**
- `search` - Buscar en nombres, apellidos, documento o email
- `documento` - Filtrar por documento específico

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

## 🚗 **CRUD VEHÍCULOS**

### **1. Listar Vehículos (GET)**
```http
GET /api/vehiculos?page=1&per_page=15
Authorization: Bearer {token}
```

**Parámetros de filtrado:**
- `search` - Buscar en placa, marca, modelo o color
- `placa` - Filtrar por placa específica
- `tipo_vehiculo_id` - Filtrar por tipo de vehículo
- `marca` - Filtrar por marca específica

### **2. Crear Vehículo (POST)**
```http
POST /api/vehiculos
Authorization: Bearer {token}
Content-Type: application/json

{
    "placa": "ABC123",
    "marca": "Toyota",
    "modelo": "Corolla",
    "color": "Blanco",
    "anio": 2020,
    "tipo_vehiculo_id": 6
}
```

### **3. Ver Vehículo Específico (GET)**
```http
GET /api/vehiculos/{id}
Authorization: Bearer {token}
```

### **4. Actualizar Vehículo (PUT)**
```http
PUT /api/vehiculos/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "placa": "ABC123",
    "marca": "Toyota",
    "modelo": "Corolla 2021",
    "color": "Blanco Perla",
    "anio": 2021,
    "tipo_vehiculo_id": 6
}
```

### **5. Eliminar Vehículo (DELETE)**
```http
DELETE /api/vehiculos/{id}
Authorization: Bearer {token}
```

---

## 🔗 **CRUD RELACIONES VEHÍCULO-PROPIETARIO**

### **1. Listar Relaciones (GET)**
```http
GET /api/vehiculo-propietarios?page=1&per_page=15
Authorization: Bearer {token}
```

**Parámetros de filtrado:**
- `vehiculo_id` - Filtrar por vehículo específico
- `propietario_id` - Filtrar por propietario específico
- `activa` - Solo relaciones activas (sin fecha_fin)
- `fecha_inicio` - Filtrar por fecha de inicio

### **2. Crear Relación (POST)**
```http
POST /api/vehiculo-propietarios
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
GET /api/vehiculo-propietarios/{id}
Authorization: Bearer {token}
```

### **4. Actualizar Relación (PUT)**
```http
PUT /api/vehiculo-propietarios/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "fecha_fin": "2025-12-31"
}
```

### **5. Eliminar Relación (DELETE)**
```http
DELETE /api/vehiculo-propietarios/{id}
Authorization: Bearer {token}
```

---

## ⏱️ **CRUD TOLERANCIAS**

### **1. Listar Tolerancias (GET)**
```http
GET /api/tolerancias?page=1&per_page=15
Authorization: Bearer {token}
```

**Parámetros de filtrado:**
- `tipo_vehiculo_id` - Filtrar por tipo de vehículo
- `minutos` - Filtrar por minutos específicos
- `mayor_a` - Tolerancias mayores a X minutos
- `menor_a` - Tolerancias menores a X minutos

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
    "minutos": 20
}
```

### **5. Eliminar Tolerancia (DELETE)**
```http
DELETE /api/tolerancias/{id}
Authorization: Bearer {token}
```

---

## 📝 **Archivos Creados/Modificados:**

### **1. Modelos:**
- `app/Models/Propietario.php` - Modelo con relaciones many-to-many
- `app/Models/Vehiculo.php` - Modelo con relaciones y tipos de vehículo
- `app/Models/VehiculoPropietario.php` - Modelo pivot personalizado
- `app/Models/Tolerancia.php` - Modelo con relación a tipos de vehículo

### **2. Controladores:**
- `app/Http/Controllers/PropietarioController.php` - CRUD completo con filtros
- `app/Http/Controllers/VehiculoController.php` - CRUD completo con relaciones
- `app/Http/Controllers/VehiculoPropietarioController.php` - Gestión de relaciones
- `app/Http/Controllers/ToleranciaController.php` - CRUD de tolerancias

### **3. Form Requests:**
- `app/Http/Requests/StorePropietarioRequest.php` - Validaciones para crear propietario
- `app/Http/Requests/UpdatePropietarioRequest.php` - Validaciones para actualizar propietario
- `app/Http/Requests/StoreVehiculoRequest.php` - Validaciones para crear vehículo
- `app/Http/Requests/UpdateVehiculoRequest.php` - Validaciones para actualizar vehículo
- `app/Http/Requests/StoreVehiculoPropietarioRequest.php` - Validaciones para relaciones
- `app/Http/Requests/UpdateVehiculoPropietarioRequest.php` - Validaciones para actualizar relaciones
- `app/Http/Requests/StoreToleranciaRequest.php` - Validaciones para tolerancias
- `app/Http/Requests/UpdateToleranciaRequest.php` - Validaciones para actualizar tolerancias

### **4. Resources:**
- `app/Http/Resources/PropietarioResource.php` - Formato JSON para propietarios
- `app/Http/Resources/VehiculoResource.php` - Formato JSON para vehículos
- `app/Http/Resources/VehiculoPropietarioResource.php` - Formato JSON para relaciones
- `app/Http/Resources/ToleranciaResource.php` - Formato JSON para tolerancias

### **5. Rutas:**
- `routes/api.php` - Rutas protegidas para todos los CRUDs

### **6. Migraciones:**
- `create_propietarios_table.php` - Tabla propietarios
- `add_columns_to_propietarios_table.php` - Columnas faltantes en propietarios
- `create_vehiculos_table.php` - Tabla vehículos
- `fix_vehiculos_table.php` - Correcciones en tabla vehículos
- `create_vehiculo_propietario_table.php` - Tabla intermedia
- `create_tolerancia_table.php` - Tabla tolerancias

---

## 🎯 **Validaciones Implementadas:**

### **Crear Propietario:**
- `nombres`: Requerido, máximo 100 caracteres
- `apellidos`: Requerido, máximo 100 caracteres
- `documento`: Requerido, único, máximo 20 caracteres, solo números
- `telefono`: Opcional, máximo 15 caracteres
- `email`: Requerido, único, formato email válido
- `direccion`: Opcional, texto

### **Crear Vehículo:**
- `placa`: Requerido, único, máximo 10 caracteres, formato alfanumérico con guiones
- `marca`: Requerido, máximo 50 caracteres
- `modelo`: Requerido, máximo 50 caracteres
- `color`: Requerido, máximo 30 caracteres
- `anio`: Requerido, entero, rango 1900 hasta año siguiente
- `tipo_vehiculo_id`: Requerido, debe existir en tipo_vehiculos

### **Crear Relación Vehículo-Propietario:**
- `vehiculo_id`: Requerido, debe existir en vehiculos
- `propietario_id`: Requerido, debe existir en propietarios
- `fecha_inicio`: Requerido, fecha válida, no puede ser futura
- `fecha_fin`: Opcional, fecha válida, debe ser posterior a fecha_inicio

### **Crear Tolerancia:**
- `minutos`: Requerido, entero, rango 1-1440 (24 horas máximo)
- `tipo_vehiculo_id`: Requerido, debe existir en tipo_vehiculos, único

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

### **2. Crear Vehículo (con relación tipo_vehiculo):**
```json
{
    "success": true,
    "message": "Vehículo creado exitosamente",
    "data": {
        "id": 5,
        "placa": "XYZ789",
        "marca": "Honda",
        "modelo": "Civic",
        "color": "Azul",
        "anio": 2019,
        "tipo_vehiculo_id": 6,
        "tipo_vehiculo": {
            "id": 6,
            "nombre": "Auto",
            "valor": 25.50
        },
        "descripcion_completa": "XYZ789 - Honda Civic (Azul)",
        "created_at": "2025-09-25 21:45:00",
        "updated_at": "2025-09-25 21:45:00"
    }
}
```

### **3. Crear Relación Vehículo-Propietario:**
```json
{
    "success": true,
    "message": "Relación vehículo-propietario creada exitosamente",
    "data": {
        "id": 2,
        "vehiculo_id": 1,
        "propietario_id": 2,
        "fecha_inicio": "2025-01-15",
        "fecha_fin": null,
        "vehiculo": {
            "id": 1,
            "placa": "ABC123",
            "marca": "Toyota",
            "modelo": "Corolla"
        },
        "propietario": {
            "id": 2,
            "nombres": "María Elena",
            "apellidos": "González Rivera",
            "documento": "87654321"
        },
        "descripcion_completa": "ABC123 - María Elena González Rivera (desde 15/01/2025 - ACTIVA)",
        "created_at": "2025-09-25 21:50:00",
        "updated_at": "2025-09-25 21:50:00"
    }
}
```

### **4. Crear Tolerancia:**
```json
{
    "success": true,
    "message": "Tolerancia creada exitosamente",
    "data": {
        "id": 2,
        "minutos": 20,
        "tipo_vehiculo_id": 7,
        "tipo_vehiculo": {
            "id": 7,
            "nombre": "Camioneta",
            "valor": 35.00
        },
        "minutos_formateados": "20 minutos",
        "tiempo_formateado": "20 minutos",
        "descripcion_completa": "20 minutos para Camioneta",
        "created_at": "2025-09-25 21:55:00",
        "updated_at": "2025-09-25 21:55:00"
    }
}
```

---

## 🔧 **Tabla de Rutas Completa:**

### **Propietarios:**
| Método | Endpoint | Función | Autenticación |
|--------|----------|---------|---------------|
| `GET` | `/api/propietarios` | Listar propietarios | ✅ Bearer Token |
| `POST` | `/api/propietarios` | Crear propietario | ✅ Bearer Token |
| `GET` | `/api/propietarios/{id}` | Ver propietario específico | ✅ Bearer Token |
| `PUT` | `/api/propietarios/{id}` | Actualizar propietario | ✅ Bearer Token |
| `DELETE` | `/api/propietarios/{id}` | Eliminar propietario | ✅ Bearer Token |

### **Vehículos:**
| Método | Endpoint | Función | Autenticación |
|--------|----------|---------|---------------|
| `GET` | `/api/vehiculos` | Listar vehículos | ✅ Bearer Token |
| `POST` | `/api/vehiculos` | Crear vehículo | ✅ Bearer Token |
| `GET` | `/api/vehiculos/{id}` | Ver vehículo específico | ✅ Bearer Token |
| `PUT` | `/api/vehiculos/{id}` | Actualizar vehículo | ✅ Bearer Token |
| `DELETE` | `/api/vehiculos/{id}` | Eliminar vehículo | ✅ Bearer Token |

### **Relaciones Vehículo-Propietario:**
| Método | Endpoint | Función | Autenticación |
|--------|----------|---------|---------------|
| `GET` | `/api/vehiculo-propietarios` | Listar relaciones | ✅ Bearer Token |
| `POST` | `/api/vehiculo-propietarios` | Crear relación | ✅ Bearer Token |
| `GET` | `/api/vehiculo-propietarios/{id}` | Ver relación específica | ✅ Bearer Token |
| `PUT` | `/api/vehiculo-propietarios/{id}` | Actualizar relación | ✅ Bearer Token |
| `DELETE` | `/api/vehiculo-propietarios/{id}` | Eliminar relación | ✅ Bearer Token |

### **Tolerancias:**
| Método | Endpoint | Función | Autenticación |
|--------|----------|---------|---------------|
| `GET` | `/api/tolerancias` | Listar tolerancias | ✅ Bearer Token |
| `POST` | `/api/tolerancias` | Crear tolerancia | ✅ Bearer Token |
| `GET` | `/api/tolerancias/{id}` | Ver tolerancia específica | ✅ Bearer Token |
| `PUT` | `/api/tolerancias/{id}` | Actualizar tolerancia | ✅ Bearer Token |
| `DELETE` | `/api/tolerancias/{id}` | Eliminar tolerancia | ✅ Bearer Token |

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

### **Paso 2: Crear Propietario**
```
POST http://127.0.0.1:8000/api/propietarios
Authorization: Bearer {token_obtenido}
{
    "nombres": "Carlos Alberto",
    "apellidos": "Rodríguez Martín",
    "documento": "11223344",
    "telefono": "555-9012",
    "email": "carlos.rodriguez@test.com",
    "direccion": "Carrera 456 #78-90"
}
```

### **Paso 3: Crear Vehículo**
```
POST http://127.0.0.1:8000/api/vehiculos
Authorization: Bearer {token}
{
    "placa": "DEF456",
    "marca": "Nissan",
    "modelo": "Sentra",
    "color": "Gris",
    "anio": 2022,
    "tipo_vehiculo_id": 6
}
```

### **Paso 4: Crear Relación**
```
POST http://127.0.0.1:8000/api/vehiculo-propietarios
Authorization: Bearer {token}
{
    "vehiculo_id": 1,
    "propietario_id": 1,
    "fecha_inicio": "2025-01-01"
}
```

### **Paso 5: Crear Tolerancia**
```
POST http://127.0.0.1:8000/api/tolerancias
Authorization: Bearer {token}
{
    "minutos": 10,
    "tipo_vehiculo_id": 2
}
```

---

## 🔍 **Funciones de Búsqueda y Filtrado:**

### **Propietarios:**
```http
# Buscar por nombres, apellidos, documento o email
GET /api/propietarios?search=juan

# Filtrar por documento específico
GET /api/propietarios?documento=12345678
```

### **Vehículos:**
```http
# Buscar por placa, marca, modelo o color
GET /api/vehiculos?search=toyota

# Filtrar por tipo de vehículo
GET /api/vehiculos?tipo_vehiculo_id=6

# Filtrar por marca
GET /api/vehiculos?marca=honda
```

### **Relaciones Vehículo-Propietario:**
```http
# Solo relaciones activas
GET /api/vehiculo-propietarios?activa=1

# Por vehículo específico
GET /api/vehiculo-propietarios?vehiculo_id=1

# Por propietario específico
GET /api/vehiculo-propietarios?propietario_id=2
```

### **Tolerancias:**
```http
# Por tipo de vehículo
GET /api/tolerancias?tipo_vehiculo_id=6

# Tolerancias mayores a 15 minutos
GET /api/tolerancias?mayor_a=15

# Tolerancias menores a 30 minutos
GET /api/tolerancias?menor_a=30
```

---

## ✅ **Estado Actual:**

✅ **4 tablas creadas** (propietarios, vehiculos, vehiculo_propietario, tolerancia)  
✅ **Estructura corregida** (columnas faltantes agregadas)  
✅ **Modelos con relaciones** many-to-many implementadas  
✅ **20 endpoints API** completamente funcionales  
✅ **Form Requests** con validaciones robustas  
✅ **Resources** para respuestas JSON consistentes  
✅ **Controladores** con filtrado y paginación  
✅ **Datos de prueba** creados y funcionando  
✅ **Rutas protegidas** con autenticación Bearer  
✅ **Migraciones ejecutadas** exitosamente  

---

## 🎉 **¡Sistema Completo de Gestión Vehicular Implementado!**

### **🔍 Características Destacadas:**
- 🚗 **Gestión completa de vehículos** con tipos y tolerancias
- 👥 **Registro de propietarios** con validación de documentos únicos
- 🔗 **Relaciones temporales** vehículo-propietario con fechas de vigencia
- ⏱️ **Sistema de tolerancias** personalizable por tipo de vehículo
- 🔐 **Seguridad robusta** con autenticación en todos los endpoints
- 📊 **Filtrado avanzado** y búsqueda en tiempo real
- 🚀 **Optimización de consultas** con eager loading
- 📱 **APIs RESTful** siguiendo mejores prácticas
- 🛡️ **Validaciones exhaustivas** para integridad de datos
- 📖 **Documentación completa** para desarrollo y testing

### **🎯 Casos de Uso Principales:**
1. **Registro de propietarios** con documentación completa
2. **Gestión de flota vehicular** con tipos diferenciados
3. **Control de propiedad temporal** para vehículos compartidos
4. **Configuración de tolerancias** por categoría de vehículo
5. **Búsquedas rápidas** por cualquier criterio relevante
6. **Historial de propietarios** por vehículo
7. **Gestión de múltiples vehículos** por propietario

### **🚀 Listo para Producción:**
**¡Tu sistema de gestión vehicular está completamente implementado, documentado y listo para usar!**

---

## 📞 **Información de Contacto del Sistema:**

### **🔑 Credenciales de Prueba:**
- **Usuario:** `admin@gmail.com`
- **Password:** `12345678`

### **📊 Estado Actual de la BD:**
- **Propietarios:** 3 registros de prueba
- **Vehículos:** 3 registros de prueba
- **Relaciones Activas:** 1 relación vigente
- **Tolerancias:** 1 configuración (Auto - 15 minutos)
- **Tipos de Vehículo:** 5 tipos disponibles

**Usa Thunder Client, Postman o cualquier cliente HTTP para probar todas las funcionalidades** 🎯

---

## 🛠️ **Próximos Pasos Sugeridos:**

1. **Implementar endpoints de estadísticas** (propietarios más activos, vehículos por año, etc.)
2. **Agregar filtros de fecha** para relaciones históricas
3. **Crear reportes en PDF** de propietarios y vehículos
4. **Implementar notificaciones** para vencimiento de relaciones
5. **Agregar validación de placas** por región/país
6. **Crear dashboard administrativo** con métricas en tiempo real

**¡El sistema está listo para crecer según tus necesidades específicas!** 🚀
