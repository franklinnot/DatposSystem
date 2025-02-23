import React, { useState, useEffect, useRef } from "react";
import { Link } from "@inertiajs/react";
import {
    ArrowDown,
    ArrowUp,
    IconClose,
    MenuBurger,
    IconPerfil,
} from "@/Components/Icons";
import UserProfileMenu from "@/Components/UserProfileMenu";
import ApplicationLogo from "@/Components/ApplicationLogo"; // Ajusta la ruta si es necesario

export default function Header({ userAuth }) {
    const [menuOpen, setMenuOpen] = useState(false);
    const [openSubmenu, setOpenSubmenu] = useState(null);
    const menuRef = useRef(null);

    const toggleMenu = () => setMenuOpen(!menuOpen);

    const toggleSubmenu = (index) => {
        setOpenSubmenu(openSubmenu === index ? null : index);
    };

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (menuRef.current && !menuRef.current.contains(event.target)) {
                setMenuOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () =>
            document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    const menuItems = [
        { label: "Dashboard", routeName: "dashboard", subItems: [] },
        { label: "Sucursal", routeName: "dashboard", subItems: [] },
        {
            label: "Productos",
            routeName: "dashboard",
            subItems: [
                { label: "Dashboard", routeName: "dashboard" },
                { label: "Dashboard", routeName: "dashboard" },
            ],
        },
        {
            label: "Operaciones de almacén",
            routeName: "dashboard",
            subItems: [
                { label: "Dashboard", routeName: "dashboard" },
                { label: "Dashboard", routeName: "dashboard" },
            ],
        },
    ];

    return (
        <header className="grid grid-flow-col place-items-center px-5 lg:px-6 max-h-14 sm:max-h-16 bg-[#EFF4FF] border-b-2">
            <div className="grid grid-flow-col place-items-center justify-self-start gap-2">
                <button
                    type="button"
                    onClick={toggleMenu}
                    className="mt-1 py-1 px-2 bg-[#d4e0fc] hover:bg-[#c9d7fa] rounded-md transition-all ease-in-out"
                >
                    {menuOpen ? (
                        <IconClose
                            strokeWidth={1.5}
                            size={20}
                            className="text-gray-600"
                        />
                    ) : (
                        <MenuBurger
                            strokeWidth={1.5}
                            size={20}
                            className="text-gray-600"
                        />
                    )}
                </button>

                <div
                    ref={menuRef}
                    className={`absolute top-[4.08rem] sm:top-[4.58rem] lg:top-[4.6rem] left-0 w-56 bg-[#EFF4FF] 
                                transition-transform duration-150 z-50 shadow-md rounded-xl 
                                ${
                                    menuOpen
                                        ? "translate-x-3"
                                        : "-translate-x-full"
                                }`}
                >
                    <nav className="flex flex-col bg-[#EFF4FF] rounded-2xl overflow-hidden">
                        {menuItems.map((item, index) => (
                            <div
                                key={index}
                                className="border-b last:border-b-0"
                            >
                                <div
                                    className="flex items-center justify-between hover:bg-[#e3ebff] focus:bg-gray-50 cursor-pointer"
                                    onClick={() => toggleSubmenu(index)}
                                >
                                    {item.subItems.length > 0 ? (
                                        <button
                                            type="button"
                                            className="px-4 py-2 flex-1 text-gray-600 text-sm font-normal text-left focus:outline-none w-max"
                                        >
                                            {item.label}
                                        </button>
                                    ) : (
                                        <Link
                                            href={route(item.routeName)}
                                            className="px-4 py-2 flex-1 text-gray-600 text-sm font-normal w-max"
                                            onClick={() => setMenuOpen(false)}
                                        >
                                            {item.label}
                                        </Link>
                                    )}

                                    {item.subItems.length > 0 && (
                                        <button
                                            type="button"
                                            className="pr-4 focus:outline-none"
                                        >
                                            {openSubmenu === index ? (
                                                <ArrowUp
                                                    size={18}
                                                    className="text-gray-500"
                                                />
                                            ) : (
                                                <ArrowDown
                                                    size={18}
                                                    className="text-gray-500"
                                                />
                                            )}
                                        </button>
                                    )}
                                </div>

                                <div
                                    className={`pl-6 bg-[#EFF4FF] overflow-hidden transition-all duration-300 
                                                ${
                                                    openSubmenu === index
                                                        ? "max-h-40 opacity-100"
                                                        : "max-h-0 opacity-0"
                                                }`}
                                >
                                    {item.subItems.map((subItem, subIndex) => (
                                        <Link
                                            key={subIndex}
                                            href={route(subItem.routeName)}
                                            className="hover:bg-[#e3ebff] h-8 flex items-center text-sm text-gray-500 rounded-l-lg pl-2 last:mb-2"
                                            onClick={() => setMenuOpen(false)}
                                        >
                                            {subItem.label}
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </nav>
                </div>

                <Link className="inline-flex ml-1" href={route("dashboard")}>
                    <ApplicationLogo size={44} />
                </Link>
            </div>

            <div className="grid grid-flow-col place-items-center justify-self-end gap-2 mt-1">
                <UserProfileMenu userAuth={userAuth} />
            </div>
        </header>
    );
}
