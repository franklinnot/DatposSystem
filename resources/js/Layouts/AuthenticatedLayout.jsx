import Header from "@/Components/Header";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";

export default function Authenticated({ auth, children }) {
    let usuario = auth?.user; // prop auth.user

    // redirigir usuarios no autenticados a la página de inicio de sesión
    useEffect(() => {
        if (!usuario) {
            Inertia.visit(route("login"), { replace: true }); // Redirige si el usuario no está autenticado
        }
    }, [usuario]);

    return (
        <div className="h-dvh w-dvw grid grid-rows-[auto_1fr] bg-white">
            {/* header */}
            <Header auth={auth}></Header>

            <main className="p-4">{children}</main>
        </div>
    );
}
