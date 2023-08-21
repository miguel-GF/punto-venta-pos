<template>
	<!-- view="hHh lpR fFf " -->
	<q-layout>
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

				<q-btn dense flat round icon="las la-cog" @click="toggleRightDrawer" />
			</q-toolbar>
		</q-header>

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

		<q-page-container>
			<div class="bg-grey-4 d-block height-layout-main">
				<main>
					<slot />
				</main>
			</div>
		</q-page-container>
	</q-layout>
</template>

<script setup>
import { ref } from "vue";
import ItemMenu from "../Components/ItemMenu.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { loading } from "../Utils/loading";
const leftDrawerOpen = ref(false);
const rightDrawerOpen = ref(false);
const opcionesSistema = ref([
	{ seccion: "Productos", opciones: [
		{	label: "Productos", tag: "productos", icon: "las la-tags" },
		{ label: "Agregar Producto", tag: "agregarProducto", icon: "las la-plus" },
	]},
	{ seccion: "Clientes", opciones: [
		{ label: "Clientes", tag: "clientes", icon: "las la-user" },
		{ label: "Agregar Cliente", tag: "agregarCliente", icon: "las la-user-plus" },
	]},
	
]);
const opcionesConfiguracion = ref([
	{ seccion: "Configuraciones", opciones: [
			{ label: "Cerrar Sesión", tag: "cerrarSesion", icon: "las la-sign-out-alt" },
	]},
]);
const toggleLeftDrawer = () => leftDrawerOpen.value = !leftDrawerOpen.value;
const toggleRightDrawer = () => rightDrawerOpen.value = !rightDrawerOpen.value;
</script>

<style scoped>
.height-layout-main {
	min-height: calc(100vh - 50px);
}
</style>