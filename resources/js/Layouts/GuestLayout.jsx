import { Link } from "@inertiajs/react";
import { useEffect } from "react";

export default function Guest({ children }) {

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
