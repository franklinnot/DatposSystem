

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
function filterRoutes(rutas, accesos, empresa) {
    // Extraer las rutas permitidas del array de accesos
    const rutasAccesibles = new Set(accesos.map((acceso) => acceso.ruta));

    return rutas
        .map((item) => {
            // Filtrar los subItems que tienen acceso permitido y no violan la condición
            const filteredSubItems = item.subItems
                ? item.subItems.filter((subItem) => {
                      // Si la empresa alcanzó el límite de sucursales y la ruta es "stores/new", omitirla
                      if (
                          empresa.cantidad_sucursales ===
                              empresa.sucursales_registradas &&
                          subItem.routeName === "stores/new"
                      ) {
                          return false;
                      }
                      return rutasAccesibles.has(subItem.routeName);
                  })
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
export function rutas_navegacion(accesos, empresa) {
    return filterRoutes(rts_navegacion, accesos, empresa);
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

