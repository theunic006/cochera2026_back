# CONFIGURACIÓN DE CORS Y SANCTUM - COCHERA 2026

## � ÚLTIMA ACTUALIZACIÓN: Soporte para API Facturación

### ⚠️ Error CORS Solucionado
```
Access to XMLHttpRequest at 'http://127.0.0.1:8000/apifactura/empresas/1' 
from origin 'http://localhost:5173' has been blocked by CORS policy
```

**Solución:** Se agregó `'apifactura/*'` a los paths permitidos en CORS.

---

## �🔧 Configuración implementada

### 1. CORS (Cross-Origin Resource Sharing)
Se ha configurado para permitir peticiones desde diferentes dominios y puertos.

**Archivo:** `config/cors.php`
- ✅ Paths: `api/*`, **`apifactura/*`**, y `sanctum/csrf-cookie`
- ✅ Métodos permitidos: `*` (GET, POST, PUT, DELETE, etc.)
- ✅ Orígenes permitidos: Configurables via `.env`
- ✅ Headers permitidos: `*`
- ✅ Soporte para credenciales: `true`
- ✅ Headers expuestos para cliente

**Configuración actual:**
```php
'paths' => ['api/*', 'apifactura/*', 'sanctum/csrf-cookie'],
```

### 2. Laravel Sanctum
Configurado para autenticación de API con tokens.

**Archivo:** `config/sanctum.php`
- ✅ Dominios stateful expandidos
- ✅ Soporte para múltiples puertos de desarrollo
- ✅ Guards configurados correctamente

### 3. Middleware
**Archivo:** `bootstrap/app.php`
- ✅ Sanctum middleware para API
- ✅ CORS middleware agregado
- ✅ Middleware de sesiones para web

### 4. Variables de entorno
**Archivo:** `.env`
```env
# CORS Configuration
CORS_ALLOWED_ORIGINS="http://localhost:3000,http://localhost:3001,http://localhost:4200,http://localhost:5173,http://localhost:8080,http://127.0.0.1:3000,http://127.0.0.1:3001,http://127.0.0.1:4200,http://127.0.0.1:5173,http://127.0.0.1:8080"

# Sanctum Configuration  
SANCTUM_STATEFUL_DOMAINS="localhost,localhost:3000,localhost:3001,localhost:4200,localhost:5173,localhost:8080,127.0.0.1,127.0.0.1:3000,127.0.0.1:3001,127.0.0.1:4200,127.0.0.1:5173,127.0.0.1:8080"

# Session Configuration
SESSION_DRIVER=database
SESSION_DOMAIN=localhost
```

---

## 🚀 Puertos soportados por defecto

- **React/Next.js**: 3000, 3001
- **Angular**: 4200  
- **Vite**: 5173
- **Vue/Nuxt**: 8080
- **Desarrollo local**: 127.0.0.1 en todos los puertos

---

## 📋 Headers necesarios en el frontend

### Para peticiones con Fetch/Axios:
```javascript
const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': 'Bearer ' + token, // Para rutas protegidas
    'X-Requested-With': 'XMLHttpRequest' // Recomendado para SPA
};
```

### Ejemplo con Axios:
```javascript
// Configuración global de Axios
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Para rutas autenticadas
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
```

### Ejemplo con Fetch:
```javascript
fetch('http://localhost:8000/api/printers', {
    method: 'GET',
    credentials: 'include', // Para cookies de sesión
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
        'X-Requested-With': 'XMLHttpRequest'
    }
})
```

---

## 🔍 Testing CORS

### 1. Desde navegador (Console):
```javascript
fetch('http://localhost:8000/api/auth/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'test@example.com',
        password: 'password'
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

### 2. Verificar headers CORS en DevTools:
- Abre Network tab
- Busca la petición OPTIONS (preflight)
- Verifica headers:
  - `Access-Control-Allow-Origin`
  - `Access-Control-Allow-Methods`
  - `Access-Control-Allow-Headers`

---

## 🐛 Solución de problemas

### Error: "CORS policy blocked"
1. Verificar que el dominio esté en `CORS_ALLOWED_ORIGINS`
2. Comprobar que el puerto coincida
3. Verificar headers en la petición

### Error: "Unauthenticated" 
1. Verificar que el token esté en el header `Authorization`
2. Formato: `Bearer token_aquí`
3. Verificar que el token no haya expirado

### Error: "CSRF token mismatch"
1. Solo aplica para sesiones stateful
2. Hacer GET a `/sanctum/csrf-cookie` primero
3. Usar `withCredentials: true` en peticiones

---

## 🔄 Comandos útiles

```bash
# Limpiar cache de configuración
php artisan config:clear

# Limpiar cache de rutas  
php artisan route:clear

# Limpiar todo el cache
php artisan optimize:clear

# Verificar configuración actual
php artisan config:show cors
php artisan config:show sanctum
```

---

## 🌐 Configuración para producción

Para producción, actualizar `.env`:

```env
# Cambiar a dominios específicos
CORS_ALLOWED_ORIGINS="https://miapp.com,https://www.miapp.com"
SANCTUM_STATEFUL_DOMAINS="miapp.com,www.miapp.com"

# URL de la aplicación
APP_URL=https://api.miapp.com

# Sesiones seguras
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

---

## ✅ Checklist de verificación

- [ ] `php artisan serve` ejecutándose
- [ ] Frontend en puerto permitido (3000, 4200, 5173, etc.)
- [ ] Headers correctos en peticiones
- [ ] Token válido para rutas protegidas
- [ ] DevTools sin errores CORS
- [ ] Respuestas JSON correctas
