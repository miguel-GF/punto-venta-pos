<template>
  <q-dialog v-model="mostrar" persistent>
    <q-card class="card-width">
      <q-card-section class="row">
        <div class="text-h6 ellipsis">{{ titulo }}</div>
        <q-space></q-space>
        <div>
          <q-btn rounded flat dense icon="close" @click="cerrar()"/>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section class="card-body-height scroll">
        <q-banner v-if="banner" dense :class="obtenerClass" class="rounded-borders q-pa-md q-mb-md">
          <template v-slot:avatar>
            <q-icon :name="obtenerIcono" />
          </template>
          {{ mensajeBanner || '--' }}
        </q-banner>
        <div class="text-center" v-html="mensaje"></div>
      </q-card-section>

      <q-separator />

      <q-card-actions align="center" class="q-py-md">
        <q-btn flat label="Cerrar" color="primary" @click="cerrar()" class="q-mr-sm" />
        <q-btn label="Aceptar" color="primary" @click="aceptar()" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  props: {
    mostrar: {
      type: Boolean,
      default: false,
    },
    titulo: {
      type: String,
      default: 'Título',
    },
    mensaje: {
      type: String,
      default: 'Mensaje default ...',
    },
    banner: {
      type: String,
      default: '',
    }
  },
  computed: {
    obtenerClass() {
      if (this.banner == 'eliminar') {
        this.mensajeBanner = 'Esta acción es irreversible';
        return 'bg-red';
      }
      else if (this.banner == 'confirmar') {
        this.mensajeBanner = 'Esta apunto de confirmar la acción';
        return 'bg-blue-5 text-white';
      }
      else if (this.banner == 'advertencia') {
        this.mensajeBanner = 'Esta acción es irreversible';
        return 'bg-amber-5';
      }
      return '';
    },
    obtenerIcono() {
      if (this.banner == 'eliminar') {
        return 'warning';
      }
      if (this.banner == 'confirmar') {
        return 'info';
      }
      if (this.banner == 'advertencia') {
        return 'warning';
      }
      return '';
    }
  },
  data() {
    return {
      mensajeBanner: '',
    }
  },
  methods: {
    cerrar() {
      this.$emit('cerrar');
    },
    aceptar() {
      this.$emit('aceptar');
    },
  }
};
</script>

<style scoped>
.card-width {
  width: 500px;
}

.card-body-height {
  min-height: 25vh;
  max-height: 50vh;
}
</style>