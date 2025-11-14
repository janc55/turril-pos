
# El Turril POS - Sistema de Punto de Venta

![Logo El Turril](public/images/el_turril.webp)

El Turril POS es un completo sistema de Punto de Venta (POS) diseñado para gestionar las operaciones de un restaurante o negocio de comida. Permite la administración de sucursales, inventario, recetas, ventas, compras y personal de una manera eficiente y centralizada.

## Tabla de Contenidos

- [Sobre el Proyecto](#sobre-el-proyecto)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Características Principales](#características-principales)
- [Esquema de la Base de Datos](#esquema-de-la-base-de-datos)
- [Cómo Empezar](#cómo-empezar)
  - [Pre-requisitos](#pre-requisitos)
  - [Instalación](#instalación)
- [Uso](#uso)
- [Contribuciones](#contribuciones)
- [Licencia](#licencia)

## Sobre el Proyecto

Este proyecto ha sido desarrollado para "El Turril", un negocio que necesitaba una solución a medida para la gestión de su punto de venta. El sistema está construido con tecnologías modernas y robustas, enfocado en la facilidad de uso y la escalabilidad.

## Tecnologías Utilizadas

- **Backend:**
  - PHP 8.2
  - Laravel 12
  - Filament 4.0 (Panel de Administración)
  - Spatie Laravel Permission (Gestión de Roles y Permisos)
  - Barryvdh Laravel DomPDF (Generación de PDFs)
- **Frontend:**
  - Vite
  - Alpine.js
  - Tailwind CSS
- **Base de Datos:**
  - Compatible con MySQL, PostgreSQL, SQLite.

## Características Principales

- **Gestión de Múltiples Sucursales:** Administra diferentes locales desde un solo lugar.
- **Control de Inventario:** Seguimiento en tiempo real del stock de ingredientes y productos.
- **Gestión de Recetas:** Define los ingredientes y cantidades para cada producto, permitiendo el descuento automático de inventario.
- **Punto de Venta (POS):** Una interfaz intuitiva (`/nueva-orden`) para registrar ventas de forma rápida.
- **Gestión de Compras:** Registra las compras a proveedores y actualiza el inventario.
- **Control de Caja:** Administra los movimientos de efectivo en diferentes cajas.
- **Gestión de Usuarios y Roles:** Asigna permisos específicos a cada empleado.
- **Reportes:** (Funcionalidad implícita a través de los recursos de Filament) Genera reportes de ventas, compras, etc.

## Esquema de la Base de Datos

El sistema se basa en un esquema de base de datos relacional que incluye las siguientes entidades principales:

- `branches`: Sucursales del negocio.
- `products`: Productos finales para la venta.
- `ingredients`: Materias primas.
- `recipes` y `recipe_ingredients`: Recetas que definen la composición de los productos.
- `purchases` y `purchase_items`: Compras de ingredientes a proveedores (`suppliers`).
- `current_stock` y `stock_movements`: Niveles de inventario y sus movimientos.
- `sales` y `sale_items`: Ventas a clientes.
- `cash_boxes` y `cash_movements`: Cajas de efectivo y sus transacciones.
- `users` y `roles`: Usuarios del sistema y sus roles.
- `settings`: Configuraciones generales de la aplicación.
- `combo_items`: Para la creación de combos o paquetes de productos.

## Cómo Empezar

Sigue estos pasos para tener una copia local del proyecto funcionando.

### Pre-requisitos

Asegúrate de tener instalado lo siguiente:

- PHP 8.2 o superior
- Composer
- Node.js y npm
- Un servidor de base de datos (ej. MySQL, MariaDB)

### Instalación

1. **Clona el repositorio:**
   ```sh
   git clone https://github.com/tu-usuario/el-turril-pos.git
   cd el-turril-pos
   ```

2. **Instala las dependencias de PHP:**
   ```sh
   composer install
   ```

3. **Instala las dependencias de Node.js:**
   ```sh
   npm install
   ```

4. **Configura el entorno:**
   - Copia el archivo de ejemplo `.env.example` a `.env`:
     ```sh
     cp .env.example .env
     ```
   - Genera la clave de la aplicación:
     ```sh
     php artisan key:generate
     ```
   - Configura las credenciales de tu base de datos en el archivo `.env`.

5. **Ejecuta las migraciones y los seeders:**
   ```sh
   php artisan migrate --seed
   ```
   Esto creará la estructura de la base de datos y la poblará con datos iniciales.

6. **Compila los assets del frontend:**
   ```sh
   npm run build
   ```

7. **Inicia el servidor de desarrollo:**
   ```sh
   php artisan serve
   ```

## Uso

Una vez que el servidor esté en funcionamiento, puedes acceder al panel de administración en la ruta `/admin`.

- **Login:** Utiliza las credenciales creadas por los seeders (puedes encontrarlas en `database/seeders/RolesAndUsersSeeder.php`).
- **Panel de Administración:** Desde aquí puedes gestionar todos los aspectos del sistema (productos, inventario, etc.).
- **Nueva Orden:** Para crear una nueva venta, dirígete a la sección "Nueva Orden" en el panel.

## Contribuciones

Las contribuciones son lo que hacen a la comunidad de código abierto un lugar increíble para aprender, inspirar y crear. Cualquier contribución que hagas será **muy apreciada**.

Si tienes una sugerencia para mejorar esto, por favor haz un fork del repositorio y crea una pull request. También puedes simplemente abrir un issue con la etiqueta "enhancement".

1. Haz un Fork del Proyecto
2. Crea tu Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Haz Commit de tus Cambios (`git commit -m 'Add some AmazingFeature'`)
4. Haz Push a la Branch (`git push origin feature/AmazingFeature`)
5. Abre una Pull Request

## Licencia

Distribuido bajo la Licencia MIT. Ver `LICENSE` para más información.
