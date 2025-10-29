# 🔧 CORRECCIÓN DE CLAVES PRIMARIAS - FACTURACIÓN API

## 📝 Cambio Realizado

Las tablas de la base de datos `factura2026` ahora usan **`id`** como clave primaria estándar en lugar de `id_*` (id_empresa, id_cliente, id_serie, id_comprobante).

---

## ✅ Correcciones Aplicadas

### **1. Modelo Serie**

#### ❌ **Antes:**
```php
protected $primaryKey = 'id_serie';

public function empresa()
{
    return $this->belongsTo(Empresa::class, 'id_empresa');
}

public function comprobantes()
{
    return $this->hasMany(Comprobante::class, 'id_serie');
}
```

#### ✅ **Después:**
```php
// Se eliminó la línea protected $primaryKey (ahora usa 'id' por defecto)

public function empresa()
{
    return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
}

public function comprobantes()
{
    return $this->hasMany(Comprobante::class, 'serie', 'id');
}
```

**Explicación:**
- **Eliminado** `protected $primaryKey = 'id_serie'` porque ahora la tabla usa `id`
- **empresa()**: La FK `id_empresa` en tabla `series` apunta a la PK `id` en tabla `empresas`
- **comprobantes()**: La FK `serie` en tabla `comprobantes` apunta a la PK `id` en tabla `series`

---

### **2. Modelo Comprobante**

#### ❌ **Antes:**
```php
public function serie()
{
    return $this->belongsTo(Serie::class, 'id_serie', 'id_serie');
}

public function cliente()
{
    return $this->belongsTo(Cliente::class, 'id_cliente');
}

public function empresa()
{
    return $this->belongsTo(Empresa::class, 'id_empresa');
}
```

#### ✅ **Después:**
```php
public function serie()
{
    return $this->belongsTo(Serie::class, 'serie', 'id');
}

public function cliente()
{
    return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
}

public function empresa()
{
    return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
}
```

**Explicación:**
- **serie()**: La FK `serie` apunta a la PK `id` en tabla `series`
- **cliente()**: La FK `id_cliente` apunta a la PK `id` en tabla `clientes`
- **empresa()**: La FK `id_empresa` apunta a la PK `id` en tabla `empresas`

---

### **3. Modelo Empresa**

#### ❌ **Antes:**
```php
public function series()
{
    return $this->hasMany(Serie::class, 'id_empresa');
}

public function comprobantes()
{
    return $this->hasManyThrough(Comprobante::class, Serie::class, 'id_empresa', 'id_serie');
}
```

#### ✅ **Después:**
```php
public function series()
{
    return $this->hasMany(Serie::class, 'id_empresa', 'id');
}

public function comprobantes()
{
    return $this->hasManyThrough(
        Comprobante::class,
        Serie::class,
        'id_empresa', // FK en tabla series
        'serie',      // FK en tabla comprobantes
        'id',         // PK local en empresas
        'id'          // PK en tabla series
    );
}
```

**Explicación:**
- **series()**: La FK `id_empresa` en tabla `series` apunta a la PK `id` en tabla `empresas`
- **comprobantes()**: Relación a través de `series` con claves explícitas

---

### **4. Modelo Cliente**

#### ❌ **Antes:**
```php
public function comprobantes()
{
    return $this->hasMany(Comprobante::class, 'id_cliente');
}
```

#### ✅ **Después:**
```php
public function comprobantes()
{
    return $this->hasMany(Comprobante::class, 'id_cliente', 'id');
}
```

**Explicación:**
- La FK `id_cliente` en tabla `comprobantes` apunta a la PK `id` en tabla `clientes`

---

### **5. ComprobanteController - Validaciones**

#### ❌ **Antes (store y update):**
```php
'id_cliente' => 'required|exists:factura2026.clientes,id_cliente',
```

#### ✅ **Después:**
```php
'id_cliente' => 'required|exists:factura2026.clientes,id',
```

**Explicación:**
- La validación `exists` ahora verifica contra la columna `id` en lugar de `id_cliente`

---

## 📊 Estructura de Claves Foráneas

### **Tabla: empresas**
- **PK:** `id` (estándar)

### **Tabla: clientes**
- **PK:** `id` (estándar)

### **Tabla: series**
- **PK:** `id` (estándar)
- **FK:** `id_empresa` → `empresas.id`

### **Tabla: comprobantes**
- **PK:** `id` (estándar)
- **FK:** `id_empresa` → `empresas.id`
- **FK:** `id_cliente` → `clientes.id`
- **FK:** `serie` → `series.id` *(relación especial por columna 'serie')*

---

## 🔍 Sintaxis de Relaciones en Laravel

### **belongsTo (Relación Inversa)**
```php
return $this->belongsTo(ModeloPadre::class, 'foreign_key', 'owner_key');
```
- **foreign_key**: Columna FK en esta tabla
- **owner_key**: Columna PK en la tabla padre

### **hasMany (Relación Directa)**
```php
return $this->hasMany(ModeloHijo::class, 'foreign_key', 'local_key');
```
- **foreign_key**: Columna FK en la tabla hija
- **local_key**: Columna PK en esta tabla

### **hasManyThrough (Relación a través de otra tabla)**
```php
return $this->hasManyThrough(
    ModeloFinal::class,
    ModeloIntermedio::class,
    'first_key',  // FK en tabla intermedia
    'second_key', // FK en tabla final
    'local_key',  // PK local
    'third_key'   // PK en tabla intermedia
);
```

---

## ✅ Validación de Cambios

Ejecutar para verificar que no hay errores:

```powershell
php artisan config:clear
php artisan cache:clear
php artisan route:list --path=apifactura
```

---

## 🧪 Pruebas Recomendadas

### **1. Listar con Relaciones**
```
GET /apifactura/empresas/1
GET /apifactura/clientes/1
GET /apifactura/series/1
GET /apifactura/comprobantes/1
```

Verificar que las relaciones (`with`) carguen correctamente:
- Empresa → Series → Comprobantes
- Cliente → Comprobantes
- Serie → Empresa, Comprobantes
- Comprobante → Serie, Cliente, Empresa

### **2. Crear Comprobante**
```json
POST /apifactura/comprobantes
{
    "id_empresa": 1,
    "id_cliente": 1,
    "tipo_comprobante": "01",
    "serie": 1,
    "correlativo": "00000001",
    "fecha_emision": "2025-10-26",
    "moneda": "PEN",
    "total": 118.00
}
```

Verificar que las validaciones `exists` funcionen correctamente.

---

## 📌 Resumen de Errores Corregidos

| **Archivo** | **Error** | **Corrección** |
|-------------|-----------|----------------|
| `Serie.php` | `protected $primaryKey = 'id_serie'` | Eliminado (usa `id` por defecto) |
| `Serie.php` | `belongsTo(Empresa::class, 'id_empresa')` | Agregado segundo parámetro `'id'` |
| `Serie.php` | `hasMany(Comprobante::class, 'id_serie')` | Cambiado a `'serie', 'id'` |
| `Comprobante.php` | `belongsTo(Serie::class, 'id_serie', 'id_serie')` | Cambiado a `'serie', 'id'` |
| `Comprobante.php` | `belongsTo(Cliente::class, 'id_cliente')` | Agregado segundo parámetro `'id'` |
| `Comprobante.php` | `belongsTo(Empresa::class, 'id_empresa')` | Agregado segundo parámetro `'id'` |
| `Empresa.php` | `hasMany(Serie::class, 'id_empresa')` | Agregado segundo parámetro `'id'` |
| `Empresa.php` | `hasManyThrough` con claves incorrectas | Actualizado con 4 parámetros correctos |
| `Cliente.php` | `hasMany(Comprobante::class, 'id_cliente')` | Agregado segundo parámetro `'id'` |
| `ComprobanteController.php` | `exists:factura2026.clientes,id_cliente` | Cambiado a `exists:factura2026.clientes,id` |

---

## 🎉 Estado Final

✅ **Todos los modelos corregidos**  
✅ **Todas las relaciones actualizadas**  
✅ **Validaciones corregidas**  
✅ **Cachés limpiados**  
✅ **Sistema listo para pruebas**
