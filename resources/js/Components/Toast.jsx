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
    // Single toast state
    const [toast, setToast] = useState(null);
    const timerRef = useRef(null);

    // Fixed duration constant
    const TOAST_DURATION = 8000; // 8 seconds

    const showToast = (
        message,
        type = "default",
        duration = TOAST_DURATION,
        extraClassName = ""
    ) => {
        // Clear any existing timer
        if (timerRef.current) {
            clearTimeout(timerRef.current);
        }

        // Set the new toast
        setToast({
            message,
            type,
            extraClassName,
            visible: true,
            timestamp: Date.now(),
        });

        // Set timer to hide the toast
        timerRef.current = setTimeout(() => {
            setToast((currentToast) =>
                currentToast ? { ...currentToast, visible: false } : null
            );

            // Clear toast data after animation completes
            setTimeout(() => {
                setToast(null);
            }, 300);
        }, duration);
    };

    const handleCloseToast = () => {
        // Clear any existing timer
        if (timerRef.current) {
            clearTimeout(timerRef.current);
        }

        // Mark toast as not visible to trigger exit animation
        setToast((currentToast) =>
            currentToast ? { ...currentToast, visible: false } : null
        );

        // Clear toast data after animation completes
        setTimeout(() => {
            setToast(null);
        }, 300);
    };

    // Cleanup on unmount
    useEffect(() => {
        return () => {
            if (timerRef.current) {
                clearTimeout(timerRef.current);
            }
        };
    }, []);

    const ToastComponent = () => (
        <div className="fixed right-4 z-[9999] w-[320px] sm:w-max">
            {toast && (
                <div
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
                        transition-all duration-300 ease-in-out
                        ${
                            toast.visible
                                ? "opacity-100 translate-y-0"
                                : "opacity-0 translate-y-[-10px]"
                        }
                    `}
                >
                    {ToastTypeIcons?.[toast.type] || ToastTypeIcons.close}

                    <div className="flex-1 min-w-0">
                        <div className="font-semibold text-sm leading-6">
                            {toast.message}
                        </div>
                    </div>

                    <button
                        className="ml-4 p-1 hover:bg-white/20 rounded focus:outline-none"
                        onClick={handleCloseToast}
                    >
                        {ToastTypeIcons.close}
                        <span className="sr-only">Cerrar</span>
                    </button>
                </div>
            )}
        </div>
    );

    return { showToast, ToastComponent };
}
