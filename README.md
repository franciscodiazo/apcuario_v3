# Acuarius v3

Sistema moderno de gestión de usuarios, lecturas, consumos y precios de agua potable para acueductos comunitarios.

## Características principales

- **Gestión de usuarios:** Registro, listado, búsqueda y edición de usuarios.
- **Lecturas:** Registro individual y masivo de lecturas, cálculo automático de consumo, búsqueda y ordenamiento por usuario, matrícula, año y ciclo.
- **Consumos:** Visualización de consumos, cálculo automático del valor a pagar según tabla de precios editable por año, filtro por estado de pago, ciclo y año.
- **Pagos:** Registro de pagos con método (banco, efectivo, transferencia, cheque, otro), estado de pagado y recibo detallado.
- **Precios:** Gestión de precios base y adicional por año.
- **Interfaz moderna:** UI/UX profesional, responsiva, con Tailwind CSS y glassmorphism.
- **Paginación y filtros profesionales** en todas las vistas principales.

## Instalación

1. Clona el repositorio:
   ```sh
   git clone https://github.com/franciscodiazo/apcuario_v3.git
   cd apcuario_v3
   ```
2. Instala dependencias PHP:
   ```sh
   composer install
   ```
3. Instala dependencias JS:
   ```sh
   npm install && npm run build
   ```
4. Copia el archivo `.env.example` a `.env` y configura tu base de datos.
5. Ejecuta las migraciones:
   ```sh
   php artisan migrate
   ```
6. Inicia el servidor:
   ```sh
   php artisan serve
   ```

## Uso

- Accede a `/usuarios` para gestionar usuarios.
- Accede a `/lecturas` para registrar y consultar lecturas.
- Accede a `/consumos` para ver consumos, pagar y generar recibos.
- Accede a `/usuarios-listado` para el registro masivo de lecturas.

## Créditos
Desarrollado por Francisco Díaz O. ([franciscojdiazo@gmail.com](mailto:franciscojdiazo@gmail.com))

---

¡Contribuciones y sugerencias son bienvenidas!
