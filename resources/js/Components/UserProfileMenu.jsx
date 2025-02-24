import React, { useState, useRef, useEffect } from "react";
import { IconPerfil, ArrowDown } from "./Icons"; // Asegúrate de tener estos componentes

const UserProfileMenu = ({ userAuth }) => {
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
            <span className="text-gray-600 text-md">{userAuth.nombre}</span>

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
                className={`absolute top-[3.2rem] right-0 w-[10rem] bg-[#EFF4FF]
                            transition-all duration-150 z-50 shadow-md rounded-xl transform ${
                                menuOpen
                                    ? "translate-x-0 opacity-100"
                                    : "translate-x-4 opacity-0"
                            }`}
                style={{ pointerEvents: menuOpen ? "auto" : "none" }}
            >
                <ul>
                    <li className="px-4 py-2 hover:bg-[#e3ebff] border-b text-sm rounded-t-xl ">
                        <a
                            href="/perfil"
                            className="flex flex-row items-center justify-between"
                        >
                            <span className="w-max">Perfil de usuario</span>
                            <ArrowDown
                                size={13}
                                className="rotate-[-90deg]"
                            ></ArrowDown>
                        </a>
                    </li>
                    {/* Mostrar el perfil de empresa solo si el usuario tiene acceso */}
                    {userAuth.hasCompanyAccess && (
                        <li className="px-4 py-2 hover:bg-[#e3ebff] border-b text-sm">
                            <a href="/empresa">Perfil de empresa</a>
                        </li>
                    )}
                    <li className="px-4 py-2 hover:bg-[#e3ebff] text-sm text-red-600 rounded-b-xl">
                        <a href="/logout">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    );
};

export default UserProfileMenu;
