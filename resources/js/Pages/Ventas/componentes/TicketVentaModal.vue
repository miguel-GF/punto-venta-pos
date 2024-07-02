<template>
  <q-dialog v-model="mostrar" persistent>
    <q-card class="q-pb-md card-width-450">
      <q-card-section class="row justify-center">
        <div class="text-h6 ellipsis">Venta - {{ ventaObj.serie_folio || "--" }}</div>
      </q-card-section>

      <q-separator />

      <q-card-section class="card-body-height scroll q-mb-md">
        <iframe
          width="100%"
          height="400"
          :src="'data:application/pdf;base64,'+base64" frameborder="0"
        >
        </iframe>
      </q-card-section>

      <q-card-actions align="center">
        <q-btn ref="btnAceptar" label="Cerrar" color="primary" @click="aceptar()" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  name: "TicketVentaModal",
  props: {
    mostrar: {
      type: Boolean,
      default: false,
    },
    ventaObj: {
      type: Object,
      default: () => {},
    },
    base64: {
      type: String,
      default: "",
    },
  },
  methods: {
    aceptar() {
      this.$emit('aceptar');
    },
  }
};
</script>

<style scoped>
.card-width-450 {
  width: 450px !important;
}

.card-body-height {
  max-height: 60vh;
}
</style>