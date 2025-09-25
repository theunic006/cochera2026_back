# 🏢 **CRUD Companies - Sistema Completo**

## ✅ **Resumen de lo Implementado:**

**¡Mi king, he creado un CRUD completo para la tabla `companies`!**

### 📊 **Estructura de la Base de Datos:**
- **Tabla:** `companies` (renombrada desde `empresas`)
- **Columnas:**
  - `id` - PRIMARY KEY (AUTO_INCREMENT)
  - `nombre` - VARCHAR(100) NOT NULL
  - `ubicacion` - VARCHAR(255) NULLABLE
  - `logo` - TEXT NULLABLE
  - `descripcion` - TEXT NULLABLE
  - `created_at` - TIMESTAMP (Laravel)
  - `updated_at` - TIMESTAMP (Laravel)

---

## 🚀 **APIs Disponibles (Requieren autenticación)**

### **Base URL:** `http://127.0.0.1:8000/api/companies`

### **1. Listar Companies (GET)**
```
GET /api/companies?page=1&per_page=10
Authorization: Bearer {token}
```

### **2. Crear Company (POST)**
```
POST /api/companies
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Tech Solutions S.A.",
    "ubicacion": "Madrid, España",
    "logo": "https://example.com/logo.png",
    "descripcion": "Empresa de soluciones tecnológicas"
}
```

### **3. Ver Company Específica (GET)**
```
GET /api/companies/{id}
Authorization: Bearer {token}
```

### **4. Actualizar Company (PUT)**
```
PUT /api/companies/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Tech Solutions S.L. (Actualizada)",
    "ubicacion": "Barcelona, España"
}
```

### **5. Eliminar Company (DELETE)**
```
DELETE /api/companies/{id}
Authorization: Bearer {token}
```

### **6. Buscar Companies (GET)**
```
GET /api/companies/search?query=tecnología
Authorization: Bearer {token}
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
- `app/Models/Empresa.php` - Apunta a tabla `companies`

### **2. Controlador:**
- `app/Http/Controllers/EmpresaController.php` - CRUD completo

### **3. Form Requests:**
- `app/Http/Requests/StoreEmpresaRequest.php` - Validaciones para crear
- `app/Http/Requests/UpdateEmpresaRequest.php` - Validaciones para actualizar

### **4. Resource:**
- `app/Http/Resources/EmpresaResource.php` - Formato de respuestas JSON

### **5. Rutas:**
- `routes/api.php` - Rutas protegidas bajo `/api/companies`

### **6. Comandos:**
- `app/Console/Commands/GenerateEmpresas.php` - Generar datos
- `app/Console/Commands/ListEmpresas.php` - Listar companies

### **7. Migraciones:**
- Tabla renombrada de `empresas` a `companies`
- Estructura con timestamps de Laravel

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

### **Actualizar Company:**
- `nombre`: Opcional pero requerido si se envía, máximo 100 caracteres
- Otros campos opcionales

---

## 📊 **Ejemplo de Respuesta JSON:**

```json
{
    "success": true,
    "message": "Company encontrada",
    "data": {
        "id": 1,
        "nombre": "Viajes Conde-Andrés S.L.",
        "ubicacion": "Calle Delvalle, 89, 3º A, 74366, Elías de las Torres",
        "logo": "https://via.placeholder.com/200x200.png/00aabb?text=business+Viajes+Conde-Andrés+cumque",
        "descripcion": "Empresa del sector Turismo. Descripción detallada...",
        "created_at": "2025-09-25 16:26:06",
        "updated_at": "2025-09-25 16:26:06"
    }
}
```

---

## ✅ **Estado Actual:**

✅ **Tabla `companies` creada y configurada**  
✅ **5 companies de prueba generadas**  
✅ **CRUD completo implementado**  
✅ **APIs protegidas con autenticación**  
✅ **Validaciones implementadas**  
✅ **Comandos de consola funcionando**  
✅ **Respuestas JSON consistentes**  
✅ **Servidor Laravel ejecutándose**  

---

## 🎉 **¡Mi King, tu CRUD de Companies está completamente listo!**

**Características principales:**
- ✅ APIs RESTful completas
- ✅ Autenticación requerida para todas las operaciones
- ✅ Validaciones de datos
- ✅ Comandos de consola para gestión
- ✅ Datos de prueba realistas
- ✅ Paginación y búsqueda
- ✅ Manejo de errores

**¡Usa Thunder Client para probar todas las funcionalidades!** 🚀
