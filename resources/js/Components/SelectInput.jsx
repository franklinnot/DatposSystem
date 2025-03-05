
// Components/SelectInput.js
import { forwardRef, useEffect, useRef, useState } from "react";

export default forwardRef(function SelectInput(
  {
    options = [],
    value = "",
    className = "",
    placeholder = "Selecciona...",
    isDisabled = false,
    ...props
  },
  ref
) {
  const selectRef = ref ? ref : useRef();
  const [selectedValue, setSelectedValue] = useState(value);
  const [hasValue, setHasValue] = useState(value !== "");

  // Sincroniza con el valor de props
  useEffect(() => {
    setSelectedValue(value);
    setHasValue(value !== "");
  }, [value]);

  // Funcion para manejar el cambio de valor en el select
  const handleChange = (e) => {
      const newValue = e.target.value;
      setSelectedValue(newValue);
      setHasValue(newValue !== "");
      props.onChange?.(e);
  };

  return (
    <select
      {...props}
      ref={selectRef}
      value={selectedValue}
      onChange={handleChange}
      className={`
        ${hasValue ? "bg-[#e8f0fe]" : "bg-[#f2f2f2]"}
        border-0 focus:border-[#0875E4] focus:ring-[#0875E4]
        rounded-lg shadow-sm mt-1 block w-full ${className}
      `}
      disabled={isDisabled}
    >
      {options.length ? (
        <>
          <option value="" disabled hidden>
            {placeholder}
          </option>
          {options.map((option) => (
            <option key={option.id} value={option.id}>
              {option.name}
            </option>
          ))}
        </>
      ) : (
        <option disabled>-- Sin opciones --</option>
      )}
    </select>
  );
});