import Header from "@/Components/Header";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";
import { verificarRuta } from "../Utils/rutas.js";

export default function Authenticated({ auth, children }) {
    let usuario = auth?.user; // prop auth.user
    let accesos = auth?.accesos; // prop auth.accesos
    let ruta_actual = usePage().url.substring(1); // ruta actual
    let ruta_permitida = verificarRuta(ruta_actual, accesos); // verificar si la ruta actual está permitida
    // alert(ruta_actual);
    // redirigir usuarios no autenticados a la página de inicio de sesión
    useEffect(() => {
        if (!usuario) {
            Inertia.visit(route("login"), { replace: true }); // Redirige si el usuario no está autenticado
        }
    }, [usuario]);

    // Verificar si la ruta actual NO está permitida
    useEffect(() => {
        if (!ruta_permitida) {
            Inertia.visit(route("profile"), { replace: true }); // Redirige si la ruta no está permitida
        }
    }, [ruta_permitida]);

    return (
        <div className="h-dvh w-dvw grid bg-white">
            {/* header */}
            <Header auth={auth}></Header>

            <main>{children}</main>
        </div>
    );
}
