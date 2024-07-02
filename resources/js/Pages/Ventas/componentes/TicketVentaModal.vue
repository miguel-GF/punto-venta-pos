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
        <q-btn ref="btnAceptar" label="Cerrar" color="secondary" @click="aceptar()" />
        <q-btn ref="btnAceptar" label="Imprimir" color="primary" @click="printPDF()" />
        <q-btn ref="btnAceptar" label="Imprimir IFRAME" color="info" @click="printPDFIframe()" />
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
    printPDF() {
      const fullBase64 = this.base64;
      printJS({ printable: fullBase64, type: 'pdf', base64: true });
    },
    printPDFIframe() {
      const base64PDF = 'JVBERi0xLjMKJcfsj6IKNSAwIG9iaiA8PC9MZW5ndGggMTU5Pj4Kc3RyZWFtCkpUc0IKMSAwIG9iaiA8PC9Db2xvclNwYWNlIC9EZXZpY2VSR0IgL1N1YnR5cGUgL1hNTE1FVC9UeXBlIC9YTUwgL1Jvb3QgMyAwIFIvUmVzb3VyY2VzIDw8L0ZvbnQgPDwvRjEgNyAwIFI+PiAvUHJvY1NldCAvUERGIC9NZXRhZGF0YSA4IDAgUiA+PiA+PgplbmRvYmoKNCAwIG9iaiA8PC9MZW5ndGggMTQxPj4Kc3RyZWFtCkpUc0IKPGRvY3VtZW50PjxwYWdlcz48cGFnZT48bW51bT4xPC9tbnVtPjwvcGFnZT48L3BhZ2VzPjwvZG9jdW1lbnQ+Cj4+CmVuZHN0cmVhbQplbmRvYmoKNiAwIG9iaiA8PC9UeXBlIC9Gb250RGVzY3JpcHRvci9Gb250TmFtZSAvSGVsdmV0aWNhLUJvbGQvRm9udEJCb3VuZGluZ0JveCBbIC0zMiAtMjggMzYgMzYgXSBwZXJjZW50PSAxMjAwPj4KZW5kb2JqCjcgdHlwZT0iRm9udCIgPDwvVHlwZSAvRm9udC9TdWJ0eXBlIC9UeXBlMS9CYXNlRm9udCBOYW1lIC9IZWx2ZXRpY2EtQm9sZD4+CmVuZG9iago4IDAgb2JqIDw8L1R5cGUgL01ldGFkYXRhL0NyZWF0b3IgKENyZWF0b3IgTmFtZSk+PgplbmRvYmoKMyAwIG9iaiA8PC9UeXBlIC9QYWdlcy9LaWRzIFsgMSAwIFIgXS9Db3VudCAxPj4KZW5kb2JqCjkgMCBvYmoKPDwvTGVuZ3RoIDEzPj4Kc3RyZWFtCkJUUwo8L2NyZWF0b3IgKGNyZWF0b3IgZGV2ZWxvcGVyIG5hbWUpPgp0cmFpbGVyCjw8L1Jvb3QgMyAwIFIvU2l6ZSAxMCA+PgpzdGFydHhyZWYKOTgKJSVFT0Y=';
      const iframe = document.createElement('iframe');
      iframe.style.display = 'none';
      iframe.src = 'data:application/pdf;base64,' + base64PDF;
      document.body.appendChild(iframe);

      // Espera a que el iframe cargue y luego imprime
      iframe.onload = () => {
          iframe.contentWindow.print();
          document.body.removeChild(iframe); // Limpia el DOM después de la impresión
      };
    }
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