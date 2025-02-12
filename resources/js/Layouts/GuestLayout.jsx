import ApplicationLogo from "@/Components/ApplicationLogo";
import { Link } from "@inertiajs/react";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";

export default function Guest({ children }) {
    useEffect(() => {
        // Espera a que se cargue el DOM y busca el elemento con id "app"
        const appElement = document.getElementById("app");
        if (!appElement) {
            console.warn('No se encontró el elemento con id "app".');
            return;
        }

        // Obtiene el contenido del atributo data-page
        const pageData = appElement.getAttribute("data-page");
        if (!pageData) {
            console.warn('No se encontró data-page en el elemento "app".');
            return;
        }

        try {
            // Parsea el JSON que se encuentra en data-page
            const page = JSON.parse(pageData);
            // Si se detecta que existe auth.user (es decir, el usuario ya está autenticado)
            if (
                page.props.auth.user.email !== null
            ) {
                // Redirige a la ruta del home
                window.location.href = "/dashboard";
            }
        } catch (error) {
            console.error("Error al parsear data-page:", error);
        }
    }, []);

    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <Link href="/">
                    <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" />
                </Link>
            </div>

            <div className="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {children}
            </div>
        </div>
    );
}
