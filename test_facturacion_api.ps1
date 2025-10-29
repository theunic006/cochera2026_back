# Script de prueba para la API de Facturacion
# Ejecutar: .\test_facturacion_api.ps1

$baseUrl = "http://localhost:8000"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "PRUEBA DE API DE FACTURACION" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Paso 1: Login
Write-Host "[1] Haciendo login..." -ForegroundColor Yellow
$loginBody = @{
    email = "juan@cochera.com"
    password = "password123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/api/auth/login" -Method POST -ContentType "application/json" -Body $loginBody -ErrorAction Stop

    if ($loginResponse.success -eq $true) {
        $token = $loginResponse.token
        Write-Host "Login exitoso" -ForegroundColor Green
        Write-Host "Token: $token" -ForegroundColor Gray
        Write-Host ""
    } else {
        Write-Host "Login fallo: $($loginResponse.message)" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "Error en login: $_" -ForegroundColor Red
    Write-Host "Detalles: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Paso 2: Probar endpoint de empresas
Write-Host "[2] Probando endpoint GET /apifactura/empresas..." -ForegroundColor Yellow

try {
    $headers = @{
        "Authorization" = "Bearer $token"
        "Accept" = "application/json"
    }

    $empresasResponse = Invoke-RestMethod -Uri "$baseUrl/apifactura/empresas" -Method GET -Headers $headers -ErrorAction Stop

    Write-Host "Endpoint funcionando correctamente" -ForegroundColor Green
    Write-Host ""
    Write-Host "Respuesta:" -ForegroundColor Cyan
    $empresasResponse | ConvertTo-Json -Depth 5

} catch {
    Write-Host "Error al consultar empresas" -ForegroundColor Red
    Write-Host "Detalles: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.Exception.Response) {
        Write-Host "Status Code: $($_.Exception.Response.StatusCode.value__)" -ForegroundColor Red
    }
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "PRUEBA COMPLETADA EXITOSAMENTE" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Puedes usar este token en Postman:" -ForegroundColor Yellow
Write-Host $token -ForegroundColor White
Write-Host ""
Write-Host "Endpoints disponibles:" -ForegroundColor Yellow
Write-Host "- GET    $baseUrl/apifactura/empresas" -ForegroundColor White
Write-Host "- POST   $baseUrl/apifactura/empresas" -ForegroundColor White
Write-Host "- GET    $baseUrl/apifactura/clientes" -ForegroundColor White
Write-Host "- POST   $baseUrl/apifactura/clientes" -ForegroundColor White
Write-Host "- GET    $baseUrl/apifactura/series" -ForegroundColor White
Write-Host "- POST   $baseUrl/apifactura/series" -ForegroundColor White
Write-Host "- GET    $baseUrl/apifactura/comprobantes" -ForegroundColor White
Write-Host "- POST   $baseUrl/apifactura/comprobantes" -ForegroundColor White
Write-Host ""
