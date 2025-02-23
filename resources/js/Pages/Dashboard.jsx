import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";
import { useState } from "react";

export default function Dashboard({ auth }) {
    const appName = import.meta.env.VITE_APP_NAME || "Laravel";
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={`Dashboard`} />
            <p>Yupi yupi!!! Hola {auth.user.nombre}!</p>
        </AuthenticatedLayout>
    );
}
