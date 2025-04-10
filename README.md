# DatposSystem

![DatposSystem](https://raw.githubusercontent.com/franklinnot/DatposSystem/refs/heads/main/public/favicon.svg)

## 📋 Acerca del Proyecto

**DatposSystem** es un sistema integral diseñado para gestionar ventas, inventarios, usuarios y otros procesos esenciales en un entorno comercial. Construido con **Laravel** y **React**, este proyecto combina las mejores prácticas del desarrollo moderno para ofrecer una solución robusta, escalable y fácil de usar.

El objetivo principal de este proyecto es proporcionar una base sólida para la administración de negocios, permitiendo a los usuarios manejar productos, listas de precios, turnos de caja, comprobantes y más, a través de una interfaz intuitiva y funcionalidades avanzadas.

---

## 🚀 Características Principales

- **Gestión de Productos**:
  - Registrar, listar, editar y eliminar productos.
  - Manejo de variantes y detalles (ej. colores, tallas).

- **Administración de Usuarios**:
  - Registro de usuarios con roles y permisos personalizados.
  - Asignación de accesos a sucursales y almacenes.

- **Listas de Precios**:
  - Creación y edición de listas de precios.
  - Asociación de precios a productos específicos.

- **Turnos de Caja**:
  - Gestión de turnos, incluyendo apertura, cierre y control de caja.

- **Integración de Comprobantes**:
  - Configuración y manejo de diferentes tipos de comprobantes.

- **Ventas**:
  - Registro de ventas con detalles de productos y comprobantes asociados.

- **Notificaciones Dinámicas**:
  - Uso de `Toast` para mostrar mensajes de éxito, advertencias o errores en tiempo real.

- **Seguridad y Roles**:
  - Middleware personalizado para gestionar accesos restringidos.
  - Sistema basado en roles y permisos configurables.

---

## 🛠️ Tecnologías Utilizadas

### **Backend**
- **[Laravel](https://laravel.com/):** Framework PHP para el desarrollo backend.
- **Inertia.js:** Comunicación fluida entre el frontend y backend sin necesidad de APIs REST explícitas.

### **Frontend**
- **[React](https://reactjs.org/):** Biblioteca JavaScript para construir interfaces de usuario dinámicas.
- **Tailwind CSS:** Framework de diseño para estilos rápidos y personalizables.

### **Base de Datos**
- **MySQL:** Base de datos relacional para almacenar toda la información del sistema.
- **Tabla `acceso`:** Control centralizado de rutas y permisos basados en roles.

---

## 🎨 Vistas y Funcionalidades

### **Productos**
- Vista para listar productos con opciones de búsqueda y filtros avanzados.
- Formulario para registrar o editar productos con variantes (colores, tallas, etc.).

### **Usuarios**
- Gestión de usuarios con asignación de roles y permisos.
- Multi-selección para asociar usuarios a sucursales y almacenes.

### **Listas de Precios**
- Módulo dedicado para la creación y edición de listas de precios.
- Rutas y permisos configurados para restringir el acceso según el rol del usuario.

---

## 📂 Estructura del Proyecto

La organización del proyecto sigue principios modernos de modularidad y escalabilidad:

```
DatposSystem/
├── app/Http/Controllers    # Controladores de Laravel
├── app/Models              # Modelos del sistema
├── database/migrations     # Migraciones de la base de datos
├── resources/js/Pages      # Páginas y componentes React
├── resources/views         # Plantillas Blade
├── routes/web.php          # Rutas web del sistema
├── public/                 # Archivos públicos (imágenes, assets, etc.)
└── README.md               # Documentación del proyecto
```

---

## 🌟 Instalación y Configuración

Sigue estos pasos para configurar y ejecutar el proyecto en tu entorno local:

### **Requisitos Previos**
- PHP >= 8.1
- Composer
- Node.js >= 16
- MySQL

### **Pasos**
1. Clona el repositorio:
   ```bash
   git clone https://github.com/franklinnot/DatposSystem.git
   cd DatposSystem
   ```

2. Instala las dependencias de PHP con Composer:
   ```bash
   composer install
   ```

3. Instala las dependencias de JavaScript:
   ```bash
   npm install && npm run dev
   ```

4. Configura el archivo `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configura la base de datos en el archivo `.env` y ejecuta las migraciones:
   ```bash
   php artisan migrate --seed
   ```

6. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

---

## ✍️ Autor

**Franklin Baca Campos**  
- **LinkedIn:** [Franklin Not](https://www.linkedin.com/in/franklinnot/)  
- **GitHub:** [franklinnot](https://github.com/franklinnot)  
- **Correo:** [tuemail@example.com](mailto:tuemail@example.com)

Este proyecto fue diseñado y desarrollado con el objetivo de ser una solución práctica para la gestión comercial, así como un ejemplo de buenas prácticas en desarrollo web.

---

## 📄 Licencia

Este proyecto está licenciado bajo la [MIT License](https://opensource.org/licenses/MIT). Puedes usarlo, modificarlo y distribuirlo libremente mientras se mantengan los créditos originales.

---

## 🚀 Inspiración y Futuro

Este proyecto no solo está diseñado para resolver problemas reales, sino también para inspirar a otros desarrolladores. Si estás buscando un ejemplo práctico de cómo construir una aplicación moderna utilizando Laravel y React, este proyecto puede ser un excelente punto de partida.

Si tienes ideas para mejorar el sistema o deseas contribuir, ¡estaré encantado de colaborar contigo!

---

**¡Gracias por visitar DatposSystem! Espero que este proyecto te inspire tanto como a mí al desarrollarlo.**