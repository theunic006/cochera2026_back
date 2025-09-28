# CRUD de Registro

Este documento describe el uso de la API para la gestión de registros en el sistema cochera2026.

## Endpoints

- `GET /api/registros` — Listar registros (paginado)
- `POST /api/registros` — Crear registro
- `GET /api/registros/{id}` — Ver registro específico
- `PUT /api/registros/{id}` — Actualizar registro
- `DELETE /api/registros/{id}` — Eliminar registro

## Campos
- `id_vehiculo` (int, requerido): ID del vehículo
- `id_user` (int, requerido): ID del usuario
- `id_empresa` (int, requerido): ID de la empresa
- `fecha` (datetime, opcional): Fecha del registro

## Ejemplo de creación
```json
{
  "id_vehiculo": 1,
  "id_user": 2,
  "id_empresa": 3,
  "fecha": "2025-09-27 10:00:00"
}
```

## Respuesta
```json
{
  "id": 1,
  "id_vehiculo": 1,
  "id_user": 2,
  "id_empresa": 3,
  "fecha": "2025-09-27 10:00:00"
}
```

## Validaciones
- Todos los IDs deben existir en sus tablas correspondientes.
- La fecha es opcional, por defecto se asigna la actual.

## Notas
- El endpoint soporta paginación con el parámetro `per_page`.
- Requiere autenticación si la API está protegida.
