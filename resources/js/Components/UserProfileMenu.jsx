import React, { useState, useRef, useEffect } from "react";
import { Link } from "@inertiajs/react";
import { IconPerfil, ArrowDown } from "@/Components/Icons"; // Asegúrate de tener estos componentes
import { rutas_perfilEmpresa } from "../Utils/rutas.js";

const UserProfileMenu = ({ auth }) => {
    let accesos = auth?.accesos; 
    let rts_perfilEmpresa = rutas_perfilEmpresa(accesos);

    const [menuOpen, setMenuOpen] = useState(false);
    const menuRef = useRef(null);
    // Cerrar el menú si se hace clic fuera
    useEffect(() => {
        const handleClickOutside = (event) => {
            const menuProfile = document.getElementById("menu-profile");
            if (
                menuRef.current &&
                !menuRef.current.contains(event.target) &&
                menuProfile &&
                !menuProfile.contains(event.target)
            ) {
                setMenuOpen(false);
            }
        };

        document.addEventListener("mousedown", handleClickOutside);
        return () =>
            document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    return (
        <div
            className="relative grid grid-flow-col place-items-center justify-self-end gap-2 mt-1"
            ref={menuRef}
        >
            {/* Nombre del usuario */}
            <span className="text-gray-600 text-md">
                {auth.empresa.nombre_comercial}
            </span>

            {/* Contenedor del ícono de perfil y la flechita */}
            <div
                id="menu-profile"
                className="relative cursor-pointer"
                onClick={() => setMenuOpen((prev) => !prev)}
            >
                <IconPerfil size={34} className="text-gray-700" />
                {/* La flechita se posiciona de forma absoluta respecto al contenedor */}
                <ArrowDown
                    size={14}
                    className="absolute text-white bottom-[6px] font-semibold right-[2.5px] transform translate-y-1/2 bg-gray-600 border border-gray-500 rounded-full"
                />
            </div>

            {/* Menú desplegable */}
            <div
                className={`absolute top-[3.4rem] right-[-6px] w-[10rem] bg-[#EFF4FF]
                            transition-all duration-150 z-50 shadow-md rounded-xl transform ${
                                menuOpen
                                    ? "translate-x-0 opacity-100"
                                    : "translate-x-4 opacity-0"
                            }`}
                style={{ pointerEvents: menuOpen ? "auto" : "none" }}
            >
                <ul>
                    <li className="px-4 py-2 hover:bg-[#e3ebff] border-b text-gray-600 text-sm font-normal text-left rounded-t-xl ">
                        <Link href={route("profile")}>Perfil de Usuario</Link>
                    </li>
                    {/* Mostrar el perfil de empresa solo si el usuario tiene acceso */}
                    {rts_perfilEmpresa.length > 0 && (
                        <li className="px-4 py-2 hover:bg-[#e3ebff] border-b text-gray-600 text-sm font-normal text-left">
                            <Link href={route(rts_perfilEmpresa[0].routeName)}>
                                Perfil de Empresa
                            </Link>
                        </li>
                    )}
                    <li className="px-4 py-2 hover:bg-[#e3ebff] text-sm font-normal text-left text-red-600 rounded-b-xl">
                        <Link href={route("logout")} method="post" as="button">
                            Cerrar sesión
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    );
};

export default UserProfileMenu;
