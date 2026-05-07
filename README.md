# Jackeline FS - Gestión Administrativa

Este proyecto es una modernización del sistema original Jackeline, migrado a **Laravel 12** con un stack moderno basado en **Tailwind CSS**, **Alpine.js** y **Vite**.

## 🚀 Requisitos del Sistema

- **PHP**: 8.2 o superior
- **Composer**
- **Node.js & NPM** (versión LTS recomendada)
- **Servidor de Base de Datos**: MySQL/MariaDB (XAMPP recomendado)

## 🛠️ Instalación y Puesta en Marcha

Sigue estos pasos para configurar el proyecto localmente:

### 1. Preparación del Directorio
Asegúrate de estar en la raíz del proyecto:
```bash
cd c:\xampp\htdocs\jackeline
```

### 2. Instalar Dependencias de PHP
Ejecuta composer para instalar todas las librerías necesarias de Laravel:
```bash
composer install
```

### 3. Instalar Dependencias de JavaScript
Instala los módulos de Node para el frontend:
```bash
npm install
```

### 4. Configuración del Entorno (.env)
Copia el archivo de ejemplo y configura tus credenciales de base de datos:
```bash
copy .env.example .env
```
*Abre el archivo `.env` y ajusta las siguientes variables según tu configuración de XAMPP:*
- `DB_DATABASE=jackeline`
- `DB_USERNAME=root`
- `DB_PASSWORD=`

### 5. Generar Clave de Aplicación
```bash
php artisan key:generate
```

### 6. Migraciones de Base de Datos
Crea la estructura de la base de datos (asegúrate de que la DB exista en PHPMyAdmin):
```bash
php artisan migrate
```

### 7. Ejecutar el Proyecto en Desarrollo
Para poner en marcha el servidor y la compilación de assets en tiempo real, usa el comando simplificado configurado en el proyecto:
```bash
composer dev
```
Este comando iniciará automáticamente:
- El servidor de Laravel en [http://localhost:8000](http://localhost:8000)
- El servidor de Vite para recarga en caliente (HMR).

## 📂 Notas de la Migración

- El sistema utiliza **Tailwind CSS 4.0** para el diseño.
- La interactividad frontend se maneja con **Alpine.js**.
- Los reportes PDF se generan con **Laravel DOMPDF**.
- Las exportaciones a Excel utilizan **Laravel Excel (Maatwebsite)**.

---
> [!NOTE]
> El archivo README original de Laravel ha sido renombrado a [README_LARAVEL.md](./README_LARAVEL.md) para referencia futura.
