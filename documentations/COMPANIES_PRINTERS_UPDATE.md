# CONFIGURACIÓN DE IMPRESORAS EN COMPANIES

## 📄 Nuevos campos agregados

Se han agregado dos nuevos campos a la tabla `companies` para gestionar impresoras:

- **`imp_input`**: Nombre de la impresora para tickets de entrada
- **`imp_output`**: Nombre de la impresora para tickets de salida

## 🔧 Campos actualizados

### Model: `Company.php`
✅ Agregados en `$fillable`: `imp_input`, `imp_output`

### Requests: `StoreCompanyRequest.php` y `UpdateCompanyRequest.php`
✅ Validaciones agregadas:
- `imp_input`: nullable|string|max:255
- `imp_output`: nullable|string|max:255

### Resource: `CompanyResource.php`
✅ Campos agregados en respuesta API:
- `imp_input`: Nombre directo de impresora
- `imp_output`: Nombre directo de impresora
- `printer_info`: Información adicional sobre impresoras

## 📋 Ejemplos de uso

### 1. Crear company con impresoras
```json
POST /api/companies
{
    "nombre": "Cochera Central",
    "ubicacion": "Lima Centro",
    "capacidad": 50,
    "descripcion": "Cochera principal",
    "estado": "activo",
    "imp_input": "HP LaserJet Pro",
    "imp_output": "Epson TM-T20"
}
```

### 2. Actualizar solo las impresoras
```json
PUT /api/companies/1
{
    "imp_input": "Canon PIXMA",
    "imp_output": "Brother HL-L2350DW"
}
```

### 3. Respuesta de la API
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nombre": "Cochera Central",
        "ubicacion": "Lima Centro",
        "logo": null,
        "capacidad": 50,
        "descripcion": "Cochera principal",
        "estado": "activo",
        "imp_input": "Canon PIXMA",
        "imp_output": "Brother HL-L2350DW",
        "estado_info": {
            "label": "Activo",
            "is_active": true,
            "is_suspended": false
        },
        "printer_info": {
            "input_printer": "Canon PIXMA",
            "output_printer": "Brother HL-L2350DW",
            "has_input_printer": true,
            "has_output_printer": true
        },
        "users_count": 5,
        "created_at": "2025-10-11 01:30:00",
        "updated_at": "2025-10-11 01:45:00"
    }
}
```

## 🔗 Integración con sistema de impresoras

### Flujo recomendado:

1. **Obtener impresoras disponibles**:
   ```
   GET /api/printers
   ```

2. **Configurar impresoras en company**:
   ```
   PUT /api/companies/{id}
   {
       "imp_input": "nombre_impresora_entrada",
       "imp_output": "nombre_impresora_salida"
   }
   ```

3. **Usar en tickets de ingreso**:
   - El sistema puede usar `imp_input` de la company del usuario
   - Fallback a impresora por defecto si no está configurada

## 📝 Validaciones implementadas

### Campos opcionales
- Ambos campos son **nullable** (pueden estar vacíos)
- Máximo 255 caracteres cada uno
- Deben ser strings válidos

### Mensajes de error personalizados
- `imp_input.string`: "La impresora de entrada debe ser una cadena de texto"
- `imp_input.max`: "El nombre de la impresora de entrada no puede tener más de 255 caracteres"
- `imp_output.string`: "La impresora de salida debe ser una cadena de texto"  
- `imp_output.max`: "El nombre de la impresora de salida no puede tener más de 255 caracteres"

## 🧪 Testing en Postman

### Headers necesarios:
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

### Casos de prueba:

**1. Crear company con impresoras:**
```
POST http://localhost:8000/api/companies
Body: {json con imp_input e imp_output}
```

**2. Actualizar solo impresoras:**
```
PUT http://localhost:8000/api/companies/1
Body: {"imp_input": "Nueva impresora", "imp_output": "Otra impresora"}
```

**3. Obtener company con info de impresoras:**
```
GET http://localhost:8000/api/companies/1
```

## ✅ Verificaciones

- [ ] Model Company actualizado con fillable
- [ ] Requests con validaciones para imp_input e imp_output
- [ ] Resource incluyendo campos de impresoras
- [ ] Mensajes de validación personalizados
- [ ] Documentación actualizada

## 🔮 Próximos pasos sugeridos

1. **Integrar con IngresoController**: Usar `imp_input` de la company del usuario autenticado
2. **Crear endpoint de configuración**: Endpoint específico para configurar impresoras
3. **Validación avanzada**: Verificar que las impresoras existan en el sistema
4. **Histórico de cambios**: Log de cambios en configuración de impresoras
