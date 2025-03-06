import * as ToastPrimitives from "@radix-ui/react-toast";
import { useState, useEffect } from "react";

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
        duration: 3000,
        extraClassName: "", // Permite agregar estilos personalizados
    });

    const showToast = (
        message,
        type = "default",
        duration = 3000,
        extraClassName = ""
    ) => {
        setToast({
            message,
            type,
            open: true,
            duration,
            extraClassName, // Asignar los estilos adicionales
        });
    };

    useEffect(() => {
        if (toast.open) {
            const timer = setTimeout(() => {
                setToast((prev) => ({ ...prev, open: false }));
            }, toast.duration);
            return () => clearTimeout(timer);
        }
    }, [toast.open, toast.duration]);

    const ToastComponent = () => (
        <ToastPrimitives.Provider>
            <ToastPrimitives.Root
                open={toast.open}
                onOpenChange={(open) => setToast((prev) => ({ ...prev, open }))}
                className={`
                    fixed top-[4.5rem] right-4 z-50 flex items-center gap-3 rounded-lg px-4 py-3
                    shadow-[0px_5px_20px_rgba(0,0,0,0.1)]
                    ${
                        toast.type === "success"
                            ? "bg-emerald-500 text-white"
                            : toast.type === "error"
                            ? "bg-rose-500 text-white"
                            : "bg-gray-900 text-gray-100"
                    }
                    ${toast.extraClassName} /* Estilos personalizados */
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

            <ToastPrimitives.Viewport className="fixed bottom-4 right-4 w-[320px]" />
        </ToastPrimitives.Provider>
    );

    return { showToast, ToastComponent };
}
