

// Rutas de navegación
let rts_navegacion = [
    { label: "Dashboard", routeName: "dashboard", subItems: [] },
    {
        label: "Sucursal",
        routeName: "stores",
        subItems: [
            { label: "Nueva Sucursal", routeName: "stores/new" },
            { label: "Ver Sucursales", routeName: "stores" },
            { label: "Editar información", routeName: "stores/edit" },
        ],
    },
];


// Rutas de perfil -- empresa y usuarioc
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
    const rutasAccesibles = accesos.map((acceso) => acceso.ruta);

    return rutas
        .map((item) => {
            // Si no tiene subRutas, comprobar si su ruta está permitida
            if (!item.subRutas || item.subRutas.length === 0) {
                return rutasAccesibles.includes(item.routeName) ? item : null;
            } else {
                // Filtrar subRutas que tienen acceso permitido
                const filteredsubRutas = item.subRutas.filter((subRuta) =>
                    rutasAccesibles.includes(subRuta.routeName)
                );

                // Si hay subRutas visibles, devolver el ítem con los subRutas filtrados
                return filteredsubRutas.length > 0
                    ? { ...item, subRutas: filteredsubRutas }
                    : null;
            }
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

