import { useState } from "react";
import ApplicationLogo from "@/Components/ApplicationLogo";
import Header from "@/Components/Header";
import Dropdown from "@/Components/Dropdown";
import NavLink from "@/Components/NavLink";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink";
import { Link } from "@inertiajs/react";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";

export default function Authenticated({ user, children }) {
    const { props } = usePage(); // props globales de Inertia
    const userAuth = props.auth?.user; // prop auth.user

    // redirigir usuarios no autenticados a la página de inicio de sesión
    useEffect(() => {
        if (!userAuth) {
            Inertia.visit(route("login"), { replace: true }); // Redirige si el usuario no está autenticado
        }
    }, [userAuth]);

    return (
        <div className="h-dvh w-dvw grid bg-white">
            {/* header */}
            <Header userAuth={userAuth}></Header>

            <main>{children}</main>
        </div>
    );
}
