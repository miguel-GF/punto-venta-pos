<template>
  <q-item-label class="bg-grey-2" header>{{ datos.seccion || '--' }}</q-item-label>
  <q-item dense v-for="(opc, i) in datos.opciones" clickable v-ripple @click="onClick(opc)" :key="i">
    <q-item-section>{{ opc.label || '--' }}</q-item-section>
    <q-item-section avatar>
      <q-icon color="secondary" :name="opc.icon" />
    </q-item-section>
  </q-item>
</template>

<script>
import { loading } from '../Utils/loading';
export default {
  props: {
    datos: {
      type: Object,
      default: () => { },
    }
  },
  methods: {
    onClick(opcion) {
      loading(true, 'Cargando ...')
      if (opcion.tag == 'cerrarSesion') {
        this.logout();
      } else {
        this.redirect(opcion);
      }
    },
    logout() {
      this.$inertia.post("/logout");
    },
    redirect(opcion) {
      let url;
      switch (opcion.tag) {
        case 'productos':
          url = "/productos";
          break;
      
        case 'agregarProducto':
          url = "/productos/agregar";
          break;

        case 'agregarMovimientoInventario':
          url = "/inventarios/agregar";
          break;

        case 'agregarVenta':
        url = "/ventas/agregar";
        break;

        case 'ventas':
        url = "/ventas";
        break;
        
        case 'clientes':
          url = "/clientes";
          break;

        case 'agregarCliente':
          url = "/clientes/agregar";
          break;

        case 'usuarios':
          url = "/usuarios";
          break;

        case 'agregarUsuario':
          url = "/usuarios/agregar";
          break;

        case 'lecturaImpresora':
          url = "/configuraciones/usuario";
          break;

        case 'edicionSucursalDefault':
          url = "/configuraciones/sucursalDefault";
          break;

        default:
          url = "";
          break;
      }
      this.$inertia.get(url);
    }
  },
};
</script>