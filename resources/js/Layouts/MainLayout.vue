<template>
	<!-- view="hHh lpR fFf " -->
	<q-layout>
		<q-page-container class="row col-12 column window-height">
			<div>
				<q-header reveal elevated class="bg-primary text-white">
					<q-toolbar>
						<q-btn dense flat round icon="las la-bars" @click="toggleLeftDrawer" />

						<q-toolbar-title>
							<!-- <q-avatar>
								<img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
							</q-avatar>
							Home-->
							<Link @click="loading(true)" class="cursor-pointer" as="label" v-if="$page.props.usuario.tipo == 'sistema'"
								href="/dashboard">Home</Link>
						</q-toolbar-title>
						<div class="text-left q-pr-sm">
							{{ `${$page.props.usuario.nombre || '--'} - ${$page.props.usuario.correo || '--'}` }}
						</div>

						<q-btn dense flat round icon="las la-search" @click="mostrarModalProducto(true)" class="q-mr-sm" />
						<q-btn dense flat round icon="las la-cog" @click="toggleRightDrawer" />
					</q-toolbar>
				</q-header>
			</div>
			<div class="col full-height">
				<div class="bg-grey-4 d-block full-height">
					<main class="full-height">
						<slot />
					</main>
				</div>
			</div>
		</q-page-container>

		<q-drawer v-model="leftDrawerOpen" side="left" overlay bordered>
			<q-list bordered>
				<template v-if="$page.props.usuario.tipo == 'sistema'">
					<template v-for="(opc, i) in opcionesSistema" :key="i">
						<ItemMenu :datos="opc" />
					</template>
				</template>
			</q-list>
		</q-drawer>

		<q-drawer v-model="rightDrawerOpen" side="right" overlay bordered>
			<q-list bordered>
				<template v-for="(opc, i) in opcionesConfiguracion" :key="i">
					<ItemMenu :datos="opc" />
				</template>
			</q-list>
		</q-drawer>

		<!-- <q-page-container>
			<div class="bg-grey-4 d-block height-layout-main">
				<main>
					<slot />
				</main>
			</div>
		</q-page-container> -->

		<!-- MODALES -->
		<dialog-search-product
			:mostrar="modalProducto"
			@cerrar="mostrarModalProducto(false)"
		/>
	</q-layout>
</template>

<script setup>
import { ref } from "vue";
import ItemMenu from "../Components/ItemMenu.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { loading } from "../Utils/loading";
const leftDrawerOpen = ref(false);
const rightDrawerOpen = ref(false);
const modalProducto = ref(false);
const opcionesSistema = ref([
	{ seccion: "Productos", opciones: [
		{	label: "Productos", tag: "productos", icon: "las la-tags" },
		{ label: "Agregar Producto", tag: "agregarProducto", icon: "las la-plus" },
		{ label: "Entradas y Salidas", tag: "agregarMovimientoInventario", icon: "las la-exchange-alt" },
	]},
	{ seccion: "Ventas", opciones: [
		{	label: "Ventas", tag: "ventas", icon: "las la-list" },
		{	label: "Nueva Venta", tag: "agregarVenta", icon: "las la-file-invoice-dollar" },
	]},
	{ seccion: "Clientes", opciones: [
		{ label: "Clientes", tag: "clientes", icon: "las la-user" },
		{ label: "Agregar Cliente", tag: "agregarCliente", icon: "las la-user-plus" },
	]},
	{ seccion: "Usuarios", opciones: [
		{ label: "Usuarios", tag: "usuarios", icon: "las la-user-shield" },
		{ label: "Agregar Usuario", tag: "agregarUsuario", icon: "las la-user-plus" },
	]},
]);
const opcionesConfiguracion = ref([
	{ seccion: "Configuraciones", opciones: [
    { label: "Lectura e Impresora", tag: "lecturaImpresora", icon: "las la-print" },
    { label: "Editar Sucursal", tag: "edicionSucursalDefault", icon: "las la-store-alt" },
    { label: "Cerrar Sesión", tag: "cerrarSesion", icon: "las la-sign-out-alt" },
	]},
]);
const toggleLeftDrawer = () => leftDrawerOpen.value = !leftDrawerOpen.value;
const toggleRightDrawer = () => rightDrawerOpen.value = !rightDrawerOpen.value;
const mostrarModalProducto = (value) => modalProducto.value = value;
</script>

<style scoped>
.height-layout-main {
	min-height: calc(100vh - 50px);
}
</style>