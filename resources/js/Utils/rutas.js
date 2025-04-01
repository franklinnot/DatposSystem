// Rutas de navegación
let rts_navegacion = [
    { label: "Dashboard", routeName: "dashboard", subItems: [] },
    {
        label: "Sucursales",
        routeName: "stores",
        subItems: [
            { label: "Nueva Sucursal", routeName: "stores/new" },
            { label: "Ver Sucursales", routeName: "stores" },
            { label: "Editar información", routeName: "stores/edit" },
        ],
    },
    {
        label: "Cajas",
        routeName: "cashregisters",
        subItems: [
            { label: "Nueva Caja", routeName: "cashregisters/new" },
            { label: "Ver Cajas", routeName: "cashregisters" },
            { label: "Editar información", routeName: "cashregisters/edit" },
        ],
    },
    {
        label: "Almacenes",
        routeName: "warehouses",
        subItems: [
            { label: "Nuevo Almacén", routeName: "warehouses/new" },
            { label: "Ver Almacenes", routeName: "warehouses" },
            { label: "Editar información", routeName: "warehouses/edit" },
        ],
    },
    {
        label: "Roles",
        routeName: "roles",
        subItems: [
            { label: "Nuevo Rol", routeName: "roles/new" },
            { label: "Ver Roles", routeName: "roles" },
            { label: "Editar información", routeName: "roles/edit" },
        ],
    },
    {
        label: "Usuarios",
        routeName: "users",
        subItems: [
            { label: "Nuevo Usuario", routeName: "users/new" },
            { label: "Ver Usuarios", routeName: "users" },
            { label: "Editar información", routeName: "users/edit" },
        ],
    },
    {
        label: "Unidades de Medida",
        routeName: "units",
        subItems: [
            { label: "Nueva Unidad de Medida", routeName: "units/new" },
            { label: "Ver Unidades de Medida", routeName: "units" },
            { label: "Editar información", routeName: "units/edit" },
        ],
    },
    {
        label: "Familias de Productos",
        routeName: "families",
        subItems: [
            { label: "Nueva Familia", routeName: "families/new" },
            { label: "Ver Familias", routeName: "families" },
            { label: "Editar información", routeName: "families/edit" },
        ],
    },
    {
        label: "Productos",
        routeName: "products",
        subItems: [
            { label: "Nuevo Producto", routeName: "products/new" },
            { label: "Ver Productos", routeName: "products" },
            { label: "Editar información", routeName: "products/edit" },
        ],
    },
    {
        label: "Tipos de Operación",
        routeName: "operationtypes",
        subItems: [
            {
                label: "Nuevo Tipo de Operación",
                routeName: "operationtypes/new",
            },
            { label: "Ver Tipos de Operación", routeName: "operationtypes" },
            { label: "Editar información", routeName: "operationtypes/edit" },
        ],
    },
    {
        label: "Asociados",
        routeName: "partners",
        subItems: [
            {
                label: "Nuevo Asociado",
                routeName: "partners/new",
            },
            { label: "Ver lista de Asociados", routeName: "partners" },
            { label: "Editar información", routeName: "partners/edit" },
        ],
    },
];

// Rutas de perfil -- empresa y usuarios
let rts_perfilEmpresa = [
    {
        label: "Empresa",
        routeName: "business",
        subItems: [
            {
                label: "Ver información de la Empresa",
                routeName: "business",
                subItems: [],
            },
            {
                label: "Editar información de la Empresa",
                routeName: "business/edit",
                subItems: [],
            },
            {
                label: "Ver suscripciones de la Empresa",
                routeName: "business/subscriptions",
                subItems: [],
            },
        ],
    },
];

// Rutas de perfil de usuario
let rts_perfilUsuario = [
    {
        label: "Perfil de Usuario",
        routeName: "profile",
        subItems: [
            {
                label: "Editar información",
                routeName: "profile/edit",
                subItems: [],
            },
            {
                label: "Cambiar contraseña",
                routeName: "profile/edit/password",
                subItems: [],
            },
        ],
    },
];

// --------------------------------------------

// Rutas a las que cualquier usuario tiene acceso
let rts_permisoTotal = [
    {
        label: "Ver información",
        routeName: "profile",
        subItems: [],
    },
];

// Función para filtrar las rutas permitidas
function filterRoutes(rutas, accesos) {
    // Extraer las rutas permitidas del array de accesos
    const rutasAccesibles = new Set(accesos.map((acceso) => acceso.ruta));

    return rutas
        .map((item) => {
            // Filtrar los subItems que tienen acceso permitido
            const filteredSubItems = item.subItems
                ? item.subItems.filter((subItem) =>
                      rutasAccesibles.has(subItem.routeName)
                  )
                : [];

            // Verificar si el elemento principal es accesible o si tiene subItems accesibles
            if (
                rutasAccesibles.has(item.routeName) ||
                filteredSubItems.length > 0
            ) {
                return { ...item, subItems: filteredSubItems };
            }

            return null;
        })
        .filter((item) => item !== null); // Eliminar ítems nulos
}

// rutas permitidas relacionadas a la empresa
export function rutas_perfilEmpresa(accesos) {
    return filterRoutes(rts_perfilEmpresa, accesos);
}

// rutas permitidas para la navegación
export function rutas_navegacion(accesos) {
    return filterRoutes(rts_navegacion, accesos);
}

// funcion que retorna las rutas padre
function rutasPadre(rutas) {
    return rutas.map((item, index) => ({
        id: index + 1, // Asignamos un ID único basado en el índice
        name: item.label, // Tomamos el label de la ruta
        ruta: item.routeName, // Tomamos el routeName de la ruta
    }));
}

export function getRutasPadre() {
    let rutas = rts_perfilUsuario.concat(rts_perfilEmpresa);
    rutas = rutas.concat(rts_navegacion);
    return rutasPadre(rutas);
}

// Función que busca una ruta por su id
export function getRutaPorId(rutas, id) {
    return rutas.find((ruta) => ruta.id === id) || null;
}
