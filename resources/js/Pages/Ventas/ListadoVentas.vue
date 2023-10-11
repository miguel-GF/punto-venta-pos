<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-table title="Lista de Ventas" :rows="ventas" :columns="columns" :rows-per-page-options="[10]" :filter="filter"
        class="tabla-listado striped-table" row-key="venta_id"
        no-data-label="Sin registros encontrados"
        no-results-label="Sin registros encontrados"
      >
        <template v-slot:top-right>
          <q-input outlined dense debounce="300" v-model="filter" placeholder="Búsqueda" class="q-mr-md">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
          <q-btn @click="irAgregar()" dense icon-right="add" color="primary">
            <div class="q-px-sm">Agregar Venta</div>
          </q-btn>
        </template>
        <template #body-cell-folio="{ row }">
          <q-td class="text-left">
            <Link @click="activarLoading()" as="a" :href="`/ventas/detalle/${row.venta_id}`">{{ row.serie_folio || '--'
            }}</Link>
          </q-td>
        </template>
      </q-table>
    </div>
  </MainLayout>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import MainLayout from '../../Layouts/MainLayout.vue';
import { formatearNumero } from '../../Utils/format';
import { loading } from '../../Utils/loading';
import { obtenerFechaHoraLeible } from '../../Utils/date';
export default {
  name: "ListadoVentas",
  props: ["ventas", "status", "mensaje",],
  components: { MainLayout, Link },
  data() {
    return {
      filter: "",
      columns: [
        {
          name: 'folio',
          label: 'folio',
          align: 'left',
          field: row => row.serie_folio,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'total',
          label: 'Total Venta',
          align: 'right',
          field: row => row.total,
          format: val => formatearNumero(val, 'currency'),
          sortable: true
        },
        {
          name: 'cantidad',
          label: '# Productos',
          align: 'right',
          field: row => row.cantidad,
          format: val => formatearNumero(val),
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
          name: 'autorRegistro',
          label: 'Autor Registro',
          align: 'left',
          field: row => row.usuario_nombre,
          format: val => val ?? '--',
        },
      ],
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
      this.$inertia.get('/ventas/agregar');
    },
    activarLoading() {
      loading(true);
    },
  }
};
</script>
