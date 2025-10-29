# API DE FACTURACIÓN - GUÍA DE USO

## Base URL
```
http://localhost:8000/apifactura
```

## Autenticación
Todas las rutas requieren autenticación mediante Sanctum. Primero debes hacer login:

### 1. Login
```
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
    "email": "tu_email@ejemplo.com",
    "password": "tu_contraseña"
}
```

**Respuesta:**
```json
{
    "success": true,
    "message": "Login exitoso",
    "token": "1|abcdefghijklmnopqrstuvwxyz123456789",
    "user": {...}
}
```

Usa el token en todas las peticiones posteriores:
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456789
```

---

## ENDPOINTS DE EMPRESAS

### 1. Listar todas las empresas
```
GET http://localhost:8000/apifactura/empresas
Authorization: Bearer {token}
```

**Respuesta esperada:**
```json
{
    "success": true,
    "message": "Empresas obtenidas exitosamente",
    "data": [
        {
            "id": 1,
            "ruc": "20123456789",
            "razon_social": "EMPRESA DEMO SAC",
            "nombre_comercial": "DEMO",
            "direccion": "Av. Principal 123",
            "telefono": "987654321",
            "email": "demo@empresa.com",
            "logo": null,
            "estado": true,
            "created_at": "2025-10-26T12:00:00.000000Z",
            "updated_at": "2025-10-26T12:00:00.000000Z"
        }
    ],
    "total": 1
}
```

### 2. Crear una empresa
```
POST http://localhost:8000/apifactura/empresas
Authorization: Bearer {token}
Content-Type: application/json

{
    "ruc": "20123456789",
    "razon_social": "EMPRESA DEMO SAC",
    "nombre_comercial": "DEMO",
    "direccion": "Av. Principal 123",
    "telefono": "987654321",
    "email": "demo@empresa.com",
    "estado": true
}
```

**Respuesta esperada:**
```json
{
    "success": true,
    "message": "Empresa creada exitosamente",
    "data": {
        "id": 1,
        "ruc": "20123456789",
        "razon_social": "EMPRESA DEMO SAC",
        ...
    }
}
```

### 3. Obtener una empresa específica
```
GET http://localhost:8000/apifactura/empresas/1
Authorization: Bearer {token}
```

### 4. Actualizar una empresa
```
PUT http://localhost:8000/apifactura/empresas/1
Authorization: Bearer {token}
Content-Type: application/json

{
    "razon_social": "EMPRESA DEMO ACTUALIZADA SAC",
    "telefono": "999888777"
}
```

### 5. Eliminar una empresa
```
DELETE http://localhost:8000/apifactura/empresas/1
Authorization: Bearer {token}
```

---

## ENDPOINTS DE CLIENTES

### 1. Listar todos los clientes
```
GET http://localhost:8000/apifactura/clientes
Authorization: Bearer {token}
```

### 2. Crear un cliente
```
POST http://localhost:8000/apifactura/clientes
Authorization: Bearer {token}
Content-Type: application/json

{
    "tipo_documento": "DNI",
    "numero_documento": "12345678",
    "nombres": "Juan",
    "apellidos": "Pérez García",
    "razon_social": null,
    "direccion": "Jr. Los Olivos 456",
    "telefono": "987654321",
    "email": "juan@ejemplo.com",
    "estado": true
}
```

### 3. Obtener un cliente específico
```
GET http://localhost:8000/apifactura/clientes/1
Authorization: Bearer {token}
```

### 4. Actualizar un cliente
```
PUT http://localhost:8000/apifactura/clientes/1
Authorization: Bearer {token}
Content-Type: application/json

{
    "telefono": "999888777",
    "email": "nuevo@email.com"
}
```

### 5. Eliminar un cliente
```
DELETE http://localhost:8000/apifactura/clientes/1
Authorization: Bearer {token}
```

---

## ENDPOINTS DE SERIES

### 1. Listar todas las series
```
GET http://localhost:8000/apifactura/series
Authorization: Bearer {token}
```

**Respuesta esperada (incluye relación con empresa):**
```json
{
    "success": true,
    "message": "Series obtenidas exitosamente",
    "data": [
        {
            "id": 1,
            "empresa_id": 1,
            "tipo_comprobante": "FACTURA",
            "serie": "F001",
            "correlativo_actual": 100,
            "estado": true,
            "empresa": {
                "id": 1,
                "ruc": "20123456789",
                "razon_social": "EMPRESA DEMO SAC",
                ...
            }
        }
    ],
    "total": 1
}
```

### 2. Crear una serie
```
POST http://localhost:8000/apifactura/series
Authorization: Bearer {token}
Content-Type: application/json

{
    "empresa_id": 1,
    "tipo_comprobante": "FACTURA",
    "serie": "F001",
    "correlativo_actual": 0,
    "estado": true
}
```

### 3. Obtener una serie específica
```
GET http://localhost:8000/apifactura/series/1
Authorization: Bearer {token}
```

### 4. Actualizar una serie
```
PUT http://localhost:8000/apifactura/series/1
Authorization: Bearer {token}
Content-Type: application/json

{
    "correlativo_actual": 150
}
```

### 5. Eliminar una serie
```
DELETE http://localhost:8000/apifactura/series/1
Authorization: Bearer {token}
```

---

## ENDPOINTS DE COMPROBANTES

### 1. Listar todos los comprobantes
```
GET http://localhost:8000/apifactura/comprobantes
Authorization: Bearer {token}
```

**Respuesta esperada (incluye relaciones con serie, empresa y cliente):**
```json
{
    "success": true,
    "message": "Comprobantes obtenidos exitosamente",
    "data": [
        {
            "id": 1,
            "serie_id": 1,
            "cliente_id": 1,
            "tipo_comprobante": "FACTURA",
            "serie": "F001",
            "correlativo": "00000001",
            "numero_completo": "F001-00000001",
            "fecha_emision": "2025-10-26",
            "fecha_vencimiento": "2025-11-26",
            "moneda": "PEN",
            "tipo_cambio": "1.00",
            "subtotal": "100.00",
            "igv": "18.00",
            "total": "118.00",
            "estado": "EMITIDO",
            "observaciones": null,
            "serie": {
                "id": 1,
                "tipo_comprobante": "FACTURA",
                "serie": "F001",
                "empresa": {
                    "id": 1,
                    "ruc": "20123456789",
                    "razon_social": "EMPRESA DEMO SAC"
                }
            },
            "cliente": {
                "id": 1,
                "numero_documento": "12345678",
                "nombres": "Juan",
                "apellidos": "Pérez García"
            }
        }
    ],
    "total": 1
}
```

### 2. Crear un comprobante
```
POST http://localhost:8000/apifactura/comprobantes
Authorization: Bearer {token}
Content-Type: application/json

{
    "serie_id": 1,
    "cliente_id": 1,
    "tipo_comprobante": "FACTURA",
    "serie": "F001",
    "correlativo": "00000001",
    "numero_completo": "F001-00000001",
    "fecha_emision": "2025-10-26",
    "fecha_vencimiento": "2025-11-26",
    "moneda": "PEN",
    "tipo_cambio": 1.00,
    "subtotal": 100.00,
    "igv": 18.00,
    "total": 118.00,
    "estado": "EMITIDO",
    "observaciones": "Comprobante de prueba"
}
```

### 3. Obtener un comprobante específico
```
GET http://localhost:8000/apifactura/comprobantes/1
Authorization: Bearer {token}
```

### 4. Actualizar un comprobante
```
PUT http://localhost:8000/apifactura/comprobantes/1
Authorization: Bearer {token}
Content-Type: application/json

{
    "estado": "ANULADO",
    "observaciones": "Comprobante anulado por error"
}
```

### 5. Eliminar un comprobante
```
DELETE http://localhost:8000/apifactura/comprobantes/1
Authorization: Bearer {token}
```

---

## CONFIGURACIÓN EN POSTMAN

### Variables de entorno recomendadas:
```
base_url: http://localhost:8000
token: (se obtiene del login)
```

### Headers globales:
```
Authorization: Bearer {{token}}
Content-Type: application/json
Accept: application/json
```

---

## NOTAS IMPORTANTES

1. **Base de datos independiente**: Todos estos endpoints usan la base de datos `factura2026`, completamente separada de `cochera2026`.

2. **Autenticación**: Aunque los endpoints están en `apifactura`, la autenticación sigue siendo la misma del sistema (usa `/api/auth/login`).

3. **Relaciones**:
   - Una Empresa puede tener muchas Series
   - Una Serie pertenece a una Empresa
   - Un Comprobante pertenece a una Serie y a un Cliente
   - Un Comprobante tiene acceso a la Empresa a través de la Serie

4. **Validaciones**:
   - RUC único por empresa
   - Número de documento único por cliente
   - Referencias válidas entre tablas (foreign keys)

---

## PRUEBA RÁPIDA

Para probar rápidamente que todo funciona:

1. Inicia el servidor:
   ```bash
   php artisan serve
   ```

2. Haz login (Postman):
   ```
   POST http://localhost:8000/api/auth/login
   ```

3. Copia el token y prueba listar empresas:
   ```
   GET http://localhost:8000/apifactura/empresas
   Authorization: Bearer {tu_token}
   ```

4. Si la respuesta es exitosa (aunque esté vacía), ¡todo está funcionando correctamente!
