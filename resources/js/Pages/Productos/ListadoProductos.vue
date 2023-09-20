<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-table
        title="Lista de Productos"
        :rows="productos"
        :columns="columns"
        :rows-per-page-options="[10]"
        :filter="filter"
        class="tabla-listado striped-table"
        row-key="producto_id" 
      >
        <template v-slot:top-right>
          <q-input outlined dense debounce="300" v-model="filter" placeholder="Búsqueda" 
            class="q-mr-md"
          >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
          <q-btn @click="irAgregar()" dense icon-right="add" color="primary">
            <div class="q-px-sm">Agregar Producto</div>
          </q-btn>
        </template>
        <template #body-cell-descripcion="{row}">
          <q-td class="text-left">
            <div class="w350 break-words-3-lines">
              {{ row.descripcion || '--' }}
            </div>
          </q-td>
        </template>
        <template #body-cell-opciones="{row}">
          <q-td class="text-center">
            <q-btn @click="irEditar(row)" dense flat class="q-mr-md" color="primary" icon="edit" size="12px">
              <q-tooltip>Editar</q-tooltip>
            </q-btn>
            <q-btn @click="confirmarEliminar(row)" dense flat color="negative" icon="delete" size="12px">
              <q-tooltip>Eliminar</q-tooltip>
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </div>
    <!-- MODALES -->
    <!-- DIALOGO DE CONFIRMACION -->
    <the-dialog-confirm
      :mostrar="mostrarModalConfirmar"
      titulo="Eliminar Producto"
      :mensaje="mensajeConfirmacion"
      banner="eliminar"
      @cerrar="mostrarModalConfirmar = false"
      @aceptar="eliminar()"
    />

    <!-- DIALOGO DE EXITO -->
    <the-dialog-response
      :mostrar="mostrarModalExito"
      :mensaje="mensaje"
      @aceptar="mostrarModalExito = false"
    />
  </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import { formatearNumero } from '../../Utils/format';
import { loading } from '../../Utils/loading';
import { obtenerFechaHoraActualOperacion } from '../../Utils/date';
export default {
  props: ["productos","status","mensaje"],
  components: { MainLayout },
  data() {
    return {
      filter: "",
      columns: [
        {
          name: 'clave',
          label: 'Clave',
          align: 'left',
          field: row => row.clave,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'codigoBarras',
          label: 'CÓDIGO BARRAS',
          align: 'left',
          field: row => row.codigo_barras,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'nombre',
          label: 'Nombre',
          align: 'left',
          field: row => row.nombre,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'descripcion',
          label: 'Descripción',
          align: 'left',
          field: row => row.descripcion,
          format: val => val || '--',
          sortable: true,
          headerClasses: "w250"
        },
        {
          name: 'precio',
          label: 'Precio',
          align: 'right',
          field: row => row.precio,
          format: val => formatearNumero(val, 'currency'),
          sortable: true
        },
        {
          name: 'existencia',
          label: 'Existencia',
          align: 'right',
          field: row => row.existencia,
          format: val => formatearNumero(val),
          sortable: true
        },
        {
          name: 'opciones',
          label: '',
          align: 'center',
        },
      ],
      mostrarModalConfirmar: false,
      mostrarModalExito: false,
      mensajeConfirmacion: "",
      productoEliminarId: "",
    }
  },
  created() {
    loading(false);
  },
  updated() {
    loading(false);
    if (this.status == 200) {
      this.mostrarModalExito = true;
    } else if (this.status == 300) {
      notify(this.mensaje, 'error');
    }
  },
  methods: {
    irAgregar() {
      loading(true);
      this.$inertia.get('/productos/agregar');
    },
    irEditar({ producto_id }) {
      loading(true);
      this.$inertia.get('/productos/editar/' + producto_id);
    },
    confirmarEliminar({ producto_id, clave, nombre }) {
      this.productoEliminarId = producto_id;
      this.mensajeConfirmacion = `¿Está seguro de eliminar el producto ${clave} - ${nombre}?`;
      this.mostrarModalConfirmar = true;
    },
    eliminar() {
      this.mostrarModalConfirmar = false;
      loading(true, 'Eliminando');
      const form = {
        fechaActual: obtenerFechaHoraActualOperacion(),
      }
      this.$inertia.post('/productos/eliminar/' + this.productoEliminarId, form);
    }
  }
};
</script>
