import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput.jsx";
import { Head, Link, useForm } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";
import { useState } from "react";
import {
    getProvincias,
    getDepartamentos,
    getProvinciaById,
    getDepartamentoById,
    getProvinciasByDepartment,
    getDepartamentoByProvincia,
} from "../../Utils/ubigeo.js";
import useToast from "@/Components/Toast";

export default function NuevaSucursal({ auth }) {
    // datos necesarios para el post y otras props
    const { data, setData, post, processing, errors, transform, reset } =
        useForm({
            // obligatorio
            nombre: "",
            codigo: "",
            // opcional
            departamento: "",
            ciudad: "",
            direccion: "",
            telefono: "",
            id_empresa: auth?.empresa?.id_empresa
                ? auth?.empresa?.id_empresa
                : "",
        });

    // Toast para mostrar resultados
    const { showToast, ToastComponent } = useToast();

    // mensaje flash
    const flash = usePage().props?.flash;
    useEffect(() => {
        if (flash?.message) {
            showToast(flash.message, "success");
        }
    }, [flash]);


    // Estados para manejar la seleccion de departamento y provincia
    const [departamentos, setDepartamentos] = useState();
    const [provincias, setProvincias] = useState();

    useEffect(() => {
        setDepartamentos(getDepartamentos());
        setProvincias(getProvincias());
    }, []);

    // Función para manejar el cambio de departamento
    const handleDepartamentoChange = (e) => {
        const department_id = e.target.value;
        setData("departamento", department_id);
        setProvincias(getProvinciasByDepartment(department_id)); // Filtra provincias por departamento
        setData("ciudad", ""); // Resetea la ciudad al cambiar de departamento
    };

    // Funcion para manejar el cambio de provincia
    const handleProvinciaChange = (e) => {
        const provincia_id = e.target.value;
        const departamento = getDepartamentoByProvincia(provincia_id);
        setProvincias(getProvinciasByDepartment(departamento.id));
        setData("departamento", departamento.id); // Resetea el departamento al cambiar de provincia
        setData("ciudad", provincia_id);
    };

    // Función para enviar el formulario
    const submit = (e) => {
        e.preventDefault();

        // Verificar si todos los campos obligatorios están llenos
        if (
            !data.nombre ||
            !data.codigo ||
            !data.departamento ||
            !data.ciudad ||
            !data.direccion
        ) {
            showToast(
                "Por favor, llena todos los campos obligatorios.",
                "error"
            );
            return;
        }

        transform((data) => ({
            ...data,
            departamento: data.departamento
                ? getDepartamentoById(data.departamento).name
                : "",
            ciudad: data.ciudad ? getProvinciaById(data.ciudad).name : "",
        }));

        post(route("stores/new"), {
            onFinish: () => {
                reset();
                processing = false;
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title={`Nueva Sucursal`} />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nueva Sucursal</h2>
                </div>
                <form
                    onSubmit={submit}
                    className="grid gap-6 sm:gap-8 justify-self-center bg-white rounded-2xl max-sm:w-full max-sm:max-w-[448px] sm:w-[75%] sm:max-w-[612px] px-5 py-6"
                >
                    {/* Input de Nombre */}
                    <div>
                        <InputLabel
                            htmlFor="nombre"
                            value="Nombre"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="nombre"
                            type="text"
                            name="nombre"
                            value={data.nombre}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) => setData("nombre", e.target.value)}
                        />

                        <InputError message={errors.nombre} className="mt-2" />
                    </div>

                    {/* Input de Codigo */}
                    <div>
                        <InputLabel
                            htmlFor="codigo"
                            value="Código"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="codigo"
                            type="text"
                            name="codigo"
                            value={data.codigo}
                            className="mt-1 block w-full"
                            isFocused={false}
                            onChange={(e) => setData("codigo", e.target.value)}
                        />

                        <InputError message={errors.codigo} className="mt-2" />
                    </div>

                    {/* Input de Departamento */}
                    <div>
                        <InputLabel
                            htmlFor="departamento"
                            value="Departamento"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="departamento"
                            name="departamento"
                            options={departamentos}
                            value={data.departamento}
                            onChange={handleDepartamentoChange}
                            placeholder="Selecciona un departamento"
                            className=""
                        />
                        <InputError message={errors.departamento} />
                    </div>

                    {/* Input de Ciudad */}
                    <div>
                        <InputLabel
                            htmlFor="ciudad"
                            value="Ciudad"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="ciudad"
                            name="ciudad"
                            options={provincias}
                            value={data.ciudad}
                            onChange={handleProvinciaChange}
                            placeholder="Selecciona una ciudad"
                            className=""
                            // isDisabled={!data.departamento}
                        />
                        <InputError message={errors.ciudad} />
                    </div>

                    {/* Input de Direccion */}
                    <div>
                        <InputLabel
                            htmlFor="direccion"
                            value="Direccion"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="direccion"
                            type="text"
                            name="direccion"
                            value={data.direccion}
                            className="mt-1 block w-full"
                            isFocused={false}
                            onChange={(e) =>
                                setData("direccion", e.target.value)
                            }
                        />

                        <InputError
                            message={errors.direccion}
                            className="mt-2"
                        />
                    </div>

                    {/* Input de Telefono */}
                    <div>
                        <InputLabel
                            htmlFor="telefono"
                            value="Teléfono (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="telefono"
                            type="text"
                            name="telefono"
                            value={data.telefono}
                            className="mt-1 block w-full"
                            isFocused={false}
                            onChange={(e) =>
                                setData("telefono", e.target.value)
                            }
                        />

                        <InputError
                            message={errors.telefono}
                            className="mt-2"
                        />
                    </div>

                    {/* Boton de Registrar */}
                    <div className="grid grid-flow-row items-center mt-3">
                        <PrimaryButton
                            className="font-medium text-md w-full justify-center rounded-lg bg-gradient-to-r from-[#0B6ACB] via-[#0875E4] to-[#0B6ACB] hover:bg-[#3c78fa] focus:ring-[#3c78fa]"
                            disabled={processing}
                        >
                            Registrar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
