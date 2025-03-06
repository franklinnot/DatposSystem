import React, { useState, useEffect } from "react";
import * as ToastPrimitives from "@radix-ui/react-toast";

export default function useToast() {
    const [message, setMessage] = useState(null);
    const [open, setOpen] = useState(false);

    const showToast = (msg) => {
        setMessage(msg);
        setOpen(true);
    };

    useEffect(() => {
        if (open) {
            const timer = setTimeout(() => setOpen(false), 3000);
            return () => clearTimeout(timer);
        }
    }, [open]);

    const ToastComponent = () => (
        <ToastPrimitives.Provider>
            <ToastPrimitives.Root
                open={open}
                onOpenChange={setOpen}
                className="fixed bottom-4 right-4 z-50 bg-gray-900 text-white px-4 py-3 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 ease-in-out"
            >
                <ToastPrimitives.Title className="font-semibold">
                    {message}
                </ToastPrimitives.Title>
                <ToastPrimitives.Close
                    className="ml-auto text-gray-400 hover:text-gray-200 transition cursor-pointer"
                    onClick={() => setOpen(false)}
                >
                    ✖
                </ToastPrimitives.Close>
            </ToastPrimitives.Root>
            <ToastPrimitives.Viewport className="fixed bottom-4 right-4 w-auto max-w-sm" />
        </ToastPrimitives.Provider>
    );

    return { showToast, ToastComponent };
}
