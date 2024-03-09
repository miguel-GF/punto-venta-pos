<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-card class="q-pa-md full-height overflow-auto">
        <div class="row col-12 justify-between q-mb-md">
          <div class="text-h4">Editar Configuraciones</div>
          <div class="q-my-auto">
            <q-btn @click="regresar()" dense icon="chevron_left" color="secondary" class="q-mr-md">
              <div class="q-px-sm">Regresar</div>
            </q-btn>
            <q-btn @click="$refs.inputSubmit.click()" dense icon-right="las la-save" color="primary">
              <div class="q-px-sm">Guardar</div>
            </q-btn>
          </div>
        </div>
        <q-separator class="q-mb-md" />
        <!-- <q-scroll-area class="hc-100-220 minh-200" visible> -->
        <q-form ref="form" @submit.prevent="guardar()">
          <input ref="inputSubmit" hidden type="submit">
          <div class="row col-12 justify-center full-height">
            <!-- SECCION LECTURA -->
            <div class="q-mb-md col-8">
              <div class="text-h6 text-grey-9 text-left fs-italic">Lectura</div>
              <q-separator />
            </div>
            <!-- LECTURA COMPLETA -->
            <div class="q-mb-lg col-8">
              <div class="q-mb-xs row">
                <div class="q-pr-md">
                  <label>Lectura Modo Monitor</label>
                </div>
                <div>
                  <q-toggle
                    :label="form.lecturaCompleta ?  'Si' : 'No'"
                    v-model="form.lecturaCompleta"
                    dense
                    checked-icon="check"
                    unchecked-icon="clear"
                    ref="lectorToogle"
                  />
                </div>
              </div>
              <div>
                <q-banner dense :class="obtenerClassLector" class="rounded-borders q-pa-sm q-mb-md">
                  <template v-slot:avatar>
                    <div class="q-my-auto">
                      <q-icon name="info" size="xs" />
                    </div>
                  </template>
                  {{ mensajeBanner || '--' }}
                </q-banner>
              </div>
            </div>
            <div class="q-mb-md col-8">
              <div class="text-h6 text-grey-9 text-left fs-italic">Impresora</div>
              <q-separator />
            </div>
            <div class="q-mb-lg col-8">
              <div class="q-mb-xs row">
                <div class="q-pr-md">
                  <label>Impresora Predeterminada</label>
                </div>
                <div>
                  <q-toggle
                    :label="form.impresoraPredeterminada ?  'Si' : 'No'"
                    v-model="form.impresoraPredeterminada"
                    dense
                    checked-icon="check"
                    unchecked-icon="clear"
                    ref="lectorToogle"
                    disable
                  />
                </div>
              </div>
              <div>
                <q-banner dense :class="obtenerClassImpresora" class="rounded-borders q-pa-sm q-mb-md">
                  <template v-slot:avatar>
                    <div class="q-my-auto">
                      <q-icon name="info" size="xs" />
                    </div>
                  </template>
                  {{ mensajeImpresora || '--' }}
                </q-banner>
              </div>
              <div class="q-mb-lg">
                <q-select
                  :options="impresorasOpciones"
                  v-model="form.impresoraNombre"
                  dense
                  outlined
                  emit-value
                  disable
                  >
                  <!-- :disable="form.impresoraPredeterminada"c
                  :rules="[val => form.impresoraPredeterminada ? [] : validarSeleccionImpresora(val)]" -->
                  <template #selected v-if="!form.impresoraNombre">
                    Selecciona una opción
                  </template>
                </q-select>
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
    <the-dialog-response :mostrar="mostrarModalExito" :mensaje="mensaje"
      @aceptar="mostrarModalExito = false" />
  </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import { loading } from '../../Utils/loading';
import { notify } from '../../Utils/notify';
import { obtenerFechaHoraActualOperacion } from '../../Utils/date';
import { isEmpty, sortBy } from '../../Utils/lodash';
export default {
  name: "EditarUsuario",
  props: ["usuarioConfiguracion", "impresoras", "status", "mensaje"],
  components: { MainLayout },
  data() {
    return {
      form: {
        lecturaCompleta: true,
        impresoraPredeterminada: false,
        impresoraNombre: "",
      },
      isPwd: true,
      editarPassword: false,
      mostrarModalExito: false,
      mensajeConfirmacion: "",
      mensajeBanner: "",
      mensajeImpresora: "",
      impresorasOpciones: [],
    }
  },
  created() {
    if (this.usuarioConfiguracion && !this.status) {
      this.llenarDatosForm();
      this.$nextTick(() => this.$refs.lectorToogle.$el.focus());
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
  computed: {
    obtenerClassLector() {
      if (!this.form.lecturaCompleta) {
        this.mensajeBanner = 'La lectura del código de barras será completo.';
        return 'bg-blue-5 text-white';
      }
      else {
        this.mensajeBanner = 'La lectura del código de barras solo leerá los primeros 4 caracteres.';
        return 'bg-green-5';
      }
    },
    obtenerClassImpresora() {
      if (!this.form.impresoraPredeterminada) {
        this.mensajeImpresora = 'Debe seleccionar una impresora de la lista de abajo, la cual utilizará para la impresión de tickets.';
        return 'bg-blue-5 text-white';
      }
      else {
        this.mensajeImpresora = 'La impresora utilizada será la predeterminada del sistema para la impresión de tickets.';
        return 'bg-green-5';
      }
    },
  },
  methods: {
    llenarDatosForm() {
      this.impresorasOpciones = [];
      if (!isEmpty(this.impresoras)) {
        this.impresoras.forEach(impresora => this.impresorasOpciones.push({
          label: impresora,
          value: impresora
        }));
        this.impresorasOpciones = sortBy(this.impresorasOpciones, ['label']);
      }
      const { lectura_modo_monitor, impresora_predeterminada, impresora_nombre } = this.usuarioConfiguracion;
      this.form = {
        lecturaCompleta: lectura_modo_monitor ? true : false,
        // impresoraPredeterminada: impresora_predeterminada ? true: false,
        // impresoraNombre: impresora_nombre,
        impresoraPredeterminada: false,
        impresoraNombre: null,
      };
    },
    regresar() {
      loading(true);
      this.$inertia.get('/');
    },
    guardar() {
      loading(true, 'Editando ...');
      const form = {
        lecturaCompleta: this.form.lecturaCompleta ? 'si' : 'no',
        fechaActual: obtenerFechaHoraActualOperacion(),
        impresoraPredeterminada: this.form.impresoraPredeterminada ? 'si' : 'no',
        impresoraNombre: !this.form.impresoraPredeterminada ? this.form.impresoraNombre : null,
      };
      this.$inertia.post("/configuraciones/usuario", form);
    },
    validarSeleccionImpresora(val) {
      if (isEmpty(val)) {
        return "Debe seleccionar una impresora";
      }
      return true;
    },
  }
};
</script>
