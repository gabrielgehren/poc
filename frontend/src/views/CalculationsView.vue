<script setup>

import cardInput from '../components/cardInput.vue';
import cardTable from '../components/cardTable.vue';
 
const handleCustomEvent = (value) => {
  alert(value.error);
}

</script>
<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
}

.card {
  margin-bottom: 20px;
}
 
</style>
<template>
  <div class="container">
   <cardInput @alertEvent="handleCustomEvent"/>
   <cardTable @alertEvent="handleCustomEvent"/>
  </div>
</template>
