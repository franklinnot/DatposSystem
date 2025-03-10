

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
];

// Rutas de perfil -- empresa y usuarios
let rts_perfilEmpresa = [
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
];

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
            if (rutasAccesibles.has(item.routeName) || filteredSubItems.length > 0) {
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

// funcion para verificar si la ruta actual está permitida
export function verificarRuta(route, accesos) {
    let rutasAccesibles = rts_permisoTotal.map((acceso) => acceso.routeName);
    if (rutasAccesibles.includes(route)) {
        return true;
    }
    // seleccionar solo el campo ruta de cada acceso
    rutasAccesibles = accesos.map((acceso) => acceso.ruta);
    // verificar si este array de rutas incluye la ruta a consultar
    return rutasAccesibles.includes(route);
}

