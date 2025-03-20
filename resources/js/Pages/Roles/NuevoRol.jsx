import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput.jsx";
import { Head, useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import useToast from "@/Components/Toast";
import { getRutasPadre, getRutaPorId } from "../../Utils/rutas.js";
import GuestLayout from "@/Layouts/GuestLayout";

export default function NuevoRol({ auth }) {
    const { data, setData, post, reset, processing, errors } = useForm({
        codigo: "",
        nombre: "",
        descripcion: "",
        subrutas: [], // Array para almacenar los IDs de las subrutas seleccionadas
    });

    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();

    // Mostrar toast si hay respuesta del servidor
    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const lista_rutas = getRutasPadre(); // Obtener las rutas padre
    const lista_accesos = usePage().props.accesos_sistema; // Accesos agrupados desde el controlador
    const [rutas, setRutas] = useState([]);
    const [rutaPadreSeleccionada, setRutaPadreSeleccionada] = useState(null);
    const [subRutasFiltradas, setSubRutasFiltradas] = useState([]);

    useEffect(() => {
        setRutas(lista_rutas || []);
    }, []);

    // Manejar cambio de ruta padre
    const handleRutaChange = (e) => {
        const idRutaPadre = e.target.value; // Obtener el ID de la ruta padre seleccionada
        const rutaPadre = getRutaPorId(lista_rutas, idRutaPadre); // Buscar la ruta padre por su ID

        if (rutaPadre) {
            setRutaPadreSeleccionada(rutaPadre);

            // Extraer la primera parte de la ruta (antes del primer '/')
            const rutaPadreKey = rutaPadre.ruta.split("/")[0];

            // Buscar el grupo de subrutas correspondiente en lista_accesos
            const grupoEncontrado = lista_accesos.find(
                (grupo) => grupo.rutaPadre === rutaPadreKey
            );

            if (grupoEncontrado) {
                setSubRutasFiltradas(grupoEncontrado.subRutas); // Establecer las subrutas filtradas
            } else {
                setSubRutasFiltradas([]); // Si no se encuentra, limpiar las subrutas
            }
        } else {
            setRutaPadreSeleccionada(null);
            setSubRutasFiltradas([]);
        }
    };

    const submit = (e) => {
        e.preventDefault();

        // Validación simple en cliente
        if (!data.nombre || !data.codigo || data.subrutas.length === 0) {
            showToast(
                "Por favor, llena todos los campos obligatorios.",
                "error"
            );
            return;
        }

        post(route("roles/new"), {
            onError: (serverErrors) => {
                // Manejar error duplicado de código con el campo correcto
                if (serverErrors.nombre) {
                    showToast(serverErrors.nombre, "error");
                }
            },
            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Nuevo Rol" />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nuevo Rol</h2>
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
                            isFocused={true}
                            onChange={(e) =>
                                setData("codigo", e.target.value.toUpperCase())
                            }
                        />
                        <InputError message={errors.codigo} className="mt-2" />
                    </div>

                    {/* Input de Descripcion */}
                    <div>
                        <InputLabel
                            htmlFor="descripcion"
                            value="Descripción (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="descripcion"
                            type="text"
                            name="descripcion"
                            value={data.descripcion}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) =>
                                setData("descripcion", e.target.value)
                            }
                        />
                        <InputError
                            message={errors.descripcion}
                            className="mt-2"
                        />
                    </div>

                    {/* Input de Ruta Padre */}
                    <div>
                        <InputLabel
                            htmlFor="ruta"
                            value="Accesos"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="ruta"
                            name="ruta"
                            options={rutas}
                            value={rutaPadreSeleccionada?.id || ""}
                            onChange={handleRutaChange}
                            placeholder="Selecciona una ruta"
                            closeOnSelect={true}
                        />

                        {/* Checkboxes para las subrutas filtradas */}
                        <div className="pl-2 sm:pl-3 mt-8 grid gap-2 max-h-28">
                            {subRutasFiltradas.map((subRuta) => (
                                <div key={subRuta.id} className="block">
                                    <label className="flex items-center">
                                        <input
                                            type="checkbox"
                                            name="subrutas"
                                            value={subRuta.id}
                                            checked={data.subrutas.includes(
                                                subRuta.id
                                            )}
                                            onChange={(e) => {
                                                const isChecked =
                                                    e.target.checked;
                                                setData(
                                                    "subrutas",
                                                    isChecked
                                                        ? [
                                                              ...data.subrutas,
                                                              subRuta.id,
                                                          ] // Agregar el id al array
                                                        : data.subrutas.filter(
                                                              (id) =>
                                                                  id !==
                                                                  subRuta.id
                                                          ) // Remover el id del array
                                                );
                                            }}
                                            className="form-checkbox h-4 w-4 text-[#0B6ACB] rounded focus:ring-[#0B6ACB]"
                                        />
                                        <span className="ms-2 text-sm font-medium text-gray-600">
                                            {subRuta.name}
                                        </span>
                                    </label>
                                </div>
                            ))}
                        </div>

                        <InputError message={errors.rutapadre} />
                    </div>

                    {/* Botón de Registrar */}
                    <div className="grid grid-flow-row items-center">
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
