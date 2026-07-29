@echo off
title Actualizador de M-SportPro
echo ===================================================
echo   ACTUALIZADOR AUTOMATICO DE LA PLATAFORMA M-SportPro
echo ===================================================
echo.

echo [+] Obteniendo ultimos cambios del repositorio (Git Pull)...
git pull
if %ERRORLEVEL% neq 0 (
    echo [!] Error al descargar los cambios de Git.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [+] Instalando/Actualizando dependencias PHP (Composer)...
call composer install --no-interaction --prefer-dist --optimize-autoloader
if %ERRORLEVEL% neq 0 (
    echo [!] Error al instalar las dependencias de Composer.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [+] Instalando/Actualizando dependencias Javascript (NPM)...
call npm install
if %ERRORLEVEL% neq 0 (
    echo [!] Error al instalar dependencias de NPM.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [+] Compilando assets de la interfaz (NPM Build)...
call npm run build
if %ERRORLEVEL% neq 0 (
    echo [!] Error al compilar con Vite.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [+] Ejecutando migraciones de base de datos...
call php artisan migrate --force
if %ERRORLEVEL% neq 0 (
    echo [!] Error al ejecutar las migraciones de base de datos.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [+] Limpiando y optimizando cache de Laravel...
call php artisan optimize:clear

echo.
echo ===================================================
echo   PROCESO TERMINADO - PLATAFORMA ACTUALIZADA CON EXITO
echo ===================================================
echo.
pause
