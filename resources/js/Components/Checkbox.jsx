export default function Checkbox({ className = '', ...props }) {
    return (
        <input
            {...props}
            type="checkbox"
            className={
                "rounded border-gray-300 text-[#0875E4] shadow-sm focus:ring-[#0875E4] " +
                className
            }
        />
    );
}
