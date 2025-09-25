# 🧪 Test de API - Crear Usuario con Relaciones

## 📝 **JSON de Prueba**
```json
{
    "name": "Juan Pérez",
    "email": "juan@cochera.com",
    "password": "password123",
    "categoria": "Administrativo",
    "idrol": 7,
    "id_company": 10
}
```

## 📋 **Datos de Referencia**

### **Roles Disponibles:**
- ID 6: Operador de Salida
- ID 7: Vigilante de Seguridad  
- ID 8: Mantenimiento

### **Companies Disponibles:**
- ID 9: Alanis y Batista S.L.U.
- ID 10: Guevara, Archuleta y Bustamant...
- ID 11: Tech Solutions S.A.

## 🔗 **Endpoint:**
```
POST /api/users
Authorization: Bearer {tu_token}
Content-Type: application/json
```

## ✅ **Respuesta Esperada:**
```json
{
    "success": true,
    "message": "Usuario creado exitosamente",
    "data": {
        "id": 104,
        "name": "Juan Pérez",
        "email": "juan@cochera.com",
        "categoria": "Administrativo",
        "idrol": 7,
        "id_company": 10,
        "role": {
            "id": 7,
            "descripcion": "Vigilante de Seguridad"
        },
        "company": {
            "id": 10,
            "nombre": "Guevara, Archuleta y Bustamant...",
            "ubicacion": "Paseo Haro, 661, Bajo 9º, 94381, O..."
        },
        "email_verified_at": null,
        "created_at": "2025-09-25 17:30:00",
        "updated_at": "2025-09-25 17:30:00"
    }
}
```

## 🚀 **Instrucciones para Probar:**

1. **Obtener Token de Autenticación:**
   ```
   POST /api/auth/login
   {
       "email": "admin@gmail.com",
       "password": "password"
   }
   ```

2. **Crear Usuario con Thunder Client/Postman:**
   - URL: `http://127.0.0.1:8000/api/users`
   - Method: `POST`
   - Headers: `Authorization: Bearer {token}`
   - Body: JSON del paso 1

3. **Verificar Usuario Creado:**
   ```
   GET /api/users/{id}
   ```

---

**¡Los cambios ya están aplicados! Ahora la API debería funcionar correctamente con las nuevas columnas.** 🎉
