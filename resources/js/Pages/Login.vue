<template>
  <div class="window-height window-width background-image">
    <div class="centered-div full-width">
      <div class="row full-width">
        <div class="col-4"></div>
        <div class="col-12 col-sm-12 col-md-4 col-4">
          <q-card class="full-width">
            <q-tabs
              v-model="tab"
              dense
              class="text-grey"
              active-color="primary"
              indicator-color="primary"
              align="justify"
              narrow-indicator
            >
              <q-tab name="sistema" label="Sistema Ventas" @click="guardarTipo('sistema', limpiarDatos())" />
            </q-tabs>
  
            <q-separator />
  
            <q-tab-panels v-model="tab" animated>
              <q-tab-panel name="sistema">
                <div class="text-h5 text-center">Acceso al Sistema</div>
                <q-form @submit.prevent="submitForm()" class="bg-white shadow-md rounded q-pa-md">
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                      Email
                    </label>
                    <q-input
                      v-model.trim="form.correo"
                      type="email"
                      id="email"
                      dense
                      outlined
                      autofocus
                      :rules="[
                        val => !!val || 'Email es obligatorio',
                        val => /.+@.+\..+/.test(val) || 'Email es inválido',
                      ]"
                    />
                  </div>
                  <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                      Password
                    </label>
                    <q-input
                      v-model.trim="form.password"
                      type="password"
                      id="password"
                      dense
                      outlined
                      :rules="[val => !!val || 'Password es obligatorio',]"
                    />
                  </div>  
                  <div class="text-center q-mt-md">
                    <q-btn
                      color="primary"
                      type="submit"
                      v-if="!mostrarSpinner"
                    >
                      Iniciar Sesión
                    </q-btn>
                    <q-spinner-oval
                      v-else
                      color="primary"
                      size="2em"
                    />
                  </div>
                </q-form>
              </q-tab-panel>
            </q-tab-panels>
          </q-card>
        </div>
        <div class="col-4"></div>
      </div>
    </div>
    
  </div>
</template>

<script>
import { notify } from "../Utils/notify.js";
import { loading } from "../Utils/loading.js";
export default {
  data() {
    return {
      form: {
        correo: "",
        password: "",
        tipo: "alumno"
      },
      tab: "sistema",
      mostrarSpinner: false,
    };
  },
  created() {
    loading(false);
  },
  updated() {
    this.mostrarSpinner = false;
    const { status, error } = this.$page.props;
    if (status >= 300) {
      this.showNotif(error, 'error');
    }
  },
  methods: {
    async submitForm() {
      let form = {};
      try {
        
        this.mostrarSpinner = true;
        form = {
          correo: this.form.correo,
          password: this.form.password,
          tipo: "sistema"
        }
        this.$inertia.post("/login", form);
      } catch (error) {
        notify(error, 'error');
      }
    },
    guardarTipo(tipo) {
      this.form.tipo = tipo;
    },
    limpiarDatos() {
      this.form.correo = "";
      this.form.password = "";
    },
    showNotif (message, tipo) {
      return notify(message, tipo);
    }
  },
};
</script>

<style scoped>
  .centered-div {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
  .background-image {
    background-image: url('/resources/images/bg-login.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
</style>