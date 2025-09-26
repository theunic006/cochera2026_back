# 🚗 **CRUD Tipos de Vehículo - Sistema Completo**

## ✅ **Resumen de lo Implementado:**

**¡Sistema CRUD completo para `tipo_vehiculos` con validaciones avanzadas y filtrado especializado!**

### 📊 **Estructura de la Base de Datos:**
- **Tabla:** `tipo_vehiculos`
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `nombre` - VARCHAR(50) NOT NULL UNIQUE (Nombre del tipo de vehículo)
  - `valor` - FLOAT NULL (Valor asociado al tipo de vehículo)
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

### 🎯 **Funcionalidades Principales:**
- ✅ **CRUD completo** (Crear, Leer, Actualizar, Eliminar)
- ✅ **Validación de unicidad** en nombres de tipos de vehículo
- ✅ **Búsqueda por nombre** con coincidencias parciales
- ✅ **Filtrado por valor** (con valor definido, por rango)
- ✅ **Validaciones robustas** en todos los endpoints
- ✅ **Autenticación requerida** para todas las operaciones
- ✅ **Formateo de respuestas** con información adicional

---

## 🚀 **APIs Disponibles (Requieren autenticación)**

### **Base URL:** `http://127.0.0.1:8000/api/tipo-vehiculos`

---

## 📋 **CRUD Básico**

### **1. Listar Tipos de Vehículo (GET)**
```http
GET /api/tipo-vehiculos?page=1&per_page=10
Authorization: Bearer {token}
```

### **2. Crear Tipo de Vehículo (POST)**
```http
POST /api/tipo-vehiculos
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Automóvil",
    "valor": 15.50
}
```

**📝 Campos:**
- `nombre` (requerido): Nombre único del tipo de vehículo (máx. 50 caracteres)
- `valor` (opcional): Valor numérico asociado (no puede ser negativo)

### **3. Ver Tipo de Vehículo Específico (GET)**
```http
GET /api/tipo-vehiculos/{id}
Authorization: Bearer {token}
```

### **4. Actualizar Tipo de Vehículo (PUT)**
```http
PUT /api/tipo-vehiculos/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Automóvil Compacto",
    "valor": 18.00
}
```

### **5. Eliminar Tipo de Vehículo (DELETE)**
```http
DELETE /api/tipo-vehiculos/{id}
Authorization: Bearer {token}
```

---

## 🔍 **APIs de Búsqueda y Filtrado**

### **6. Buscar por Nombre**
```http
GET /api/tipo-vehiculos/search?query=auto&per_page=10
Authorization: Bearer {token}
```

### **7. Tipos con Valor Definido**
```http
GET /api/tipo-vehiculos/con-valor?per_page=10
Authorization: Bearer {token}
```

### **8. Filtrar por Rango de Valor**
```http
GET /api/tipo-vehiculos/rango-valor?min=10&max=30&per_page=10
Authorization: Bearer {token}
```

**📌 Parámetros para rango de valor:**
- `min` (opcional): Valor mínimo
- `max` (opcional): Valor máximo
- Se puede usar solo `min`, solo `max`, o ambos

---

## 📊 **Ejemplos de Respuestas JSON:**

### **1. Listar Tipos de Vehículo:**
```json
{
    "success": true,
    "message": "Tipos de vehículo obtenidos exitosamente",
    "data": [
        {
            "id": 1,
            "nombre": "Automóvil",
            "nombre_formateado": "Automóvil",
            "valor": 15.5,
            "valor_formateado": "15.50",
            "tiene_valor": true,
            "created_at": "2025-09-25 21:30:00",
            "updated_at": "2025-09-25 21:30:00"
        },
        {
            "id": 2,
            "nombre": "Motocicleta",
            "nombre_formateado": "Motocicleta",
            "valor": 8.0,
            "valor_formateado": "8.00",
            "tiene_valor": true,
            "created_at": "2025-09-25 21:31:00",
            "updated_at": "2025-09-25 21:31:00"
        },
        {
            "id": 3,
            "nombre": "Bicicleta",
            "nombre_formateado": "Bicicleta",
            "valor": null,
            "valor_formateado": "Sin valor",
            "tiene_valor": false,
            "created_at": "2025-09-25 21:32:00",
            "updated_at": "2025-09-25 21:32:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 1,
        "per_page": 10,
        "total": 3,
        "from": 1,
        "to": 3
    }
}
```

### **2. Crear Tipo de Vehículo:**
```json
{
    "success": true,
    "message": "Tipo de vehículo creado exitosamente",
    "data": {
        "id": 4,
        "nombre": "Camión",
        "nombre_formateado": "Camión",
        "valor": 25.0,
        "valor_formateado": "25.00",
        "tiene_valor": true,
        "created_at": "2025-09-25 21:35:00",
        "updated_at": "2025-09-25 21:35:00"
    }
}
```

### **3. Buscar por Nombre:**
```json
{
    "success": true,
    "message": "Resultados de búsqueda para: auto",
    "data": [
        {
            "id": 1,
            "nombre": "Automóvil",
            "nombre_formateado": "Automóvil",
            "valor": 15.5,
            "valor_formateado": "15.50",
            "tiene_valor": true,
            "created_at": "2025-09-25 21:30:00",
            "updated_at": "2025-09-25 21:30:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 1,
        "per_page": 10,
        "total": 1,
        "from": 1,
        "to": 1
    }
}
```

### **4. Filtrar por Rango de Valor:**
```json
{
    "success": true,
    "message": "Tipos de vehículo con valor entre 10 y 20 obtenidos exitosamente",
    "data": [
        {
            "id": 1,
            "nombre": "Automóvil",
            "nombre_formateado": "Automóvil",
            "valor": 15.5,
            "valor_formateado": "15.50",
            "tiene_valor": true,
            "created_at": "2025-09-25 21:30:00",
            "updated_at": "2025-09-25 21:30:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 1,
        "per_page": 10,
        "total": 1,
        "from": 1,
        "to": 1
    },
    "filtros": {
        "min": 10,
        "max": 20
    }
}
```

### **5. Eliminar Tipo de Vehículo:**
```json
{
    "success": true,
    "message": "Tipo de vehículo 'Automóvil' eliminado exitosamente",
    "data": null
}
```

---

## 🔧 **Validaciones Implementadas:**

### **Crear Tipo de Vehículo:**
- `nombre`: Requerido, string, máximo 50 caracteres, único en la tabla
- `valor`: Opcional, numérico, no puede ser negativo

### **Actualizar Tipo de Vehículo:**
- `nombre`: Opcional pero requerido si se envía, string, máximo 50 caracteres, único (excluyendo el registro actual)
- `valor`: Opcional, numérico, no puede ser negativo

### **Filtro por Rango de Valor:**
- `min`: Opcional, numérico, no puede ser negativo
- `max`: Opcional, numérico, no puede ser negativo
- Validación: `min` no puede ser mayor que `max`

---

## 📝 **Archivos Creados/Modificados:**

### **1. Modelo:**
- `app/Models/TipoVehiculo.php` - Modelo con scopes y métodos helper

### **2. Controlador:**
- `app/Http/Controllers/TipoVehiculoController.php` - CRUD completo + filtrado especializado

### **3. Form Requests:**
- `app/Http/Requests/StoreTipoVehiculoRequest.php` - Validaciones para crear
- `app/Http/Requests/UpdateTipoVehiculoRequest.php` - Validaciones para actualizar

### **4. Resource:**
- `app/Http/Resources/TipoVehiculoResource.php` - Formato de respuestas JSON

### **5. Rutas:**
- `routes/api.php` - Rutas protegidas bajo `/api/tipo-vehiculos`

### **6. Migración:**
- `database/migrations/create_tipo_vehiculos_table.php` - Tabla con campos nombre y valor

---

## 🔧 **Tabla de Rutas Completa:**

| Método | Endpoint | Función | Autenticación |
|--------|----------|---------|---------------|
| `GET` | `/api/tipo-vehiculos` | Listar tipos de vehículo | ✅ Bearer Token |
| `POST` | `/api/tipo-vehiculos` | Crear tipo de vehículo | ✅ Bearer Token |
| `GET` | `/api/tipo-vehiculos/search` | Buscar por nombre | ✅ Bearer Token |
| `GET` | `/api/tipo-vehiculos/con-valor` | Solo con valor definido | ✅ Bearer Token |
| `GET` | `/api/tipo-vehiculos/rango-valor` | Filtrar por rango de valor | ✅ Bearer Token |
| `GET` | `/api/tipo-vehiculos/{id}` | Ver tipo específico | ✅ Bearer Token |
| `PUT` | `/api/tipo-vehiculos/{id}` | Actualizar tipo | ✅ Bearer Token |
| `DELETE` | `/api/tipo-vehiculos/{id}` | Eliminar tipo | ✅ Bearer Token |

---

## 🧪 **Pruebas con Thunder Client/Postman**

### **Paso 1: Login**
```
POST http://127.0.0.1:8000/api/auth/login
{
    "email": "admin@gmail.com",
    "password": "12345678"
}
```

### **Paso 2: Crear Tipos de Vehículo**
```
POST http://127.0.0.1:8000/api/tipo-vehiculos
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Automóvil",
    "valor": 15.50
}
```

```
POST http://127.0.0.1:8000/api/tipo-vehiculos
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Motocicleta",
    "valor": 8.00
}
```

```
POST http://127.0.0.1:8000/api/tipo-vehiculos
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Bicicleta"
}
```

### **Paso 3: Probar Filtros**
```
GET http://127.0.0.1:8000/api/tipo-vehiculos/con-valor
Authorization: Bearer {token}
```

```
GET http://127.0.0.1:8000/api/tipo-vehiculos/rango-valor?min=5&max=20
Authorization: Bearer {token}
```

```
GET http://127.0.0.1:8000/api/tipo-vehiculos/search?query=moto
Authorization: Bearer {token}
```

---

## ✅ **Estado Actual:**

✅ **Tabla `tipo_vehiculos` creada**  
✅ **CRUD completo implementado**  
✅ **Validaciones robustas**  
✅ **APIs de filtrado especializado**  
✅ **Autenticación requerida**  
✅ **Respuestas JSON consistentes**  
✅ **Documentación completa**  
✅ **Servidor Laravel ejecutándose**  

---

## 🎉 **¡Sistema Completo de Tipos de Vehículo Implementado!**

### **🆕 Funcionalidades Destacadas:**
- 🔍 **Búsqueda inteligente** por nombre con coincidencias parciales
- 💰 **Filtrado por valor** con opciones flexibles (con valor, por rango)
- 🛡️ **Validación de unicidad** para evitar nombres duplicados
- 📊 **Información enriquecida** en respuestas (valor formateado, flags)
- ⚡ **Ordenamiento inteligente** (alfabético para nombres, por valor para filtros)

### **📈 Características técnicas:**
- ✅ **8 endpoints RESTful** completos
- ✅ **Autenticación Bearer Token** requerida
- ✅ **Validaciones de datos robustas**
- ✅ **Manejo de errores completo**
- ✅ **Paginación en todas las consultas**
- ✅ **Scopes Eloquent** para filtrado eficiente
- ✅ **Resource personalizado** para respuestas consistentes
- ✅ **Mensajes de error personalizados**

### **🚀 Listo para producción:**
**¡Tu sistema de gestión de tipos de vehículo está completamente implementado y probado!**

---

## 💡 **Ejemplos Prácticos de Uso:**

### **Flujo Típico: Crear → Listar → Filtrar → Actualizar**

#### **1. Crear varios tipos:**
```bash
# Automóviles
curl -X POST http://127.0.0.1:8000/api/tipo-vehiculos \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Sedán","valor":16.00}'

# Motocicletas  
curl -X POST http://127.0.0.1:8000/api/tipo-vehiculos \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Scooter","valor":6.50}'

# Sin valor definido
curl -X POST http://127.0.0.1:8000/api/tipo-vehiculos \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Patineta"}'
```

#### **2. Usar filtros avanzados:**
```bash
# Solo vehículos motorizados (con valor)
curl -X GET "http://127.0.0.1:8000/api/tipo-vehiculos/con-valor" \
  -H "Authorization: Bearer {token}"

# Vehículos económicos (valor entre 5 y 15)
curl -X GET "http://127.0.0.1:8000/api/tipo-vehiculos/rango-valor?min=5&max=15" \
  -H "Authorization: Bearer {token}"

# Buscar scooters
curl -X GET "http://127.0.0.1:8000/api/tipo-vehiculos/search?query=scoot" \
  -H "Authorization: Bearer {token}"
```

### **💡 Casos de Uso Reales:**
- **Sistema de cocheras:** Diferentes tarifas por tipo de vehículo
- **Aplicación de estacionamiento:** Clasificación y precios dinámicos
- **Gestión de flotas:** Categorización de vehículos empresariales
- **Sistema de multas:** Diferentes sanciones según tipo de vehículo

**¡El CRUD está listo para integrarse con cualquier sistema de gestión vehicular!** 🎯
