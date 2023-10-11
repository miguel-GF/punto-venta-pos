<template>
  <q-dialog v-model="mostrar" persistent>
    <q-card style="width: 800px; max-width: 80vw;">
      <q-card-section class="row">
        <div class="text-h6 ellipsis">{{ titulo }}</div>
        <q-space></q-space>
        <div>
          <q-btn rounded flat dense icon="close" @click="cerrar()"/>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section class="card-body-height scroll">
        <div class="q-mb-lg">
          <q-input
            v-model.trim="busqueda"
            type="text"
            id="busqueda"
            ref="busqueda"
            dense
            outlined
            autofocus
            @keypress.enter="obtenerProducto()"
            placeholder="Buscar por Clave / Código Barras"
            input-class="text-uppercase"
            :maxlength="$page.props.usuario.lectura_modo_monitor ? 4 : 20"
          >
          <template v-slot:append>
            <q-icon name="las la-times-circle" class="cursor-pointer"
              @click="busqueda = '', $refs.busqueda.focus()"
            />
            <q-icon name="search" class="cursor-pointer"
              @click="obtenerProducto()"
            />
          </template>
          </q-input>
        </div>
        <div class="q-mb-lg">
          <q-markup-table flat class="striped-table">
            <tbody>
              <tr>
                <td class="w10p text-right text-weight-bold">Clave</td>
                <td class="w40p text-left">
                  {{ productoObj?.clave || '--' }}
                </td>
                <td class="w10p text-right text-weight-bold">Código de Barras</td>
                <td class="w40p text-left">
                  {{ productoObj?.codigo_barras || '--' }}
                </td>
              </tr>
              <tr>
                <td class="w10p text-right text-weight-bold">Nombre</td>
                <td class="w90p text-left" colspan="3">
                  {{ productoObj?.nombre || '--' }}
                </td>
              </tr>
              <tr>
                <td class="w10p text-right text-weight-bold">Descripción</td>
                <td class="w90p text-left" colspan="3">
                  <div class="break-words-3-lines">
                    {{ productoObj?.descripcion || '--' }}
                  </div>
                </td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-actions align="center" class="q-pa-none row shadow-10">
        <div class="col-6 text-center bg-blue-1 q-px-md q-py-sm" style="outline: 1px solid #dbdbdb;">
          <div class="col-12 text-h6 text-weight-bold">Precio</div>
          <div class="col-12 text-h5">
            {{ formatear(productoObj?.precio, 'currency') }}
          </div>
        </div>
        <div class="col-6 row text-center bg-green-1 q-px-md q-py-sm" style="outline: 1px solid #dbdbdb;">
          <div class="col-12 text-h6 text-weight-bold">Existencia</div>
          <div class="col-12 text-h5">
            {{ formatear(productoObj?.existencia, 'number') }}
          </div>
        </div>
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { formatearNumero } from '../Utils/format';
import { loading } from "../Utils/loading";
import { notify } from '../Utils/notify';
export default {
  props: {
    mostrar: {
      type: Boolean,
      default: false,
    },
    titulo: {
      type: String,
      default: 'Búsqueda de Producto',
    },
    usuario: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      productoObj: {},
      busqueda: '',
    }
  },
  methods: {
    async obtenerProducto() {
      try {
        if (!this.busqueda) {
          return notify('Debe introducir clave o código de barras para realizar la búsqueda', 'error');
        }
        loading(true);
        const { data, status, statusText } = await axios.get('/productos/detalle/' + this.busqueda);
        if (Number(status) != 200) {
          throw `Ocurrio un error al hacer la solicitud: ${statusText || '--'}`;
        }
        if (data.status != 200) {
          throw data.mensaje;
        }
        this.productoObj = data.producto;
        loading(false);
      } catch (error) {
        loading(false);
        notify(error, 'error');
      }
    },
    cerrar() {
      this.$emit('cerrar');
      this.productoObj = {};
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

.card-body-height {
  min-height: 25vh;
  max-height: 50vh;
}
</style>
