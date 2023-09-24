<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-card class="q-pa-md full-height">
        <div class="row col-12 justify-between q-mb-md">
          <div class="text-h6">Detalle de Venta - {{ venta.serie_folio || '--' }}</div>
          <q-btn @click="regresar()" dense icon-right="chevron_left" color="secondary">
            <div class="q-px-sm">Regresar</div>
          </q-btn>
        </div>
        <div class="row col-12 justify-between q-mb-md">
          <q-markup-table flat class="striped-table col-12" dense>
            <tbody>
              <tr>
                <td>Folio</td>
                <td>{{ venta.serie_folio || '--' }}</td>
                <td>Fecha Registro</td>
                <td>
                  {{ obtenerFecha(venta.registro_fecha) }}
                </td>
              </tr>
              <tr>
                <td># Productos</td>
                <td>{{ formatear(venta.cantidad, 'number') }}</td>
                <td>Autor</td>
                <td>{{ venta.usuario_nombre || '--' }}</td>
              </tr>
              <tr>
                <td>Total Venta</td>
                <td>{{ formatear(venta.total, 'currency') }}</td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
        <div class="q-mb-md">
          <div class="text-h6">
            Productos de la Venta
          </div>
        </div>
        <q-table :rows="ventaDetalle" :columns="columns" :rows-per-page-options="[10]"
          class="tabla-detalle-venta striped-table " row-key="venta_detalle_id">
        </q-table>
      </q-card>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import { formatearNumero } from '../../Utils/format';
import { loading } from '../../Utils/loading';
import { obtenerFechaHoraLeible } from '../../Utils/date';
export default {
  name: "ListadoVentas",
  props: ["venta", "status", "mensaje", "ventaDetalle"],
  components: { MainLayout },
  data() {
    return {
      filter: "",
      columns: [
        {
          name: 'clave',
          label: 'clave',
          align: 'left',
          field: row => row.clave,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'producto',
          label: 'producto',
          align: 'left',
          field: row => row.producto,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'cantidad',
          label: 'Cantidad',
          align: 'right',
          field: row => row.cantidad,
          format: val => formatearNumero(val),
          sortable: true
        },
        {
          name: 'cantidad',
          label: 'Precio U.',
          align: 'right',
          field: row => row.cantidad,
          format: val => formatearNumero(val, 'currency'),
          sortable: true
        },
        {
          name: 'total',
          label: 'Total',
          align: 'right',
          field: row => row.total,
          format: val => formatearNumero(val, 'currency'),
          sortable: true
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
    regresar() {
      loading(true);
      this.$inertia.get('/ventas');
    },
    formatear(val, tipo) {
      return formatearNumero(val, tipo);
    },
    obtenerFecha(val) {
      return obtenerFechaHoraLeible(val);
    }
  }
};
</script>
