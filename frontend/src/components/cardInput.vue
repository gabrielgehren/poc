<script setup>
import { reactive, ref, defineEmits } from "vue";
import { useCalculatorStore } from "../stores/useCalculatorStore";
import importComponent from "../components/importComponent.vue";
import Papa from 'papaparse';

const calculatorStore = useCalculatorStore();
const emit = defineEmits(['alertEvent']);

const input = reactive({
  cep_origem: '',
  cep_destino: ''
});

const loadStateCadastro = ref(false);
const loadStateImport = ref(false);
const fileInput = ref(null);

const buttonActions = (function(){
  async function cadastrar() {
    try {
      loadStateCadastro.value = true;
      await calculatorStore.cadastrarGeolocalizacao(input.cep_origem, input.cep_destino);
    } catch (error) {
      emit('alertEvent', { error: error.message });
    } finally {
      loadStateCadastro.value = false;
    }
  }

  return {
    cadastrar
  };
})();
</script>

<template>
  <div class="container" style="margin-top: 5em">
    <div class="card shadow p-3 mb-5 bg-body rounded">
      <div class="card-body">
        <div class="row justify-content-center align-items-center text-center mb-3">
          <div class="col-sm-3">
            <div class="input-group">
              <input
                v-model="input.cep_origem"
                type="text"
                class="form-control"
                placeholder="CEP Origem"
                aria-label="CEP Origem"
              />
            </div>
          </div>
          <div class="col-sm-3">
            <input
              v-model="input.cep_destino"
              type="text"
              class="form-control"
              placeholder="CEP Destino"
              aria-label="CEP Destino"
            />
          </div>
          <div class="col-sm-3">
            <button @click="buttonActions.cadastrar()" class="btn btn-primary w-100" type="button" :disabled="loadStateCadastro">  
              Cadastrar
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="loadStateCadastro"></span>
            </button>
          </div>
        </div>
        
        <importComponent />
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
}

.input-group {
  width: 100%;
}

.btn {
  width: 100%;
}
</style>
