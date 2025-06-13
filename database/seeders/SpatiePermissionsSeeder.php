<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Asegúrate de importar tu modelo User
use App\Models\Branch; // Asegúrate de importar tu modelo Branch

class SpatiePermissionsSeeder extends Seeder
{
    public function run()
    {
        // Limpiar la caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. Crear Permisos ---
        // Nomenclatura común: {action}_{model} o {action}_any_{model}
        // view_any_* y view_* son importantes para Filament
        $permissions = [
            // Usuarios
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user', 'force_delete_user',
            // Roles (si el admin los gestiona desde Filament)
            'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role', 'force_delete_role',
            // Sucursales
            'view_any_branch', 'view_branch', 'create_branch', 'update_branch', 'delete_branch',
            // Productos
            'view_any_product', 'view_product', 'create_product', 'update_product', 'delete_product',
            // Ingredientes
            'view_any_ingredient', 'view_ingredient', 'create_ingredient', 'update_ingredient', 'delete_ingredient',
            // Compras
            'view_any_purchase', 'view_purchase', 'create_purchase', 'update_purchase', 'delete_purchase',
            // Ventas
            'view_any_sale', 'view_sale', 'create_sale', 'update_sale', 'delete_sale','view_any_sale_item','view_sale_item',
            // CurrentStock (stock actual)
            'view_any_current_stock', 'view_current_stock', 'create_current_stock', 'update_current_stock', 'delete_current_stock',
            // StockMovement (movimientos de stock)
            'view_any_stock_movement', 'view_stock_movement', 'create_stock_movement', 'update_stock_movement', 'delete_stock_movement',
            // CashBox (cajas)
            'view_any_cash_box', 'view_cash_box', 'create_cash_box', 'update_cash_box', 'delete_cash_box',
            // CashMovement (movimientos de caja)
            'view_any_cash_movement', 'view_cash_movement', 'create_cash_movement', 'update_cash_movement', 'delete_cash_movement',
            // Recetas
            'view_any_recipe', 'view_recipe', 'create_recipe', 'update_recipe', 'delete_recipe',
            // ComboItems (si los gestionas directamente)
            'view_any_combo_item', 'view_combo_item', 'create_combo_item', 'update_combo_item', 'delete_combo_item',
            // Proveedores
            'view_any_supplier', 'view_supplier', 'create_supplier', 'update_supplier', 'delete_supplier',

            // Permisos para Páginas/Funcionalidades Específicas
            'access_admin_panel', // Acceso general al panel de Filament
            'access_dashboard',   // Acceso a la página del Dashboard
            'access_pos_page',    // Acceso a tu página de Punto de Venta (NuevaOrden)
            'access_reports_page',// Acceso a una página de reportes
            'access_settings_page',// Acceso a una página de configuración
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- 2. Crear Roles y Asignar Permisos ---
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $cashierRole = Role::firstOrCreate(['name' => 'Cajero']);
        $managerRole = Role::firstOrCreate(['name' => 'Gerente']);
        $warehouseRole = Role::firstOrCreate(['name' => 'Almacenero']);

        // Asignar todos los permisos al Administrador
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos al Cajero
        $cashierRole->givePermissionTo([
            'access_admin_panel',
            'access_dashboard',
            'access_pos_page', // Puede usar el POS
            'view_any_sale', 'view_sale', 'create_sale', // Puede ver y crear ventas
            'view_any_sale_item', 'view_sale_item', // Puede ver detalles de ítems de venta
            'view_any_product', 'view_product', // Puede ver productos en el menú
            'view_any_current_stock', 'view_current_stock', // Puede ver stock (luego lo limitaremos por sucursal)
            'view_any_cash_box', 'view_cash_box', 'create_cash_box', 'update_cash_box', // Puede abrir/cerrar su caja y ver sus movimientos
            'view_any_cash_movement', 'view_cash_movement', 'create_cash_movement',
        ]);
        // Gerente - puede ver y editar más cosas, pero no todo
        $managerRole->givePermissionTo([
            'access_admin_panel', 'access_dashboard',
            'view_any_user', 'view_user', 'update_user',
            'view_any_branch', 'view_branch', 'update_branch',
            'view_any_product', 'view_product', 'create_product', 'update_product',
            'view_any_ingredient', 'view_ingredient', 'create_ingredient', 'update_ingredient',
            'view_any_purchase', 'view_purchase', 'create_purchase', 'update_purchase',
            'view_any_sale', 'view_sale', 'update_sale',
            'view_any_current_stock', 'view_current_stock', 'create_current_stock', 'update_current_stock',
            'view_any_stock_movement', 'view_stock_movement', 'create_stock_movement',
            'view_any_cash_box', 'view_cash_box', 'create_cash_box', 'update_cash_box',
            'view_any_cash_movement', 'view_cash_movement', 'create_cash_movement',
            'view_any_supplier', 'view_supplier', 'create_supplier', 'update_supplier',
            'access_reports_page',
        ]);

        // Almacenero - enfoque en inventario
        $warehouseRole->givePermissionTo([
            'access_admin_panel', 'access_dashboard',
            'view_any_current_stock', 'view_current_stock', 'create_current_stock', 'update_current_stock', 'delete_current_stock',
            'view_any_stock_movement', 'view_stock_movement', 'create_stock_movement', 'update_stock_movement', 'delete_stock_movement',
            'view_any_ingredient', 'view_ingredient', 'create_ingredient', 'update_ingredient',
            'view_any_product', 'view_product', // Para ver los productos que consumen
            'view_any_purchase', 'view_purchase', 'create_purchase', 'update_purchase',
            'view_any_supplier', 'view_supplier', 'create_supplier', 'update_supplier',
        ]);


        // --- 3. Asignar Roles a Usuarios Existentes ---
        // Estos usuarios ya fueron creados por tu RolesAndUsersSeeder.
        // Asumiendo que sus emails son únicos y están en la tabla 'users'
        User::where('email', 'josenegretti@gmail.com')->first()?->assignRole('Administrador');
        User::where('email', 'gerente@turril.com')->first()?->assignRole('Gerente');
        User::where('email', 'cajero@turril.com')->first()?->assignRole('Cajero');
        // Si tienes un usuario almacenero, asignarle el rol aquí.
    }
}
