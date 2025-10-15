# Test del endpoint de registro de empresas

## Datos de prueba para Postman o curl

### Endpoint público de registro
```
POST http://127.0.0.1:8000/api/companies/register
Content-Type: multipart/form-data
```

### Datos de prueba (FormData):
```json
{
    "nombre": "Empresa de Prueba",
    "ubicacion": "Lima, Perú",
    "capacidad": 50,
    "descripcion": "Empresa de prueba para el sistema de cochera",
    "logo": [archivo de imagen opcional]
}
```

### Ejemplo con curl:
```bash
curl -X POST http://127.0.0.1:8000/api/companies/register \
  -F "nombre=Empresa de Prueba" \
  -F "ubicacion=Lima, Perú" \
  -F "capacidad=50" \
  -F "descripcion=Empresa de prueba para el sistema de cochera"
```

### Respuesta esperada:
```json
{
  "success": true,
  "message": "Empresa registrada exitosamente",
  "data": {
    "company": {
      "id": 1,
      "nombre": "Empresa de Prueba",
      "ubicacion": "Lima, Perú",
      "capacidad": 50,
      "descripcion": "Empresa de prueba para el sistema de cochera",
      "estado": "ACTIVO",
      "logo": null,
      "created_at": "2025-10-15 10:30:00",
      "updated_at": "2025-10-15 10:30:00"
    },
    "admin_credentials": {
      "email": "admin@empresa-de-prueba.com",
      "password": "12345678",
      "name": "Admin Empresa de Prueba",
      "role": "Administrador"
    }
  }
}
```

### Validaciones implementadas:
- ✅ Nombre obligatorio y único
- ✅ Ubicación opcional (máximo 255 caracteres)
- ✅ Logo opcional (JPG, JPEG, PNG, GIF, máximo 2MB)
- ✅ Capacidad opcional (mínimo 1)
- ✅ Descripción opcional (máximo 1000 caracteres)

### Características del endpoint:
- ✅ No requiere autenticación
- ✅ Crea empresa automáticamente
- ✅ Crea usuario administrador automáticamente
- ✅ Crea tipo de vehículo "Nuevo" por defecto
- ✅ Estado inicial: ACTIVO
- ✅ Devuelve credenciales de acceso

### Errores posibles:
- 422: Errores de validación
- 500: Error interno del servidor
- 409: Nombre de empresa ya existe

## Endpoint protegido (para comparación)
```
POST http://127.0.0.1:8000/api/companies
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

Este endpoint requiere autenticación y es para uso interno del sistema.
