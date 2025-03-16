import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput.jsx";
import { Head, useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import {
    getDepartamentos,
    getProvincias,
    getProvinciaById,
    getDepartamentoById,
    getDepartamentoByName,
    getProvinciaByName,
    getProvinciasByDepartment,
    getDepartamentoByProvincia,
} from "../../Utils/ubigeo.js";
import useToast from "@/Components/Toast";

export default function NuevoAlmacen({ auth }) {
    const { data, setData, post, reset, processing, transform, errors } =
        useForm({
            nombre: "",
            codigo: "",
            departamento: getDepartamentoByName(auth?.empresa?.departamento)
                ? getDepartamentoByName(auth?.empresa?.departamento).id
                : "",
            ciudad: getProvinciaByName(auth?.empresa?.ciudad)
                ? getProvinciaByName(auth?.empresa?.ciudad).id
                : "",
            direccion: "",
        });

    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();

    // Mostrar toast si hay respuesta del servidor
    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const [departamentos, setDepartamentos] = useState([]);
    const [provincias, setProvincias] = useState([]);

    useEffect(() => {
        setDepartamentos(getDepartamentos());
        if (auth?.empresa?.departamento) {
            setProvincias(
                getProvinciasByDepartment(
                    getDepartamentoByName(auth?.empresa?.departamento).id
                )
            );
        } else {
            setProvincias(getProvincias());
        }
    }, []);

    // Se utiliza el manejo de cambios propuesto para el departmento.
    const handleDepartamentoChange = (e) => {
        const department_id = e.target.value;
        setData("departamento", department_id);
        setProvincias(getProvinciasByDepartment(department_id));
        setData("ciudad", "");
    };

    // Se utiliza el manejo de cambios propuesto para la provincia y se sincroniza el departamento.
    const handleProvinciaChange = (e) => {
        const provincia_id = e.target.value;
        const departamento = getDepartamentoByProvincia(provincia_id);
        // Se actualiza la lista de provincias en función del departamento obtenido
        setProvincias(getProvinciasByDepartment(departamento.id));
        setData("departamento", departamento.id);
        setData("ciudad", provincia_id);
    };

    const submit = (e) => {
        e.preventDefault();

        // Validación simple en cliente; si falta un campo obligatorio no se envía
        if (
            !data.nombre ||
            !data.codigo ||
            !data.departamento ||
            !data.ciudad ||
            !data.direccion
        ) {
            showToast("Por favor, llena todos los campos.", "error");
            return;
        }

        // Se transforma el ID del departamento y la ciudad a nombres, de acuerdo al controlador.
        transform((data) => ({
            ...data,
            departamento: getDepartamentoById(data.departamento).name,
            ciudad: getProvinciaById(data.ciudad).name,
        }));

        post(route("warehouses/new"), {
            onError: (serverErrors) => {
                // Manejar error duplicado de código con el campo correcto
                if (serverErrors.codigo) {
                    showToast(serverErrors.codigo, "error");
                }
            },
            onSuccess: () => {
                reset("nombre", "codigo", "direccion");
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Nuevo Almacén" />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nuevo Almacén</h2>
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

                    {/* Input de Código */}
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
                            onChange={(e) =>
                                setData("codigo", e.target.value.toUpperCase())
                            }
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
                            closeOnSelect={true}
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
                            closeOnSelect={true}
                        />
                        <InputError message={errors.ciudad} />
                    </div>

                    {/* Input de Dirección */}
                    <div>
                        <InputLabel
                            htmlFor="direccion"
                            value="Dirección"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="direccion"
                            type="text"
                            name="direccion"
                            value={data.direccion}
                            className="mt-1 block w-full"
                            onChange={(e) =>
                                setData("direccion", e.target.value)
                            }
                        />
                        <InputError
                            message={errors.direccion}
                            className="mt-2"
                        />
                    </div>

                    {/* Botón de Registrar */}
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
