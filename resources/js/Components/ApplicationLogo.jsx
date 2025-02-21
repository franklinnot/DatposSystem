export default function ApplicationLogo({ scaleText = 1.3, size = 84, fontWeight = 900 }) {
    const scale = scaleText / 84;

    return (
        <div
            className="grid place-items-center text-center rounded-full overflow-hidden bg-gradient-to-tr from-[#085078] to-[#0875E4] drop-shadow-xl  
                        sm:scale-105 md:scale-110"
            style={{ width: `${size}px`, height: `${size}px` }}
        >
            <h1
                className="italic text-white antialiased selection:bg-transparent selection:text-inherit"
                style={{
                    fontSize: `${scale * size}rem`,
                    fontWeight: fontWeight, 
                }}
            >
                D'POS
            </h1>
        </div>
    );
}
