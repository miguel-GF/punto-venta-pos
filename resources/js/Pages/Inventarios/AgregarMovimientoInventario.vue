<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-card class="q-px-md q-pt-sm full-height overflow-auto">
        <div class="row col-12 justify-between q-mb-md">
          <div class="text-h4">Agregar Movimiento Inventario</div>
          <div class="q-my-auto">
            <q-btn @click="regresar()" dense icon="chevron_left" color="secondary"
              class="q-mr-md"
            >
              <div class="q-px-sm">Regresar</div>
            </q-btn>
          </div>
        </div>
        <q-separator class="q-mb-md" />
        <div class="row q-mb-md">
          <div class="q-my-auto q-pr-md">
            Tipo de Movimiento
          </div>
          <div class="q-gutter-md q-pt-xs">
            <q-radio dense v-model="form.tipoMovimiento" val="Entrada" label="Entradas" />
            <q-radio dense v-model="form.tipoMovimiento" val="Salida" label="Salidas" />
          </div>
          <div class="text-left q-pl-lg">
            <q-btn outline @click="mostrarModalBusquedaProductos = true" dense icon-right="las la-filter" color="primary">
              <div class="q-px-sm">Búsqueda Avanzada</div>
            </q-btn>
          </div>
        </div>
        <div class="row col-12 text-right justify-end q-pr-md q-pb-md">
          <div class="col text-left">
            <q-input
              v-model.trim="form.busqueda"
              id="busqueda"
              ref="busquedaInput"
              dense
              outlined
              placeholder="Buscar por Clave / Código de Barras"
              @keyup.enter.native="guardar()"
              :maxlength="usuario.lectura_modo_monitor ? 4 : 20"
            />
          </div>
          <div class="col-2 q-my-auto q-pr-md">
            Cantidad
          </div>
          <div class="col-2">
            <q-input
              v-model.number="form.cantidad"
              type="number"
              step="any"
              ref="cantidadInput"
              dense
              outlined
              placeholder="Cantidad a ingresar"
              @keyup.enter.native="guardar()"
            />
          </div>
          <div class="col-1">
            <q-btn @click="guardar()" dense color="primary">
              <div class="q-px-sm">Agregar</div>
            </q-btn>
          </div>
        </div>
        <q-separator class="q-mb-md" />
        <q-table 
          title="Histórico de Movimientos"
          :rows="movimientos"
          :columns="columns"
          :rows-per-page-options="[10]"
          class="tabla-agregar-movimiento striped-table"
          row-key="movimiento_inventario_id"
          no-data-label="Sin registros encontrados"
        >
          <template v-slot:top-right>
            Movimientos de la fecha actual: {{ fechaHoy }}
          </template>
          <template #body-cell-descripcion="{row}">
            <q-td class="text-left">
              <div class="w350 break-words-3-lines">
                {{ row.descripcion || '--' }}
              </div>
            </q-td>
          </template>
        </q-table>
      </q-card>
    </div>
    <!-- MODALES -->

    <!-- DIALOGO DE EXITO -->
    <the-dialog-response
      :mostrar="mostrarModalExito"
      :mensaje="mensaje"
      @aceptar="mostrarModalExito = false, limpiar()"
    />

    <!-- DIALOGO DE BUSQUEDAS DE PRODUCTOS -->
    <productos-seleccion-modal
      :mostrar="mostrarModalBusquedaProductos"
      @cerrar="mostrarModalBusquedaProductos = false"
      @seleccionar-producto="seleccionarProducto"
    />
  </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import { formatearNumero } from '../../Utils/format';
import { loading } from '../../Utils/loading';
import { notify } from '../../Utils/notify';
import { obtenerFechaHoraActualOperacion, obtenerFechaActualCompletaMostrar } from '../../Utils/date';
export default {
  name: "AgregarMovimientoInventario",
  props: ["movimientos", "status", "mensaje", "usuario"],
  components: { MainLayout },
  data() {
    return {
      fechaHoy: obtenerFechaActualCompletaMostrar(),
      form: {
        busqueda: "",
        tipoMovimiento: "Entrada",
        cantidad: 1
      },
      mostrarModalExito: false,
      mostrarModalBusquedaProductos: false,
      mensajeConfirmacion: "",
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
          name: 'marca',
          label: 'Marca',
          align: 'left',
          field: row => row.marca,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'familia',
          label: 'Familia',
          align: 'left',
          field: row => row.familia,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'tipo',
          label: 'Tipo',
          align: 'left',
          field: row => row.tipo,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'cantidad',
          label: 'Cantidad',
          align: 'center',
          field: row => row.cantidad,
          format: val => formatearNumero(val),
          sortable: true
        },
        {
          name: 'folio',
          label: 'folio',
          align: 'center',
          field: row => row.modulo_folio,
          format: val => val ?? '--',
          sortable: true
        },
        {
          name: 'fechaRegistro',
          label: 'Fecha Registro',
          align: 'center',
          field: row => row.registro_fecha,
          format: val => val ?? '--',
          sortable: true
        },
      ],
    }
  },
  created() {
    loading(false);
    this.$nextTick(() => this.$refs.busquedaInput.focus());
  },
  updated() {
    loading(false);
    if (this.status == 200) {
      this.mostrarModalExito = true;
    } else if (this.status >= 300) {
      notify(this.mensaje, 'error');
    }
  },
  methods: {
    regresar() {
      loading(true);
      this.$inertia.get('/');
    },
    guardar() {
      if (!this.form.busqueda) {
        return notify('Debe introducir clave o código de barras, favor de verificar', 'error');
      }
      if (this.form.cantidad <= 0) {
        return notify('La cantidad no es válida, favor de verificar', 'error');
      }
      loading(true, 'Agregando ...');
      const form = {
        ...this.form,
        fechaActual: obtenerFechaHoraActualOperacion(),
      };
      this.$inertia.post("/inventarios/agregar", form);
    },
    limpiar() {
      this.form = {
        busqueda: "",
        tipoMovimiento: "Entrada",
        cantidad: 1
      };
      this.$nextTick(() => {
        this.$refs.busquedaInput.focus();
      });
    },
    seleccionarProducto(producto) {
      this.mostrarModalBusquedaProductos = false;
      if (this.usuario.lectura_modo_monitor) {
        this.form.busqueda = producto?.codigo_barras.slice(0, 4);
      } else {
        this.form.busqueda = producto?.codigo_barras;
      }
      this.$nextTick(() => this.$refs.cantidadInput.focus());
    }
  }
};
</script>
