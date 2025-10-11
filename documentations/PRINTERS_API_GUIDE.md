# ENDPOINTS DE IMPRESORAS - EJEMPLOS PARA POSTMAN

## 🖨️ GESTIÓN DE IMPRESORAS

### 1. Listar todas las impresoras instaladas
```
GET {{base_url}}/api/printers
Authorization: Bearer {{token}}
```

**Respuesta esperada:**
```json
{
    "success": true,
    "message": "Impresoras obtenidas exitosamente",
    "data": [
        {
            "name": "Microsoft Print to PDF",
            "status": "Disponible",
            "type": "Virtual Printer",
            "is_default": false
        },
        {
            "name": "T20",
            "status": "Disponible",
            "driver": "Generic / Text Only",
            "port": "USB001",
            "is_shared": false,
            "is_published": false,
            "type": "Local"
        }
    ],
    "total": 2
}
```

---

### 2. Obtener información de una impresora específica
```
GET {{base_url}}/api/printers/T20
Authorization: Bearer {{token}}
```

**Respuesta esperada:**
```json
{
    "success": true,
    "message": "Impresora encontrada",
    "data": {
        "name": "T20",
        "status": "Disponible",
        "driver": "Generic / Text Only",
        "port": "USB001",
        "is_shared": false,
        "is_published": false,
        "type": "Local"
    }
}
```

---

### 3. Probar conexión con una impresora
```
POST {{base_url}}/api/printers/T20/test
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Respuesta esperada (éxito):**
```json
{
    "success": true,
    "message": "Prueba de impresión exitosa",
    "printer": {
        "name": "T20",
        "status": "Disponible",
        "driver": "Generic / Text Only",
        "port": "USB001",
        "is_shared": false,
        "is_published": false,
        "type": "Local"
    }
}
```

**Respuesta esperada (error):**
```json
{
    "success": false,
    "message": "Error al conectar con la impresora",
    "error": "Failed to open printer T20",
    "printer": {
        "name": "T20",
        "status": "Disponible"
    }
}
```

---

### 4. Probar con impresora que no existe
```
GET {{base_url}}/api/printers/ImpresoraInexistente
Authorization: Bearer {{token}}
```

**Respuesta esperada:**
```json
{
    "success": false,
    "message": "Impresora no encontrada"
}
```

---

## 📋 CONFIGURACIÓN EN POSTMAN

### Variables de entorno recomendadas:
- `base_url`: http://localhost:8000 (o tu URL del servidor)
- `token`: tu_token_de_autenticacion_aqui

### Headers necesarios:
- `Authorization`: Bearer {{token}}
- `Content-Type`: application/json (para POST)
- `Accept`: application/json

---

## 🔧 CASOS DE USO COMUNES

### Flujo completo de selección de impresora:

1. **Paso 1**: Listar impresoras disponibles
   ```
   GET /api/printers
   ```

2. **Paso 2**: Seleccionar una impresora de la lista

3. **Paso 3**: Probar conexión antes de imprimir
   ```
   POST /api/printers/{nombre}/test
   ```

4. **Paso 4**: Si la prueba es exitosa, usar esa impresora para imprimir tickets
   ```
   GET /api/ingresos/{id}/print
   ```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "Impresora no encontrada"
- Verificar que el nombre de la impresora esté bien escrito
- Usar GET /api/printers para ver nombres exactos

### Error: "Failed to open printer"
- La impresora puede estar desconectada
- Verificar drivers de la impresora
- Comprobar que el servicio de Windows Print Spooler esté ejecutándose

### Error: "Error al obtener impresoras"
- Problemas de permisos del sistema
- El usuario de PHP/Apache puede no tener permisos para acceder a WMI
- Verificar que el comando 'wmic' esté disponible

---

## 📝 NOTAS TÉCNICAS

- Los endpoints usan autenticación Sanctum (Bearer token)
- Compatible con impresoras Windows locales y de red
- Soporte para impresoras virtuales (PDF, XPS)
- Fallback a métodos alternativos si WMI falla
- La prueba de impresión envía un ticket básico de prueba
