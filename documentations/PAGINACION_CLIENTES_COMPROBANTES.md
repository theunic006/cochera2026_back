# 📄 PAGINACIÓN EN CLIENTES Y COMPROBANTES

## 🎯 Endpoints con Paginación

Los endpoints de **Clientes** y **Comprobantes** ahora devuelven **15 registros por página** para mejorar el rendimiento.

---

## 📋 Clientes - Endpoint Paginado

### **Listar Clientes (Primera Página)**
```
GET http://localhost:8000/apifactura/clientes
Authorization: Bearer {token}
```

### **Listar Clientes (Página Específica)**
```
GET http://localhost:8000/apifactura/clientes?page=2
Authorization: Bearer {token}
```

### **Respuesta con Paginación:**
```json
{
    "success": true,
    "message": "Clientes obtenidos exitosamente",
    "data": [
        {
            "id_cliente": 15,
            "tipo_documento": "DNI",
            "numero_documento": "12345678",
            "nombres": "Juan",
            "apellidos": "Pérez",
            "razon_social": null,
            "direccion": "Av. Principal 123",
            "telefono": "987654321",
            "descripcion": null,
            "estado": 1,
            "created_at": "2025-10-26T12:00:00.000000Z",
            "updated_at": "2025-10-26T12:00:00.000000Z"
        },
        // ... más clientes (hasta 15)
    ],
    "current_page": 1,
    "per_page": 15,
    "total": 150,
    "last_page": 10,
    "from": 1,
    "to": 15
}
```

### **Información de Paginación:**
- `current_page`: Página actual
- `per_page`: Registros por página (15)
- `total`: Total de registros en la base de datos
- `last_page`: Última página disponible
- `from`: Número del primer registro en la página actual
- `to`: Número del último registro en la página actual

---

## 📋 Comprobantes - Endpoint Paginado

### **Listar Comprobantes (Primera Página)**
```
GET http://localhost:8000/apifactura/comprobantes
Authorization: Bearer {token}
```

### **Listar Comprobantes (Página Específica)**
```
GET http://localhost:8000/apifactura/comprobantes?page=3
Authorization: Bearer {token}
```

### **Respuesta con Paginación:**
```json
{
    "success": true,
    "message": "Comprobantes obtenidos exitosamente",
    "data": [
        {
            "id_comprobante": 100,
            "id_empresa": 1,
            "id_cliente": 5,
            "tipo_comprobante": "01",
            "serie": "F001",
            "correlativo": "00000100",
            "fecha_emision": "2025-10-26",
            "moneda": "PEN",
            "total": "118.00",
            "estado": "EMITIDO",
            "serie": {
                "id_serie": 1,
                "serie": "F001",
                "tipo_comprobante": "01",
                "empresa": {
                    "id_empresa": 1,
                    "ruc": "20123456789",
                    "razon_social": "MI EMPRESA SAC"
                }
            },
            "cliente": {
                "id_cliente": 5,
                "numero_documento": "12345678",
                "nombres": "Juan",
                "apellidos": "Pérez"
            },
            "empresa": {
                "id_empresa": 1,
                "ruc": "20123456789",
                "razon_social": "MI EMPRESA SAC"
            }
        },
        // ... más comprobantes (hasta 15)
    ],
    "current_page": 1,
    "per_page": 15,
    "total": 500,
    "last_page": 34,
    "from": 1,
    "to": 15
}
```

---

## 🔄 Navegación entre Páginas

### **Frontend - Ejemplo de Navegación**

```javascript
// Estado para paginación
const [clientes, setClientes] = useState([]);
const [currentPage, setCurrentPage] = useState(1);
const [totalPages, setTotalPages] = useState(1);
const [total, setTotal] = useState(0);

// Función para obtener clientes
const fetchClientes = async (page = 1) => {
    const response = await fetch(`http://localhost:8000/apifactura/clientes?page=${page}`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    });
    
    const data = await response.json();
    
    if (data.success) {
        setClientes(data.data);
        setCurrentPage(data.current_page);
        setTotalPages(data.last_page);
        setTotal(data.total);
    }
};

// Botones de navegación
<button onClick={() => fetchClientes(currentPage - 1)} disabled={currentPage === 1}>
    Anterior
</button>

<span>Página {currentPage} de {totalPages}</span>

<button onClick={() => fetchClientes(currentPage + 1)} disabled={currentPage === totalPages}>
    Siguiente
</button>

<span>Total de registros: {total}</span>
```

---

## 📝 Ejemplos en Postman

### **Página 1**
```
GET http://localhost:8000/apifactura/clientes
```

### **Página 2**
```
GET http://localhost:8000/apifactura/clientes?page=2
```

### **Página 5**
```
GET http://localhost:8000/apifactura/clientes?page=5
```

---

## ✅ Endpoints SIN Paginación

Los siguientes endpoints **NO tienen paginación** porque usualmente tienen pocos registros:

### **Empresas**
```
GET /apifactura/empresas
```
Devuelve todas las empresas sin paginación.

### **Series**
```
GET /apifactura/series
```
Devuelve todas las series sin paginación.

---

## 🎨 Ejemplo de Interfaz

```
┌─────────────────────────────────────────────────────────┐
│                    LISTA DE CLIENTES                    │
├─────────────────────────────────────────────────────────┤
│ ID │ Documento  │ Nombre              │ Estado          │
├────┼────────────┼─────────────────────┼─────────────────┤
│ 15 │ 12345678   │ Juan Pérez          │ Activo          │
│ 14 │ 87654321   │ María García        │ Activo          │
│ 13 │ 11223344   │ Carlos López        │ Inactivo        │
│ ... (hasta 15 registros)                                │
├─────────────────────────────────────────────────────────┤
│  [<< Anterior]  Página 1 de 10  [Siguiente >>]         │
│  Mostrando 1-15 de 150 registros                        │
└─────────────────────────────────────────────────────────┘
```

---

## 🔧 Personalización

Si deseas cambiar el número de registros por página, puedes modificar el controlador:

```php
// En ClienteController.php o ComprobanteController.php
$clientes = Cliente::orderBy('id_cliente', 'desc')->paginate(20); // 20 por página
```

O permitir que el frontend lo defina:

```php
$perPage = $request->input('per_page', 15); // Default 15
$clientes = Cliente::orderBy('id_cliente', 'desc')->paginate($perPage);
```

Entonces desde el frontend:
```
GET /apifactura/clientes?page=2&per_page=25
```

---

## 📊 Ventajas de la Paginación

✅ **Mejor rendimiento**: Menos datos transferidos
✅ **Carga rápida**: Respuestas más rápidas
✅ **Mejor UX**: Interfaz más fluida
✅ **Escalabilidad**: Maneja miles de registros sin problemas
✅ **Ahorro de recursos**: Menor uso de memoria y ancho de banda

---

## 🎉 ¡Todo Listo!

Ahora los endpoints de **Clientes** y **Comprobantes** devuelven **15 registros por página** con toda la información de paginación necesaria para implementar navegación en tu frontend.
