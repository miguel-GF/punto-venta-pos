<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <!-- INICIO PANTALLA MOBILE -->
      <div class="lt-md full-height">
        <div class="row col-12 full-height justify-between q-mb-md">
          <q-card class="q-pa-md col-12 full-height column">
            <div class="row">
              <div :class="$q.screen.gt.xs ? 'text-h5' : 'text-h6'">Agregar Nueva Venta</div>
              <q-space />
              <div class="q-my-auto">
                <q-btn @click="regresar()" dense icon="chevron_left" color="secondary" class="q-mr-md">
                  <div class="q-px-sm gt-xs">Regresar</div>
                </q-btn>
                <q-btn @click="validarAgregar()" dense icon-right="las la-save" color="primary">
                  <div class="q-px-sm gt-xs">Guardar</div>
                </q-btn>
              </div>
            </div>
            <div class="q-mt-sm q-mb-md">
              <q-separator />
            </div>
            <!-- <div class="row hc-100-180 minh-200"> -->
            <div class="col">
              <!-- CLIENTE SMALL SCREEN -->
              <div class="q-mb-md">
                <q-select :options="clientes" v-model="form.cliente" ref="cliente" dense outlined
                  option-value="cliente_id" option-label="nombre_comercial">
                  <template #selected v-if="!form.cliente">
                    Selecciona una opción
                  </template>
                  <template #selected v-else>
                    CLIENTE: {{ form.cliente.nombre_comercial || '--' }}
                  </template>
                </q-select>
              </div>
              <!-- PRODUCTO SMALL SCREEN -->
              <div class="row">
                <div class="col">
                  <q-input v-model.trim="form.busqueda" type="text" id="busqueda" ref="busqueda" dense outlined
                    autofocus @keypress.enter="obtenerProducto()"
                    placeholder="PRODUCTO: Buscar por Clave / Código Barras" input-class="text-uppercase"
                    :maxlength="usuario.lectura_modo_monitor ? 4 : 20">
                    <template v-slot:append>
                      <q-icon name="las la-times-circle" class="cursor-pointer"
                        @click="form.busqueda = '', $refs.busqueda.focus()" />
                      <q-icon name="search" class="cursor-pointer" @click="obtenerProducto()" />
                    </template>
                  </q-input>
                </div>
                <div class="q-pl-md q-my-auto">
                  <q-btn outline @click="mostrarModalBusquedaProductos = true" dense icon-right="las la-filter"
                    color="primary">
                  </q-btn>
                </div>
              </div>
              <!-- TABLA PRODUCTOS SMALL SCREEEN -->
              <q-scroll-area class="q-mt-md" style="min-height: 150px; height: calc(100vh - 370px);">
                <q-list separator bordered v-if="form.productos.length > 0">
                  <q-item v-for="(producto, index) in form.productos" :key="producto.producto_id"
                    :class="index % 2 == 0 ? 'fondo-gris' : ''">
                    <q-item-section class="col">
                      <q-item-label caption>{{ `${producto.clave || '--'} - ${producto.codigo_barras || '--'}`
                        }}</q-item-label>
                      <q-item-label lines="2">{{ producto.nombre || '--' }}</q-item-label>
                    </q-item-section>
                    <q-item-section class="col-2">
                      <q-item-label caption>
                        Cantidad
                      </q-item-label>
                      <q-item-label class="">
                        <q-input v-model.number="producto.cantidad" type="number" step="any" id="cantidad"
                          ref="cantidad" input-class="text-right w150" class="w150" dense outlined
                          @update:modelValue="recalcularProducto(producto, index)" min="0.01" />
                      </q-item-label>
                    </q-item-section>
                    <q-item-section side top class="col-4">
                      <q-item-label caption>{{ formatearTotal(producto.precio, 'currency') }}</q-item-label>
                      <q-item-label>{{ formatearTotal(producto.total, 'currency') }}</q-item-label>
                      <q-btn @click="eliminarProducto(producto)" dense flat color="negative" icon="delete" size="11px">
                        <q-tooltip>Eliminar</q-tooltip>
                      </q-btn>
                    </q-item-section>
                  </q-item>
                </q-list>
                <div v-else class="text-h5 fs-italic absolute-center text-center">
                  Sin productos seleccionados para la venta
                </div>
              </q-scroll-area>
            </div>
            <div>
              <div class="text-center q-mb-xs">
                <label class="text-h6 text-grey-9 fs-italic">Total Venta</label>
              </div>
              <div class="text-center text-h5 bg-blue-1 q-px-md q-py-sm shadow-2 ellipsis"
                style="outline: 1px solid #dbdbdb;">
                {{ formatearTotal(calcularTotalVenta) }}
              </div>
            </div>
          </q-card>
        </div>
      </div>
      <!-- FIN PANTALLA MOBILE -->
      <!-- PANTALLA GRANDE -->
      <div class="gt-sm">
        <q-card class="q-pa-md overflow-auto">
          <div class="row col-12 justify-between q-mb-md">
            <div class="text-h4">Agregar Nueva Venta</div>
            <div class="q-my-auto">
              <q-btn @click="regresar()" dense icon="chevron_left" color="secondary" class="q-mr-md">
                <div class="q-px-sm">Regresar</div>
              </q-btn>
              <q-btn @click="validarAgregar()" dense icon-right="las la-save" color="primary">
                <div class="q-px-sm">Guardar</div>
              </q-btn>
              <!-- <q-btn @click="$refs.inputSubmit.click()" dense icon-right="las la-save" color="primary">
                <div class="q-px-sm">Guardar</div>
              </q-btn> -->
            </div>
          </div>
          <q-separator />
          <!-- <q-scroll-area class="hc-100-220 minh-200"> -->
          <div class="row hc-100-180 minh-200 q-pt-sm">
            <div class="w20p q-pr-md column full-height overflow-auto">
              <div class="col">
                <div class="q-mb-xs">
                  <label class="fs-1rem text-grey-9 fs-italic">Cliente *</label>
                </div>
                <q-select :options="clientes" v-model="form.cliente" ref="cliente" dense outlined
                  option-value="cliente_id" option-label="nombre_comercial">
                  <template #selected v-if="!form.cliente">
                    Selecciona una opción
                  </template>
                </q-select>
              </div>
              <div>
                <div class="text-center q-mb-xs">
                  <label class="text-h5 text-grey-9 fs-italic">Total Venta</label>
                </div>
                <div class="text-center text-h5 bg-blue-1 q-px-md q-py-sm shadow-2 mw-288 ellipsis"
                  style="outline: 1px solid #dbdbdb;">
                  {{ formatearTotal(calcularTotalVenta) }}
                </div>
              </div>
            </div>
            <q-separator vertical class="" />
            <div class="col q-pl-md">
              <div class="row col-12">
                <div class="row col-9">
                  <label class="fs-1rem text-grey-9 fs-italic q-my-auto">Producto</label>
                  <div class="col q-pl-md">
                    <q-input v-model.trim="form.busqueda" type="text" id="busqueda" ref="busqueda" dense outlined
                      autofocus @keypress.enter="obtenerProducto()" placeholder="Buscar por Clave / Código Barras"
                      input-class="text-uppercase" :maxlength="usuario.lectura_modo_monitor ? 4 : 20">
                      <template v-slot:append>
                        <q-icon name="las la-times-circle" class="cursor-pointer"
                          @click="form.busqueda = '', $refs.busqueda.focus()" />
                        <q-icon name="search" class="cursor-pointer" @click="obtenerProducto()" />
                      </template>
                    </q-input>
                  </div>
                </div>
                <div class="col-3 q-my-auto q-pl-md">
                  <q-btn outline @click="mostrarModalBusquedaProductos = true" dense icon-right="las la-filter"
                    color="primary">
                    <div class="q-px-sm">Búsqueda Avanzada</div>
                  </q-btn>
                </div>
              </div>
              <!-- PRODUCTOS -->
              <div class="q-mt-md">
                <q-table title="Productos a Vender" :rows="form.productos" :columns="columns"
                  :rows-per-page-options="[10]" class="tabla-agregar-venta striped-table" row-key="producto_id"
                  no-data-label="Sin productos seleccionados para la venta">
                  <template #body-cell-cantidad="{ row, rowIndex }">
                    <q-td class="text-right">
                      <q-input v-model.number="row.cantidad" type="number" step="any" id="cantidad" ref="cantidad"
                        input-class="w100 text-right" dense outlined
                        @update:modelValue="recalcularProducto(row, rowIndex)" min="0.01" placeholder="Cantidad" />
                    </q-td>
                  </template>
                  <template #body-cell-opciones="{ row }">
                    <q-td class="text-center">
                      <q-btn @click="eliminarProducto(row)" dense flat color="negative" icon="delete" size="12px">
                        <q-tooltip>Eliminar</q-tooltip>
                      </q-btn>
                    </q-td>
                  </template>
                </q-table>
              </div>
            </div>
          </div>
        </q-card>
      </div>
    </div>
    <!-- MODALES -->
    <!-- DIALOGO DE CONFIRMACION -->
    <the-dialog-confirm :mostrar="mostrarModalConfirmar" titulo="Agregar Nueva Venta" banner="confirmar"
      :mensaje="mensajeConfirmacion" @cerrar="mostrarModalConfirmar = false" @aceptar="agregarVenta()" />

    <!-- DIALOGO DE EXITO -->
    <the-dialog-response :mostrar="mostrarModalExito" :mensaje="mensajeExito" classesCard="card-width-450"
      @aceptar="mostrarModalExito = false, limpiar()">
      <template #body>
        <div class="text-center q-mt-sm">
          <iframe width="380" height="400" :src="'data:application/pdf;base64,' + ventaPdf" frameborder="0">
          </iframe>
        </div>
      </template>
    </the-dialog-response>

    <!-- DIALOGO DE BUSQUEDAS DE PRODUCTOS -->
    <productos-seleccion-modal :mostrar="mostrarModalBusquedaProductos" @cerrar="mostrarModalBusquedaProductos = false"
      @seleccionar-producto="seleccionarProducto" />
  </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import { loading } from '../../Utils/loading';
import { notify } from '../../Utils/notify';
import { formatearNumero } from '../../Utils/format';
import { obtenerFechaHoraActualOperacion } from '../../Utils/date';
export default {
  name: "AgregarVenta",
  props: ["usuario", "status", "mensaje", "clientes", "productos"],
  components: { MainLayout },
  data() {
    return {
      form: {
        cliente: "",
        busqueda: "",
        productos: [],
        totalVenta: 0,
        numeroProductos: 0,
      },
      ventaPdf: null,
      mostrarModalConfirmar: false,
      mostrarModalExito: false,
      mostrarModalBusquedaProductos: false,
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
          label: 'Código Barras',
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
          name: 'precio',
          label: 'Precio',
          align: 'right',
          field: row => row.precio,
          format: val => formatearNumero(val, 'currency'),
          sortable: true,
          headerClasses: 'w15p',
        },
        {
          name: 'cantidad',
          label: 'Cantidad',
          align: 'right',
          field: row => row.cantidad,
          format: val => formatearNumero(val),
          sortable: true,
          headerClasses: 'w15p',
        },
        {
          name: 'total',
          label: 'Total',
          align: 'right',
          field: row => row.total,
          format: val => formatearNumero(val, 'currency'),
          sortable: true,
          headerClasses: 'w15p',
        },
        {
          name: 'opciones',
          label: '',
          align: 'center',
        },
      ],
      mostrarModalExito: false,
      mensajeConfirmacion: "",
      mensajeExito: "",
    }
  },
  created() {
    loading(false);
    this.seleccionarClienteDefault();
    this.$nextTick(() => this.$refs.cliente.focus());
  },
  updated() {
    loading(false);
    if (this.status == 200) {
      this.mostrarModalExito = true;
    } else if (this.status == 300) {
      notify(this.mensaje, 'error');
    }
  },
  computed: {
    calcularTotalVenta() {
      const suma = this.form.productos.reduce(function (acumulador, producto) {
        return acumulador + producto.total;
      }, 0);
      this.form.totalVenta = suma;
      return suma;
    },
  },
  methods: {
    seleccionarClienteDefault() {
      const cliente = this.clientes.find(c => Number(c.cliente_id) == 1)
      if (cliente) this.form.cliente = cliente;
    },
    async obtenerProducto() {
      try {
        if (!this.form.busqueda) {
          return notify('Debe introducir clave o código de barras para realizar la búsqueda', 'error');
        }
        loading(true);
        const { data, status, statusText } = await axios.get('/productos/detalle/' + this.form.busqueda);
        if (Number(status) != 200) {
          throw `Ocurrio un error al hacer la solicitud: ${statusText || '--'}`;
        }
        if (data.status != 200) {
          throw data.mensaje;
        }
        this.guardarProducto(data.producto);
        loading(false);
      } catch (error) {
        loading(false);
        notify(error, 'error');
      }
    },
    guardarProducto(producto) {
      if (producto) {
        const index = this.form.productos.findIndex(p => p.producto_id == producto.producto_id);
        if (index >= 0) {
          this.form.productos[index].cantidad++;
          this.form.productos[index].total = this.calcularTotalProducto(this.form.productos[index]);
        } else {
          let productoAgregar = { ...producto };
          productoAgregar.cantidad = 1;
          productoAgregar.total = this.calcularTotalProducto(productoAgregar);
          this.form.productos.push(productoAgregar);
        }
        this.form.busqueda = "";
      }
    },
    calcularTotalProducto({ precio, cantidad }) {
      const total = precio * cantidad;
      return total;
    },
    eliminarProducto(producto) {
      const index = this.form.productos.findIndex(p => p.producto_id == producto.producto_id);
      if (index >= 0) {
        this.form.productos.splice(index, 1);
      }
    },
    recalcularProducto(producto, index) {
      this.$nextTick(() => {
        if (Number(producto.cantidad) > 0) {
          this.form.productos[index].total = this.calcularTotalProducto(this.form.productos[index]);
        }
      });
    },
    regresar() {
      loading(true);
      this.$inertia.get('/ventas');
    },
    limpiar() {
      this.form = {
        clave: "",
        busqueda: "",
        productos: [],
        totalVenta: 0,
        numeroProductos: 0,
      };
      this.seleccionarClienteDefault();
      this.$nextTick(() => {
        this.$refs.cliente.focus();
        this.$refs.form.resetValidation();
      });
    },
    validarAgregar() {
      try {
        this.calcularTotalCantidad();
        if (this.form.numeroProductos <= 0) {
          throw "Debe agregar almenos un producto a la venta";
        }
        if (this.form.productos.some(p => p.cantidad <= 0 || !p.cantidad)) {
          throw "Existe algún producto con cantidad incorrecta";
        }
        const mensajes = [];
        mensajes[0] = `Agregará una nueva venta con <span class='text-weight-bold'>${this.form.numeroProductos} productos</span>`;
        mensajes[1] = `y con un total de: <span class='text-weight-bold'>${this.formatearTotal(this.form.totalVenta)}</span>`;
        this.mensajeConfirmacion = mensajes.join('<br>');
        this.mostrarModalConfirmar = true;
      } catch (error) {
        notify(error, 'error');
      }
    },
    async agregarVenta() {
      try {
        loading(true, 'Agregando venta...');
        const form = {
          clienteId: this.form.cliente.cliente_id,
          productos: JSON.stringify(this.form.productos),
          totalVenta: JSON.stringify(this.form.totalVenta),
          numeroProductos: JSON.stringify(this.form.numeroProductos),
          fechaActual: obtenerFechaHoraActualOperacion(),
        };
        const { data, status, statusText } = await axios.post("/ventas/agregar", form);
        if (Number(status) != 200) {
          throw `Ocurrio un error al hacer la solicitud: ${statusText || '--'}`;
        }
        if (data.status >= 300) {
          throw data.mensaje;
        }
        loading(false);
        this.mensajeExito = data.mensaje;
        this.mostrarModalConfirmar = false;
        this.ventaPdf = data.archivo;
        this.mostrarModalExito = true;
      } catch (error) {
        loading(false);
        notify(error, 'error');
      }
    },
    formatearTotal(numero) {
      return formatearNumero(numero, 'currency');
    },
    calcularTotalCantidad() {
      const suma = this.form.productos.reduce(function (acumulador, producto) {
        return acumulador + producto.cantidad;
      }, 0);
      this.form.numeroProductos = suma;
      return suma;
    },
    seleccionarProducto(producto) {
      this.mostrarModalBusquedaProductos = false;
      this.guardarProducto(producto);
    }
  }
};
</script>
