<script setup>
import { computed, ref } from "vue";
import { useCalculatorStore } from "../stores/useCalculatorStore";

const calculatorStore = useCalculatorStore();
calculatorStore.fetchAllDistancias();
const distancias = computed(() => calculatorStore.getCalculosCadastrados);

const loadStateExcluir = ref(false);

function deleteAction(id) {
  try {
      loadStateExcluir.value = true;
      calculatorStore.excluirRegistro(id);
  } catch (error) {
      emit('alertEvent', { error: error.message });
  } finally {
      loadStateExcluir.value = false;
  }
}

</script>
<template>
 
 <div class="row">
      <div class="col-sm">
        <div class="card shadow p-3 mb-5 bg-body rounded">
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CEP Origem</th>
                  <th scope="col">CEP Destino</th>
                  <th scope="col">Distância</th>
                  <th scope="col">Delete</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in distancias" :key="item.id">
                  <th scope="row">{{ item.id }}</th>
                  <td>{{ item.cep_origem }}</td>
                  <td>{{ item.cep_destino }}</td>
                  <td>{{ item.distancia }}</td>
                  <td>
                    <button @click="deleteAction(item.id)" class="btn btn-danger" type="button" >  
                      <i class="bi bi-trash"  v-show="!loadStateExcluir"></i>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="loadStateExcluir"></span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

</template>
<style scoped>
 
 
</style>