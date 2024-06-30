<template>
  <q-dialog v-model="mostrar" persistent @show="loadPdf">
    <q-card class="q-pb-md card-width-450">
      <q-card-section class="row justify-center">
        <div class="text-h6 ellipsis">Venta - {{ ventaObj.serie_folio || "--" }}</div>
      </q-card-section>

      <q-separator />

      <q-card-section class="card-body-height scroll q-mb-md">
        <iframe allowfullscreen type="application/pdf" id="pdf" style="width: 100%; height: 400px;"></iframe>
      </q-card-section>

      <q-card-actions align="center">
        <q-btn ref="btnAceptar" label="Cerrar" color="primary" @click="aceptar()" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'TicketVentaModal',
  props: {
    mostrar: {
      type: Boolean,
      default: false,
    },
    ventaObj: {
      type: Object,
      default: () => ({}),
    },
    base64: {
      type: String,
      default: '',
    },
  },
  methods: {
    aceptar() {
      this.$emit('aceptar');
    },
    async loadPdf() {
      if (!this.base64) return;

      try {
        // Decodificar la cadena base64 del PDF
        const pdfData = atob(this.base64);
        const uint8Array = new Uint8Array([...pdfData].map((char) => char.charCodeAt(0)));

        // Cargar el documento PDF desde Uint8Array
        const pdfDoc = await PDFLib.PDFDocument.load(uint8Array);

        // Convertir el PDF a base64 para cargarlo en el iframe
        const pdfDataUri = await pdfDoc.saveAsBase64({ dataUri: true });

        // Establecer el contenido del iframe con el PDF base64
        const iframe = document.getElementById('pdf');
        iframe.src = pdfDataUri;

      } catch (error) {
        console.error('Error cargando el PDF:', error);
      }
    },
  },
});
</script>

<style scoped>
.card-width-450 {
  width: 450px !important;
}

.card-body-height {
  max-height: 60vh;
  overflow: auto;
}
</style>
