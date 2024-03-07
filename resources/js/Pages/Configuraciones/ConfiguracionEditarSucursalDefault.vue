<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-card class="q-pa-md full-height overflow-auto">
        <div class="row col-12 justify-between q-mb-md">
          <div class="text-h4">Editar Sucursal</div>
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
            <!-- NOMBRE -->
            <div class="q-mb-xs col-8">
              <div class="q-mb-xs">
                <label>Nombre *</label>
              </div>
              <div class="q-mb-xs">
                <q-input
                  v-model.trim="form.nombre"
                  id="nombre"
                  ref="nombreInput"
                  dense
                  outlined
                  placeholder="Nombre de la Sucursal/Tienda"
                  maxlength="100"
                  :rules="[val => !!val || 'El nombre es obligatorio',]"
                  input-class="text-uppercase"
                />
              </div>
            </div>
            <!-- DIRECCION -->
            <div class="q-mb-xs col-8">
              <div class="q-mb-xs">
                <label>Dirección *</label>
              </div>
              <div class="q-mb-xs">
                <q-input
                  v-model.trim="form.direccion"
                  id="direccion"
                  ref="direccionInput"
                  dense
                  outlined
                  placeholder="Dirección"
                  maxlength="250"
                  :rules="[val => !!val || 'La dirección es obligatoria',]"
                  input-class="text-uppercase"
                />
              </div>
            </div>
            <!-- TELEFONO -->
            <div class="q-mb-xs col-8">
              <div class="q-mb-xs">
                <label>Teléfono</label>
              </div>
              <div class="q-mb-xs">
                <q-input
                  v-model="form.telefono"
                  id="telefono"
                  ref="telefonoInput"
                  mask="(###) ###-####"
                  dense
                  outlined
                  placeholder="(###) ###-####"
                  maxlength="15"
                  input-class="text-uppercase"
                />
              </div>
            </div>
            <!-- RFC -->
            <div class="q-mb-xs col-8">
              <div class="q-mb-xs">
                <label>RFC</label>
              </div>
              <div class="q-mb-xs">
                <q-input
                  v-model.trim="form.rfc"
                  id="rfc"
                  ref="rfcInput"
                  dense
                  outlined
                  placeholder="RFC"
                  maxlength="15"
                  input-class="text-uppercase"
                />
              </div>
            </div>
            <!-- TICKET LEYENDA FOOTER -->
            <div class="q-mb-xs col-8">
              <div class="q-mb-xs">
                <label>Pie del Ticket</label>
              </div>
              <div class="q-mb-xs">
                <q-input
                  v-model.trim="form.ticketLeyendaPie"
                  id="ticketLeyendaPie"
                  ref="ticketLeyendaPieInput"
                  dense
                  outlined
                  placeholder="Leyenda que aparecerá en la parte de abajo del ticket"
                  maxlength="250"
                  input-class="text-uppercase"
                />
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
export default {
  name: "EditarUsuario",
  props: ["usuario", "sucursalDefault", "status", "mensaje"],
  components: { MainLayout },
  data() {
    return {
      form: {
        nombre: "",
        direccion: "",
        telefono: "",
        rfc: "",
        ticketLeyendaPie: ""
      },
      isPwd: true,
      editarPassword: false,
      mostrarModalExito: false,
      mensajeConfirmacion: "",
      mensajeBanner: "",
    }
  },
  created() {
    if (this.sucursalDefault && !this.status) {
      this.llenarDatosForm();
      this.$nextTick(() => this.$refs.nombreInput.focus());
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
    obtenerClass() {
      if (!this.form.lecturaCompleta) {
        this.mensajeBanner = 'La lectura del código de barras será completo.';
        return 'bg-blue-5 text-white';
      }
      else {
        this.mensajeBanner = 'La lectura del código de barras solo leerá los primeros 4 caracteres.';
        return 'bg-green-5';
      }
    },
  },
  methods: {
    llenarDatosForm() {
      const { nombre, direccion, telefono, rfc, ticket_leyenda_pie } = this.sucursalDefault;
      this.form = {
        nombre,
        direccion,
        telefono,
        rfc,
        ticketLeyendaPie: ticket_leyenda_pie
      };
    },
    regresar() {
      loading(true);
      this.$inertia.get('/');
    },
    guardar() {
      loading(true, 'Editando ...');
      const form = {
        ...this.form,
        sucursalId: this.sucursalDefault.sucursal_id,
        fechaActual: obtenerFechaHoraActualOperacion(),
      };
      this.$inertia.post("/configuraciones/sucursalDefault", form);
    },
  }
};
</script>
