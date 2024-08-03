<script setup>
import { ref, defineEmits } from "vue";
import { useCalculatorStore } from "../stores/useCalculatorStore";
import Papa from "papaparse";

const emit = defineEmits(["alertEvent"]);
const calculatorStore = useCalculatorStore();

const importLoadState = ref(false);
const fileInput = ref(null);
var dataFileToProcess = [];

const importActions = (() => {
  async function importar() {
    try {
      importLoadState.value = true;
      if (dataFileToProcess.length < 1) {
        throw new Error("Selecione uma arquivo!");
      }
      await calculatorStore.importCeps(dataFileToProcess);
    } catch (error) {
      alert(error.message);
    } finally {
      importLoadState.value = false;
    }
  }

  function lerArquivo(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
      const csv = e.target.result;
      dataFileToProcess = Papa.parse(csv).data;
    };
    reader.readAsText(file);
  }

  function extractNumbers(input) {
    return input.replace(/\D/g, "");
  }

  return {
    importar,
    lerArquivo,
  };
})();
</script>

<template>
  <div class="row justify-content-center align-items-center text-center">
    <div class="col-sm-9 d-flex">
      <input
        class="form-control me-2"
        type="file"
        ref="fileInput"
        @change="importActions.lerArquivo"
        accept=".csv"
      />
      <button
        @click="importActions.importar()"
        class="btn btn-primary"
        type="button"
        :disabled="importLoadState"
      >
        Importar
        <span
          class="spinner-border spinner-border-sm"
          role="status"
          aria-hidden="true"
          v-show="importLoadState"
        ></span>
      </button>
    </div>
  </div>
</template>

<style scoped>
button {
  float: left;
}
</style>
