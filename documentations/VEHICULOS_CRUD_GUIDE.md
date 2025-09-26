# 🚗 **CRUD Vehículos - Sistema de Gestión Vehicular**

## ✅ **Resumen de lo Implementado:**

**¡Sistema CRUD completo para gestión de vehículos con tipos diferenciados y relaciones con propietarios!**

### 📊 **Estructura de la Base de Datos:**
- **Tabla:** `vehiculos`
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

### 🎯 **Funcionalidades Principales:**
- ✅ **CRUD completo** (Crear, Leer, Actualizar, Eliminar)
- ✅ **Validación de placas únicas** con formato específico
- ✅ **Relación con tipos de vehículo** (Auto, Bicicleta, Camioneta, etc.)
- ✅ **Búsqueda avanzada** por placa, marca, modelo o color
- ✅ **Filtrado por tipo** de vehículo y marca
- ✅ **Paginación automática** (15 elementos por página)
- ✅ **Relaciones con propietarios** (many-to-many)
- ✅ **Autenticación Bearer Token** requerida

---

## 🚀 **APIs Disponibles (Requieren autenticación)**

### **Base URL:** `http://127.0.0.1:8000/api/vehiculos`

---

## 📋 **CRUD Básico**

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

**Ejemplo con filtros:**
```http
GET /api/vehiculos?search=toyota&page=1
GET /api/vehiculos?tipo_vehiculo_id=6&marca=honda
```

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

## 🎯 **Validaciones Implementadas:**

### **Crear Vehículo:**
- `placa`: Requerido, único, máximo 10 caracteres, formato alfanumérico con guiones
- `marca`: Requerido, máximo 50 caracteres
- `modelo`: Requerido, máximo 50 caracteres
- `color`: Requerido, máximo 30 caracteres
- `anio`: Requerido, entero, rango 1900 hasta año siguiente (2026)
- `tipo_vehiculo_id`: Requerido, debe existir en tipo_vehiculos

### **Actualizar Vehículo:**
- Mismas validaciones que crear
- `placa` debe seguir siendo única al actualizar

---

## 📊 **Ejemplos de Respuestas JSON:**

### **1. Listar Vehículos (con tipo de vehículo):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "placa": "ABC123",
            "marca": "Toyota",
            "modelo": "Corolla",
            "color": "Blanco",
            "anio": 2020,
            "tipo_vehiculo_id": 6,
            "tipo_vehiculo": {
                "id": 6,
                "nombre": "Auto",
                "valor": 25.50
            },
            "descripcion_completa": "ABC123 - Toyota Corolla (Blanco)",
            "created_at": "2025-09-25 21:45:00",
            "updated_at": "2025-09-25 21:45:00"
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

### **2. Crear Vehículo:**
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
        "created_at": "2025-09-25 22:30:00",
        "updated_at": "2025-09-25 22:30:00"
    }
}
```

### **3. Error de Validación:**
```json
{
    "success": false,
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "placa": ["Ya existe un vehículo con esta placa"],
        "anio": ["El año no puede ser mayor al próximo año"],
        "tipo_vehiculo_id": ["El tipo de vehículo seleccionado no existe"]
    }
}
```

---

## 🔧 **Archivos del Sistema:**

### **1. Modelo:**
- `app/Models/Vehiculo.php` - Modelo con relaciones many-to-many con propietarios

### **2. Controlador:**
- `app/Http/Controllers/VehiculoController.php` - CRUD completo con filtros y relaciones

### **3. Form Requests:**
- `app/Http/Requests/StoreVehiculoRequest.php` - Validaciones para crear
- `app/Http/Requests/UpdateVehiculoRequest.php` - Validaciones para actualizar

### **4. Resource:**
- `app/Http/Resources/VehiculoResource.php` - Formato JSON con tipo de vehículo

### **5. Migraciones:**
- `create_vehiculos_table.php` - Estructura inicial
- `fix_vehiculos_table.php` - Correcciones (año y nombre de columna FK)

---

## 📈 **Funcionalidades del Modelo:**

### **Relaciones:**
- `tipoVehiculo()` - Relación belongsTo con tipo_vehiculos
- `propietarios()` - Relación many-to-many con propietarios
- `propietariosActivos()` - Solo propietarios con relación vigente
- `vehiculoPropietarios()` - Registros de la tabla intermedia

### **Métodos Útiles:**
- `getPlacaFormateadaAttribute()` - Placa en mayúsculas
- `getDescripcionCompletaAttribute()` - Placa + marca + modelo + color
- `tieneTipoVehiculo()` - Verificar si tiene tipo asignado
- `tienePropietario()` - Verificar si tiene propietario actual
- `propietarioActual()` - Obtener propietario vigente

### **Scopes de Consulta:**
- `byPlaca($placa)` - Filtrar por placa
- `byMarca($marca)` - Filtrar por marca
- `byModelo($modelo)` - Filtrar por modelo
- `byColor($color)` - Filtrar por color
- `byTipoVehiculo($tipoId)` - Filtrar por tipo de vehículo

---

## 🚙 **Tipos de Vehículo Disponibles:**

Los vehículos se categorizan según la tabla `tipo_vehiculos`:

- **Auto** (ID: 6) - Vehículos particulares
- **Bicicleta** (ID: 2) - Bicicletas convencionales
- **Camioneta** (ID: 7) - Vehículos utilitarios
- **Couster** (ID: 8) - Vehículos de transporte
- **Default** (ID: 1) - Tipo genérico

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

### **Paso 2: Crear Vehículo**
```
POST http://127.0.0.1:8000/api/vehiculos
Authorization: Bearer {token_obtenido}
{
    "placa": "DEF456",
    "marca": "Nissan",
    "modelo": "Sentra",
    "color": "Gris",
    "anio": 2022,
    "tipo_vehiculo_id": 6
}
```

### **Paso 3: Buscar Vehículos**
```
GET http://127.0.0.1:8000/api/vehiculos?search=nissan
Authorization: Bearer {token}
```

### **Paso 4: Filtrar por Tipo**
```
GET http://127.0.0.1:8000/api/vehiculos?tipo_vehiculo_id=6
Authorization: Bearer {token}
```

---

## ✅ **Estado Actual:**

✅ **Tabla `vehiculos` creada** con estructura corregida  
✅ **CRUD completo implementado** (5 endpoints)  
✅ **Validaciones robustas** incluyendo formato de placa  
✅ **Relación con tipos de vehículo** funcionando  
✅ **Búsqueda y filtrado** múltiple implementado  
✅ **Relaciones con propietarios** configuradas  
✅ **Paginación automática** para mejor rendimiento  
✅ **Eager loading** de tipo de vehículo incluido  
✅ **Autenticación** Bearer Token requerida  
✅ **3 registros de prueba** creados y funcionando  

---

## 🎉 **¡Sistema de Vehículos Completamente Implementado!**

### **🔍 Características Destacadas:**
- 🚗 **Gestión completa de vehículos** con todos los datos relevantes
- 🏷️ **Categorización por tipos** con diferentes configuraciones
- 🔐 **Validación de placas únicas** con formato específico
- 📅 **Control de años** con rangos válidos (1900-2026)
- 🔍 **Búsqueda inteligente** en múltiples campos
- 👥 **Integración con propietarios** para gestión completa
- 📱 **API RESTful** siguiendo mejores prácticas
- 🛡️ **Seguridad robusta** con autenticación requerida

### **🎯 Casos de Uso:**
1. **Registro de vehículos** con categorización
2. **Búsqueda rápida** por placa, marca o modelo
3. **Filtrado por tipo** para diferentes tarifas
4. **Asignación a propietarios** con fechas de vigencia
5. **Control de unicidad** de placas

**¡El sistema de vehículos está listo y completamente funcional!** 🎯
