import * as ToastPrimitives from "@radix-ui/react-toast";
import { useState, useEffect, useRef } from "react";

const ToastTypeIcons = {
    success: (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth={1.5}
            stroke="currentColor"
            className="size-6"
        >
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="m4.5 12.75 6 6 9-13.5"
            />
        </svg>
    ),
    error: (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth={1.5}
            stroke="currentColor"
            className="size-6"
        >
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
            />
        </svg>
    ),
    close: (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth={1.5}
            stroke="currentColor"
            className="size-6"
        >
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M6 18 18 6M6 6l12 12"
            />
        </svg>
    ),
};

export default function useToast() {
    const [toast, setToast] = useState({
        message: null,
        type: "default",
        open: false,
        duration: 8000,
        extraClassName: "",
    });

    const timerRef = useRef(null); // Referencia para el timer

    const showToast = (
        message,
        type = "default",
        duration = 8000,
        extraClassName = ""
    ) => {
        // Limpia el timer existente al mostrar nuevo toast
        if (timerRef.current) {
            clearTimeout(timerRef.current);
        }

        setToast({
            message,
            type,
            open: true,
            duration,
            extraClassName,
        });
    };

    useEffect(() => {
        if (toast.open) {
            timerRef.current = setTimeout(() => {
                setToast((prev) => ({ ...prev, open: false }));
            }, toast.duration);
        }
        return () => {
            if (timerRef.current) {
                clearTimeout(timerRef.current);
            }
        };
    }, [toast.open, toast.duration]);

    const ToastComponent = () => (
        <ToastPrimitives.Provider>
            <ToastPrimitives.Root
                open={toast.open}
                onOpenChange={(open) => setToast((prev) => ({ ...prev, open }))}
                className={`
                    flex items-center gap-3 rounded-lg px-4 py-3
                    shadow-md 
                    ${
                        toast.type === "success"
                            ? "bg-emerald-500 text-white"
                            : toast.type === "error"
                            ? "bg-rose-500 text-white"
                            : "bg-gray-900 text-gray-100"
                    }
                    ${toast.extraClassName}
                `}
            >
                {ToastTypeIcons?.[toast.type] || ToastTypeIcons.close}

                <div className="flex-1 min-w-0">
                    <ToastPrimitives.Title className="font-semibold text-sm leading-6">
                        {toast.message}
                    </ToastPrimitives.Title>
                </div>

                <ToastPrimitives.Close
                    className="ml-4 p-1 hover:bg-white/20 rounded focus:outline-none"
                    onClick={() =>
                        setToast((prev) => ({ ...prev, open: false }))
                    }
                >
                    {ToastTypeIcons.close}
                    <span className="sr-only">Cerrar</span>
                </ToastPrimitives.Close>
            </ToastPrimitives.Root>

            {/* Viewport con posicionamiento correcto y z-index alto */}
            <ToastPrimitives.Viewport className="fixed top-4 right-4 w-[320px] z-[9999]" />
        </ToastPrimitives.Provider>
    );

    return { showToast, ToastComponent };
}