<template>
  <MainLayout>
    <div class="q-pa-md full-height">
      <q-card class="q-pa-md full-height overflow-auto">
        <div class="row col-12 justify-between q-mb-md">
          <div class="text-h4">Editar Usuario</div>
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
            <!-- SECCION DATOS GENERALES -->
            <div class="q-mb-md col-8">
              <div class="text-h6 text-grey-9 text-left fs-italic">Datos Generales</div>
              <q-separator />
            </div>
            <!-- NOMBRE -->
            <div class="q-mb-xs col-8">
              <div class="q-mb-xs">
                <label>Nombre *</label>
              </div>
              <div class="q-mb-xs">
                <q-input v-model.trim="form.nombre" id="nombre" ref="nombreInput" dense outlined placeholder="Nombre"
                  maxlength="150" :rules="[val => !!val || 'El nombre es obligatorio',]" />
              </div>
            </div>
            <!-- CORREO -->
            <div class="q-mb-xs col-8">
              <div class="q-mb-xs">
                <label>Correo *</label>
              </div>
              <div class="q-mb-xs">
                <q-input v-model.trim="form.correo" id="correo" dense outlined placeholder="Correo"
                  :rules="[val => validarCorreo(val)]" />
              </div>
            </div>
            <!-- Password -->
            <div class="q-mb-lg col-8">
              <div class="q-mb-xs row">
                <div class="q-pr-md">
                  <label>Editar Password</label>
                </div>
                <div>
                  <q-toggle
                    :label="editarPassword ?  'Si' : 'No'"
                    v-model="editarPassword"
                    dense
                  />
                </div>
              </div>
              <div class="q-mb-xs">
                <q-input :disable="!editarPassword" dense outlined v-model="form.password"
                  :type="isPwd ? 'password' : 'text'"
                  hint="Password mínimo 6 carácteres, recuerda guardar el password en un lugar seguro, antes de guardar cambios."
                  :rules="[val => !!val || 'El password es obligatorio', val => val.length > 5 || 'Mínimo 6 caracteres obligatorios',]">
                  <template v-slot:append>
                    <q-icon :name="isPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer q-mr-sm"
                      @click="isPwd = !isPwd" />
                    <div class="q-mr-sm">
                      <q-icon name="las la-clipboard" class="cursor-pointer" @click="copiarPassword()" />
                      <q-tooltip>
                        Copiar password
                      </q-tooltip>
                    </div>
                    <div>
                      <q-icon name="las la-random" class="cursor-pointer" @click="generarPasswordAleatorio()" />
                      <q-tooltip>
                        Password aleatorio
                      </q-tooltip>
                    </div>
                  </template>
                </q-input>
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
      @aceptar="mostrarModalExito = false, limpiar()" />
  </MainLayout>
</template>

<script>
import MainLayout from '../../Layouts/MainLayout.vue';
import { loading } from '../../Utils/loading';
import { notify } from '../../Utils/notify';
import { generarPassword } from '../../Utils/string';
import { obtenerFechaHoraActualOperacion } from '../../Utils/date';
import { copyToClipboard } from 'quasar';
export default {
  name: "EditarUsuario",
  props: ["usuarioEditar", "status", "mensaje"],
  components: { MainLayout },
  data() {
    return {
      form: {
        nombre: "",
        correo: "",
        password: "",
      },
      isPwd: true,
      editarPassword: false,
      mostrarModalExito: false,
      mensajeConfirmacion: "",
    }
  },
  created() {
    if (this.usuarioEditar && !this.status) {
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
  methods: {
    llenarDatosForm() {
      const { nombre, correo } = this.usuarioEditar;
      this.form = {
        nombre,
        correo,
      };
    },
    regresar() {
      loading(true);
      this.$inertia.get('/usuarios');
    },
    guardar() {
      loading(true, 'Editando ...');
      const form = {
        ...this.form,
        editarPassword: this.editarPassword ? 'si' : 'no',
        fechaActual: obtenerFechaHoraActualOperacion(),
      };
      this.$inertia.post("/usuarios/editar/" + this.usuarioEditar.usuario_id, form);
    },
    limpiar() {
      this.$nextTick(() => {
        this.$refs.nombreInput.focus();
        this.$refs.form.resetValidation();
        this.form.password = "";
        this.editarPassword = false;
      });
    },
    validarCorreo(val) {
      if (this.getEmailValidationRules(val)) {
        return "Email es inválido";
      }
      return true;
    },
    getEmailValidationRules(val) {
      const validacionCorreo = /.+@.+\..+/.test(val);
      if (!validacionCorreo) return true
      else return false;
    },
    copiarPassword() {
      copyToClipboard(this.form.password)
        .then(() => notify('Password copiado al portapapeles correctamente.', 'info'))
        .catch(() => notify('Ocurrio un error al intentar copiar el password al portapapeles.', 'error'));
    },
    generarPasswordAleatorio() {
      this.form.password = generarPassword();
    }
  }
};
</script>
