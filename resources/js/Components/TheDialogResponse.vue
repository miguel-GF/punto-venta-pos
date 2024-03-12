<template>
  <q-dialog v-model="mostrar" persistent @show="onShow()">
    <q-card class="q-pb-md" :class="classesCard">
      <q-card-section class="row justify-center">
        <div class="text-h6 ellipsis">{{ titulo }}</div>
      </q-card-section>

      <q-separator />

      <q-card-section class="card-body-height scroll q-mb-md">
        <div class="q-mb-md text-center">
          <template v-if="tipo == 'exito'">
            <q-icon name="check" size="xl" class="text-blue" />
          </template>
        </div>
        <div class="text-center" v-html="mensaje"></div>
        <slot name="body"></slot>
      </q-card-section>

      <q-card-actions align="center">
        <q-btn ref="btnAceptar" label="Aceptar" color="primary" @click="aceptar()" />
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
      default: 'Éxito',
    },
    mensaje: {
      type: String,
      default: 'Mensaje default ...',
    },
    tipo: {
      type: String,
      default: 'exito',
    },
    classesCard: {
      type: String,
      default: 'card-width',
    },
  },
  methods: {
    aceptar() {
      this.$emit('aceptar');
    },
    onShow() {
      this.$refs.btnAceptar.$el.focus();
    }
  }
};
</script>

<style scoped>
.card-width {
  width: 350px !important;
}

.card-width-450 {
  width: 450px !important;
}

.card-body-height {
  max-height: 50vh;
}
</style>