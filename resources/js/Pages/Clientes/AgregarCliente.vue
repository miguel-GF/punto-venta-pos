<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-card class="q-pa-md full-height overflow-auto">
        <div class="row col-12 justify-between q-mb-md">
          <div class="text-h4">Agregar Nuevo Cliente</div>
          <div class="q-my-auto">
            <q-btn @click="regresar()" dense icon-right="chevron_left" color="secondary"
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
        <!-- <q-scroll-area class="hc-100-220 minh-200" visible> -->
          <q-form ref="form" @submit.prevent="guardar()">
            <input ref="inputSubmit" hidden type="submit">
            <div class="row col-12 justify-center full-height">
              <!-- SECCION DATOS GENERALES -->
              <div class="q-mb-md col-8">
                <div class="text-h6 text-grey-9 text-left fs-italic">Datos Generales</div>
                <q-separator />
              </div>
              <!-- NOMBRE COMERCIAL -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Nombre Comercial *</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.nombreComercial"
                    id="nombreComercial"
                    ref="nombreInput"
                    dense
                    outlined
                    placeholder="Nombre comercial"
                    maxlength="200"
                    :rules="[val => !!val || 'El nombre comercial es obligatorio',]"
                  />
                </div>
              </div>
              <!-- TÉLEFONO -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Télefono</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.number="form.telefono"
                    id="telefono"
                    dense
                    outlined
                    placeholder="(###) ##-##-###"
                    mask="(###) ##-##-###"
                    maxlength="15"
                    :rules="[val => validarTelefono(val)]"
                  />
                </div>
              </div>
              <!-- ESLOGAN -->
              <div class="q-mb-lg col-8">
                <div class="q-mb-xs">
                  <label>Eslogan</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.eslogan"
                    type="textarea"
                    id="eslogan"
                    dense
                    outlined
                    autogrow
                    placeholder="Eslogan"
                  />
                </div>
              </div>
              <!-- CORREO -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Correo</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.correo"
                    id="correo"
                    dense
                    outlined
                    placeholder="Correo personal"
                    :rules="[val => validarCorreo(val)]"
                  />
                </div>
              </div>
              <!-- SECCION DATOS FISCALES -->
              <div class="q-mb-md col-8">
                <div class="text-h6 text-grey-9 text-left fs-italic">Datos Fiscales</div>
                <q-separator />
              </div>
              <!-- TIPO PERSONA -->
              <div class="q-mb-lg col-8">
                <div class="q-mb-xs">
                  <label>Tipo de Persona</label>
                </div>
                <div class="q-mb-xs">
                  <q-select
                    :options="tipoPersonas"
                    v-model="form.tipoPersona"
                    id="tipoPersona"
                    dense
                    outlined
                    emit-value
                  >
                    <template #selected v-if="!form.tipoPersona">
                      Selecciona una opción
                    </template>
                  </q-select>
                </div>
              </div>
              <!-- RAZON SOCIAL -->
              <div class="q-mb-lg col-8">
                <div class="q-mb-xs">
                  <label>Razón Social</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.razonSocial"
                    id="razonSocial"
                    dense
                    outlined
                    maxlength="230"
                    placeholder="Razón Social"
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
                    dense
                    outlined
                    maxlength="15"
                    placeholder="RFC"
                  />
                </div>
              </div>
              <!-- CODIGO POSTAL -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Código Postal</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.number="form.codigoPostal"
                    id="codigoPostal"
                    dense
                    outlined
                    maxlength="5"
                    mask="#####"
                    placeholder="Código Postal"
                    :rules="[val => validarCodigoPostal(val)]"
                  />
                </div>
              </div>
              <!-- DOMICILIO -->
              <div class="q-mb-lg col-8">
                <div class="q-mb-xs">
                  <label>Domicilio</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.domicilioFiscal"
                    id="domicilioFiscal"
                    type="textarea"
                    dense
                    outlined
                    autogrow
                    placeholder="Domicilio"
                  />
                </div>
              </div>
              <!-- CORREO DATOS FISCALES -->
              <div class="q-mb-xs col-8">
                <div class="q-mb-xs">
                  <label>Correo Fiscal</label>
                </div>
                <div class="q-mb-xs">
                  <q-input
                    v-model.trim="form.correoFiscal"
                    id="correoFiscal"
                    dense
                    outlined
                    placeholder="Correo para datos fiscales"
                    :rules="[val => validarCorreo(val)]"
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
  name: "AgregarCliente",
  props: ["status", "mensaje"],
  components: { MainLayout },
  data() {
    return {
      form: {
        nombreComercial: "",
        telefono: "",
        eslogan: "",
        correo: "",
        tipoPersona: "",
        razonSocial: "",
        rfc: "",
        codigoPostal: "",
        domicilioFiscal: "",
        regimenFiscal: "",
        cfid: "",
        correoFiscal: "",
      },
      mostrarModalExito: false,
      mensajeConfirmacion: "",
      tipoPersonas: [
        { label: "Física", value: "Fisica" },
        { label: "Moral", value: "Moral" },
      ]
    }
  },
  created() {
    loading(false);
    this.$nextTick(() => this.$refs.nombreInput.focus());
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
      this.$inertia.get('/clientes');
    },
    guardar() {
      loading(true, 'Agregando ...');
      const form = {
        ...this.form,
        fechaActual: obtenerFechaHoraActualOperacion(),
      };
      this.$inertia.post("/clientes/agregar", form);
    },
    limpiar() {
      this.form = {
        nombreComercial: "",
        telefono: "",
        eslogan: "",
        correo: "",
        tipoPersona: "",
        razonSocial: "",
        rfc: "",
        codigoPostal: "",
        domicilioFiscal: "",
        regimenFiscal: "",
        cfid: "",
        correoFiscal: "",
      };
      this.$nextTick(() => {
        this.$refs.nombreInput.focus();
        this.$refs.form.resetValidation();
      });
    },
    validarCodigoPostal(val) {
      const codigoPostal = String(val);
      if (codigoPostal != "" && codigoPostal.length < 5) {
        return "Debe ser mayor de 5 dígitos";
      }
      return true;
    },
    validarTelefono(val) {
      const telefono = String(val);
      if (telefono != "" && telefono.length < 15) {
        return "Debe ser 10 dígitos";
      }
      return true;
    },
    validarCorreo(val) {
      if (val != "") {
        if (this.getEmailValidationRules(val)) {
          return "Email es inválido";
        }
      }
      return true;
    },
    getEmailValidationRules(val) {
      if (val == undefined || val == "") {
        return false;
      }
      const validacionCorreo = /.+@.+\..+/.test(val);
      if (!validacionCorreo) return true
      else return false;
    },
  }
};
</script>
