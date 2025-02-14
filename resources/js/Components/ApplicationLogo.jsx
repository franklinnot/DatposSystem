export default function ApplicationLogo({ size = 84 }) {
    const scale = 1.3 / 84;

    return (
        <div
            className="grid place-items-center text-center rounded-full overflow-hidden bg-gradient-to-tr from-[#085078] to-[#0875E4] drop-shadow-xl transition-transform duration-300 ease-in-out 
                        sm:scale-105 md:scale-110"
            style={{ width: `${size}px`, height: `${size}px` }}
        >
            <h1
                className="italic font-black text-white antialiased selection:bg-transparent selection:text-inherit"
                style={{ fontSize: `${scale * size}rem` }}
            >
                D'POS
            </h1>
        </div>
    );
}
