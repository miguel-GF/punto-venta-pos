<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-card class="q-pa-md full-height overflow-auto">
        <div class="row col-12 justify-between q-mb-md">
          <div class="text-h4">Editar Producto</div>
          <div class="q-my-auto">
            <q-btn @click="regresar()" dense icon="chevron_left" color="secondary"
              class="q-mr-md"
            >
              <div class="q-px-sm">Regresar</div>
            </q-btn>
            <q-btn @click="$refs.inputSubmit.click()" dense icon-right="las la-save" color="primary">
              <div class="q-px-sm">Guardar</div>
            </q-btn>
          </div>
        </div>
        <q-separator class="q-mb-md" />
        <!-- <q-scroll-area class="hc-100-220 minh-200"> -->
          <q-form ref="form" @submit.prevent="guardar()">
            <input ref="inputSubmit" hidden type="submit">
            <div class="row col-12 justify-center full-height">
              <!-- CLAVE -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Clave *</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.clave"
                    id="clave"
                    ref="claveInput"
                    dense
                    outlined
                    placeholder="Clave del producto"
                    maxlength="15"
                    :rules="[val => !!val || 'La clave es obligatoria',]"
                    input-class="text-uppercase"
                  />
                </div>
              </div>
              <!-- CODIGO DE BARRAS -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Código de Barras *</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.codigoBarras"
                    id="codigoBarras"
                    dense
                    outlined
                    placeholder="Código de barras del producto"
                    maxlength="30"
                    :rules="[val => !!val || 'El código de barras es obligatorio',]"
                  />
                </div>
              </div>
              <!-- NOMBRE -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Nombre *</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.nombre"
                    id="nombre"
                    dense
                    outlined
                    placeholder="Nombre del producto"
                    maxlength="120"
                    :rules="[val => !!val || 'El nombre es obligatorio',]"
                  />
                </div>
              </div>
              <!-- DESCRIPCION -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Descripción *</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.descripcion"
                    type="textarea"
                    id="descripcion"
                    dense
                    outlined
                    autogrow
                    placeholder="Descripción del producto"
                    :rules="[val => !!val || 'La descripción es obligatoria',]"
                  />
                </div>
              </div>
              <!-- PRECIO Y EXISTENCIA-->
              <div class="q-mb-xs col-8">
                <div class="row col-12">
                  <!-- PRECIO -->
                  <div class="col-6 q-pr-sm">
                    <div class="q-mb-xs">
                      <label>Precio *</label>
                    </div>
                    <div class="q-mb-xs">
                      <q-input
                        v-model.number="form.precio"
                        type="number"
                        step="any"
                        id="precio"
                        dense
                        outlined
                        placeholder="Precio del producto"
                        :rules="[val => !!val || 'El precio es obligatorio',]"
                      />
                    </div>
                  </div>
                  <!-- EXISTENCIA -->
                  <div class="col-6 q-pl-sm">
                    <div class="q-mb-xs">
                      <label>Existencia *</label>
                    </div>
                    <div class="q-mb-xs">
                      <q-input
                        v-model.number="form.existencia"
                        type="number"
                        step="any"
                        id="existencia"
                        dense
                        outlined
                        placeholder="Stock del producto"
                        :rules="[val => !!val || 'La existencia es obligatoria',]"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <!-- BTN GUARDAR -->
              <div class="text-right col-8">
                <q-btn type="submit" icon-right="las la-save" color="primary" class="full-width">
                  <div class="q-px-sm">Guardar</div>
                </q-btn>
              </div>
            </div>
          </q-form>
        <!-- </q-scroll-area> -->
      </q-card>
    </div>
    <!-- MODALES -->

    <!-- DIALOGO DE EXITO -->
    <the-dialog-response
      :mostrar="mostrarModalExito"
      :mensaje="mensaje"
      @aceptar="mostrarModalExito = false, limpiar()"
    />
  </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import { loading } from '../../Utils/loading';
import { notify } from '../../Utils/notify';
import { obtenerFechaHoraActualOperacion } from '../../Utils/date';
export default {
  name: "EditarProducto",
  props: ["producto", "status", "mensaje"],
  components: { MainLayout },
  data() {
    return {
      form: {
        clave: "",
        codigoBarras: "",
        nombre: "",
        descripcion: "",
        precio: "",
        existencia: "",
      },
      mostrarModalExito: false,
      mensajeConfirmacion: "",
    }
  },
  created() {
    if (this.producto && !this.status) {
      this.llenarDatosForm();
      this.$nextTick(() => this.$refs.claveInput.focus());
    }
    loading(false);
  },
  updated() {
    loading(false);
    if (this.status == 200) {
      this.mostrarModalExito = true;
      this.llenarDatosForm();
    } else if (this.status == 300) {
      notify(this.mensaje, 'error');
    }
  },
  methods: {
    llenarDatosForm() {
      const { clave, codigo_barras, nombre, descripcion, precio, existencia } = this.producto;
      this.form = {
        clave,
        codigoBarras: codigo_barras,
        nombre,
        descripcion,
        precio,
        existencia,
      }
    },
    regresar() {
      loading(true);
      this.$inertia.get('/productos');
    },
    guardar() {
      loading(true, 'Editando ...');
      const form = {
        ...this.form,
        fechaActual: obtenerFechaHoraActualOperacion(),
      };
      this.$inertia.post("/productos/editar/" + this.producto.producto_id, form);
    },
    limpiar() {
      this.$nextTick(() => {
        this.$refs.claveInput.focus();
        this.$refs.form.resetValidation();
      });
    }
  }
};
</script>
