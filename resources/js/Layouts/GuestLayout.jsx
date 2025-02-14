import { Link } from "@inertiajs/react";
import { useEffect } from "react";

export default function Guest({ children }) {

    // Espera a que se cargue el DOM y busca el elemento con id "app"
    //     const appElement = document.getElementById("app");
    //     if (!appElement) {
    //         console.warn('No se encontró el elemento con id "app".');
    //         return;
    //     }

    //     // Obtiene el contenido del atributo data-page
    //     const pageData = appElement.getAttribute("data-page");
    //     if (!pageData) {
    //         console.warn('No se encontró data-page en el elemento "app".');
    //         return;
    //     }

    //     try {
    //         // Parsea el JSON que se encuentra en data-page
    //         const page = JSON.parse(pageData);
    //         // Si se detecta que existe auth.user (es decir, el usuario ya está autenticado)
    //         if (page.props.auth.user !== null) { // page.props.auth.user.email !== null
    //             // Redirige a la ruta del home
    //             window.location.href = "/dashboard";
    //         }
    //     } catch (error) {
    //         console.error("Error al parsear data-page:", error);
    //     }
    // }, []);

    // Construir la URL completa

    return (
        <div
            className="h-dvh w-dvw pb-6 grid place-items-center
                      bg-gradient-to-tr from-[#D3E0FF] to-[#FFFFFF]
                      lg:bg-none lg:bg-white"
        >
            {/*  */}
            {children}
        </div>
    );
}
