<template>
  <q-dialog @before-show="listarProductos()" v-model="mostrar" persistent>
    <q-card style="width: 1000px; max-width: 90vw;">
      <q-card-section class="row">
        <div class="text-h6 ellipsis">{{ titulo }}</div>
        <q-space></q-space>
        <div>
          <q-btn rounded flat dense icon="close" @click="cerrar()"/>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section class="">
        <div class="q-mb-lg">
          <div class="row">
            <div class="col-10">
              <q-input
                v-model.trim="busqueda"
                type="text"
                id="busqueda"
                ref="busqueda"
                dense
                outlined
                autofocus
                @keypress.enter="listarProductos()"
                placeholder="Buscar por Clave / Código Barras / Nombre / Descripción"
                input-class="text-uppercase"
              >
              <template v-slot:append>
                <q-icon name="las la-times-circle" class="cursor-pointer"
                  @click="busqueda = '', listarProductos(), $refs.busqueda.focus()"
                />
                <q-icon name="search" class="cursor-pointer"
                  @click="listarProductos()"
                />
              </template>
              </q-input>
            </div>
            <div class="col-2 q-my-auto text-right text-weight-thin caption-registros">
              {{ totalRegistros }}
            </div>
          </div>
        </div>
        <div class="">
          <q-table
            :rows="productos"
            :columns="columns"
            :rows-per-page-options="[10]"
            class="tabla-agregar-venta striped-table"
            row-key="producto_id"
            no-data-label="Sin registros encontrados"
          >
          <template v-slot:body="props">
            <q-tr class="cursor-pointer" :props="props" @click="seleccionarProducto(props.row)">
              <q-td key="clave" :props="props">
                {{ props.row.clave || '--' }}
              </q-td>
              <q-td key="nombre" :props="props">
                {{ props.row.nombre || '--' }}
              </q-td>
              <q-td key="codigoBarras" :props="props">
                {{ props.row.codigo_barras || '--' }}
              </q-td>
              <q-td key="descripcion" :props="props">
                {{ props.row.descripcion || '--' }}
              </q-td>
              <q-td key="existencia" :props="props">
                {{ formatear(props.row.existencia, 'number') }}
              </q-td>
            </q-tr>
          </template>
            <template #body-cell-cantidad="{row, rowIndex}">
              <q-td class="text-right">
                <q-input
                  v-model.number="row.cantidad"
                  type="number"
                  step="any"
                  id="cantidad"
                  ref="cantidad"
                  input-class="w100 text-right"
                  dense
                  outlined
                  @update:modelValue="recalcularProducto(row, rowIndex, row.cantidad)"
                  min="0.01"
                  placeholder="Cantidad"
                />
              </q-td>
            </template>
            <template #body-cell-opciones="{row}">
              <q-td class="text-center">
                <q-btn @click="eliminarProducto(row)" dense flat color="negative" icon="delete" size="12px">
                  <q-tooltip>Eliminar</q-tooltip>
                </q-btn>
              </q-td>
            </template>
          </q-table>
          <!-- <q-markup-table flat class="striped-table">
            <thead>
              <th class="text-left">Clave</th>
              <th class="text-left">Nombre</th>
              <th class="text-left">Código Barras</th>
              <th class="text-left">Descripción</th>
              <th class="text-center">Existencia</th>
            </thead>
            <tbody>
              <tr class="cursor-pointer" v-for="(producto, i) in productos" :key="i"
                @click="seleccionarProducto(producto)"
              >
                <td class="text-left"> {{ producto.clave || '--' }}</td>
                <td class="text-left"> {{ producto.nombre || '--' }}</td>
                <td class="text-left"> {{ producto.codigo_barras || '--' }}</td>
                <td class="text-left"> {{ producto.descripcion || '--' }}</td>
                <td class="text-center"> {{ formatear(producto?.existencia, 'number') }}</td>
              </tr>
            </tbody>
          </q-markup-table> -->
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script>
import { formatearNumero } from '../../Utils/format';
import { loading } from "../../Utils/loading";
import { notify } from '../../Utils/notify';
export default {
  name: "ProductosSeleccionModal",
  props: {
    mostrar: {
      type: Boolean,
      default: false,
    },
    titulo: {
      type: String,
      default: 'Selección de Producto',
    },
  },
  data() {
    return {
      productos: [],
      busqueda: '',
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
          name: 'nombre',
          label: 'Nombre',
          align: 'left',
          field: row => row.nombre,
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
          name: 'descripcion',
          label: 'Descripción',
          align: 'left',
          field: row => row.descripcion,
          format: val => val ?? '--',
          sortable: false
        },
        {
          name: 'existencia',
          label: 'Existencia',
          align: 'center',
          field: row => row.existencia,
          format: val => formatearNumero(val, 'number'),
          sortable: true,
          headerClasses: 'w15p',
        },
      ],
    }
  },
  computed: {
    totalRegistros() {
      const length = this.productos.length;
      if (length == 1) {
        return `${length} Registro`;
      }
      return `${length} Registros`;
    }
  },
  methods: {
    async listarProductos() {
      try {
        loading(true);
        const params = {
          busqueda: this.busqueda,
          status: 'Activo',
          ordenar: 'nombre_asc',
        };
        const { data, status, statusText } = await axios.get('/productos/listar', {
          params: params
        });
        if (Number(status) != 200) {
          throw `Ocurrio un error al hacer la solicitud: ${statusText || '--'}`;
        }
        if (data.status != 200) {
          throw data.mensaje;
        }
        this.productos = data.productos;
        loading(false);
      } catch (error) {
        loading(false);
        notify(error, 'error');
      }
    },
    seleccionarProducto(producto) {
      this.$emit('seleccionar-producto', producto);
      this.productos = [];
      this.busqueda = '';
    },
    cerrar() {
      this.$emit('cerrar');
      this.productos = [];
      this.busqueda = '';
    },
    formatear(val, tipo) {
      return formatearNumero(val, tipo);
    }
  }
};
</script>

<style scoped>
.card-width {
  width: 1000px;
}

.caption-registros {
  color: gray;
}
</style>
