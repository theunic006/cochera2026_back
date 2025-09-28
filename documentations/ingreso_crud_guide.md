# Guía CRUD de Ingresos

Esta guía describe cómo utilizar los endpoints y recursos relacionados con la entidad **Ingresos** en el sistema.

## Estructura de la tabla `ingresos`

| Campo          | Tipo         | Descripción                |
| -------------- | ------------ | -------------------------- |
| id             | BIGINT(20)   | Clave primaria, autoincrement |
| fecha_ingreso  | DATE         | Fecha del ingreso          |
| hora_ingreso   | TIME         | Hora del ingreso           |
| id_user        | BIGINT(20)   | ID del usuario             |
| id_empresa     | BIGINT(20)   | ID de la empresa           |
| created_at     | TIMESTAMP    | Fecha de creación          |
| updated_at     | TIMESTAMP    | Fecha de actualización     |

## Endpoints API

### Listar ingresos
- **GET** `/api/ingresos`
- Respuesta paginada con todos los ingresos.

### Crear ingreso
- **POST** `/api/ingresos`
- Body JSON:
```json
{
  "fecha_ingreso": "2025-09-27",
  "hora_ingreso": "08:15:00",
  "id_user": 1,
  "id_empresa": 1
}
```

### Ver ingreso específico
- **GET** `/api/ingresos/{id}`

### Actualizar ingreso
- **PUT/PATCH** `/api/ingresos/{id}`
- Body JSON (campos opcionales):
```json
{
  "fecha_ingreso": "2025-09-28",
  "hora_ingreso": "09:00:00"
}
```

### Eliminar ingreso
- **DELETE** `/api/ingresos/{id}`

### Buscar ingresos por fecha
- **GET** `/api/ingresos/search?q=2025-09-27`

## Validaciones
- Todos los campos son requeridos al crear un ingreso.
- Los IDs deben existir en las tablas relacionadas.
- Formato de fecha: `YYYY-MM-DD`. Formato de hora: `HH:MM:SS`.

## Ejemplo de respuesta exitosa
```json
{
  "success": true,
  "message": "Ingreso creado exitosamente",
  "data": {
    "id": 1,
    "fecha_ingreso": "2025-09-27",
    "hora_ingreso": "08:15:00",
    "id_user": 1,
    "id_empresa": 1,
    "created_at": "2025-09-27 08:15:00",
    "updated_at": "2025-09-27 08:15:00"
  }
}
```

## Seeder de ejemplo
Para poblar la tabla con datos ficticios, usa el seeder `IngresoSeeder`.

---
**Actualizado:** 27 de septiembre de 2025
