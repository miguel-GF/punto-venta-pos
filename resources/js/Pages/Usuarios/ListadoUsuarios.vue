<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-table title="Lista de Usuarios" :rows="usuarios" :columns="columns" :rows-per-page-options="[10]"
        :filter="filter" class="tabla-listado striped-table" row-key="usuario_id">
        <template v-slot:top-right>
          <q-input outlined dense debounce="300" v-model="filter" placeholder="Búsqueda" class="q-mr-md">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
          <q-btn @click="irAgregar()" dense icon-right="add" color="primary">
            <div class="q-px-sm">Agregar Usuario</div>
          </q-btn>
        </template>
        <template #body-cell-descripcion="{ row }">
          <q-td class="text-left">
            <div class="w350 break-words-3-lines">
              {{ row.descripcion || '--' }}
            </div>
          </q-td>
        </template>
        <template #body-cell-opciones="{ row }">
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
    <the-dialog-confirm :mostrar="mostrarModalConfirmar" titulo="Eliminar Usuario" :mensaje="mensajeConfirmacion"
      banner="eliminar" @cerrar="mostrarModalConfirmar = false" @aceptar="eliminar()" />

    <!-- DIALOGO DE EXITO -->
    <the-dialog-response :mostrar="mostrarModalExito" :mensaje="mensaje" @aceptar="mostrarModalExito = false" />
  </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import { loading } from '../../Utils/loading';
import { obtenerFechaHoraActualOperacion, obtenerFechaHoraLeible } from '../../Utils/date';
export default {
  name: "ListadoUsuarios",
  props: ["usuarios", "status", "mensaje"],
  components: { MainLayout },
  data() {
    return {
      filter: "",
      columns: [
        {
          name: 'nombre',
          label: 'nombre',
          align: 'left',
          field: row => row.nombre,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'correo',
          label: 'Correo',
          align: 'left',
          field: row => row.correo,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'fechaRegistro',
          label: 'Fecha Registro',
          align: 'left',
          field: row => row.registro_fecha,
          format: val => obtenerFechaHoraLeible(val),
          sortable: true,
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
      usuarioEliminarId: "",
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
      this.$inertia.get('/usuarios/agregar');
    },
    irEditar({ usuario_id }) {
      loading(true);
      this.$inertia.get('/usuarios/editar/' + usuario_id);
    },
    confirmarEliminar({ usuario_id, nombre, correo }) {
      this.usuarioEliminarId = usuario_id;
      this.mensajeConfirmacion = `¿Está seguro de eliminar el usuario ${nombre || '--'} - ${correo || '--'}?`;
      this.mostrarModalConfirmar = true;
    },
    eliminar() {
      this.mostrarModalConfirmar = false;
      loading(true, 'Eliminando');
      const form = {
        fechaActual: obtenerFechaHoraActualOperacion(),
      }
      this.$inertia.post('/usuarios/eliminar/' + this.usuarioEliminarId, form);
    }
  }
};
</script>
