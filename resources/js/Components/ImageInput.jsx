import { useState, useRef, useEffect, useCallback } from "react";

export default function ImageInput({
    className = "",
    value,
    onChange,
    ...props
}) {
    const [preview, setPreview] = useState(value || null);
    const inputRef = useRef();

    useEffect(() => {
        setPreview(value);
    }, [value]);

    const handleImageChange = useCallback(
        (e) => {
            const file = e.target.files[0];
            if (file) {
                const previewURL = URL.createObjectURL(file);
                setPreview(previewURL);
                onChange?.(file, previewURL); // Enviar también la URL de la vista previa
            }
        },
        [onChange]
    );

    const handleClick = useCallback(() => {
        inputRef.current?.click();
    }, []);

    const handleRemoveImage = useCallback(
        (e) => {
            e.stopPropagation();
            setPreview(null);
            onChange?.(null);
            if (inputRef.current) {
                inputRef.current.value = ""; // Reinicia el input file
            }
        },
        [onChange]
    );

    return (
        <div
            className={`relative flex flex-col items-center justify-center border-2 border-dashed border-gray-400 rounded-lg p-1 bg-gray-100 hover:bg-gray-200 cursor-pointer transition-all duration-200 ${className}`}
            style={{ height: "164px", width: "100%" }}
            onClick={handleClick}
        >
            <input
                type="file"
                accept="image/*"
                ref={inputRef}
                className="hidden"
                onChange={handleImageChange}
                {...props}
            />
            {preview ? (
                <>
                    <img
                        src={preview}
                        alt="Preview"
                        className="h-full w-full object-contain rounded-lg"
                    />
                    <button
                        onClick={handleRemoveImage}
                        className="absolute top-[-0.5rem] right-[-0.5rem] text-xs pt-[0.29rem] w-6 h-6 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition"
                    >
                        ✕
                    </button>
                </>
            ) : (
                <>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        strokeWidth={1.5}
                        stroke="currentColor"
                        className="w-10 h-10 text-gray-400"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"
                        />
                    </svg>
                    <p className="text-gray-400 text-sm mt-2">
                        Sube una imagen
                    </p>
                </>
            )}
        </div>
    );
}
