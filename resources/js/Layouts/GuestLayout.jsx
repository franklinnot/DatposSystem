import { Link } from "@inertiajs/react";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";

export default function Guest({ children }) {

    const { props } = usePage(); // Obtiene las props globales de Inertia
    const userAuth = props.auth?.user; // Verifica si el usuario está autenticado

    useEffect(() => {
        if (userAuth) {
            Inertia.visit(route("dashboard"), { replace: true }); // Redirige si el usuario no está autenticado
        }
    }, [userAuth]);

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
