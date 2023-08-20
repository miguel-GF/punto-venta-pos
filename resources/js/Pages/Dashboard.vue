<template>
  <MainLayout>
    <div class="text-center q-pt-xl">
      <p class="text-h3">
        Bienvenido de Nuevo!
      </p>
      <p class="text-h3">
        {{ usuario.nombre || '--' }}
      </p>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '../Layouts/MainLayout.vue';
export default {
  props: ["usuario"],
  components: { MainLayout }
};
</script>
