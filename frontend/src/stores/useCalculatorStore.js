import { defineStore } from 'pinia'
import axiosInstance from '../../infra/axios';

export const useCalculatorStore = defineStore('useCalculatorStore', {
  state: () => ({
    calculosCadastrados: [],
    fileForProcess:[]
  }),
  getters: {
    getCalculosCadastrados: (state) => state.calculosCadastrados,
    getFileForProcess: (state) => state.fileForProcess
  },
  actions: {
    setFileToProcess(data) {
      this.fileForProcess = data;
    },
    async cadastrarGeolocalizacao(origem,destino) {
      const arrZipcodes = await Promise.all(
          [this.fetchGeoLocFromCep(origem), this.fetchGeoLocFromCep(destino)]
      )
      const dataz = await this.putLocation(arrZipcodes);
      this.fetchAllDistancias();
      return dataz;
    },
    async fetchGeoLocFromCep(code) {
      const zipCode = code.replace(/\D/g, '');
      if (zipCode.length !== 8) {
        throw new Error(`Cep ${zipCode} invalido!`);
      }
      const response = await axiosInstance.get('https://brasilapi.com.br/api/cep/v2/'+zipCode);
      const zipCodeData = response.data;
      if (typeof zipCodeData.location.coordinates.longitude == 'undefined' || zipCodeData.location.coordinates.longitude == null) {
         throw new Error(`Cep ${zipCode} não possui coordenadas!`);
      }
      return zipCodeData;
    },
    async putLocation(arrZipCodes) {
      const response = await axiosInstance.put('http://localhost/POC/registrarDistancia', {
        origem: arrZipCodes[0],
        destino: arrZipCodes[1],
      });
      return response.data;
    },
    async saveImportLocations(data) {
      const response = await axiosInstance.put('http://localhost/POC/importDistances', data);
      await this.fetchAllDistancias();
      return response.data;
    },
    async fetchAllDistancias() {
      const response = await axiosInstance.get('http://localhost/POC/listar/all');
      this.calculosCadastrados = response.data.resultado;
    },
    async excluirRegistro(id) {
      const response = await axiosInstance.delete('http://localhost/POC/excluir', {
        data: {
          id: id
        }
      });
      await this.fetchAllDistancias();    
    },
    async importCeps(data) {
      const pullRequst = data.map(async (cep) => {
        const dadosCep =  [];
        dadosCep[0] = await this.fetchGeoLocFromCep(cep[0]);
        dadosCep[1] = await this.fetchGeoLocFromCep(cep[1]);
        const lat1 =  dadosCep[0].location.coordinates.latitude;
        const lon1 =  dadosCep[0].location.coordinates.longitude;
        const lat2 =  dadosCep[1].location.coordinates.latitude;
        const lon2 =  dadosCep[1].location.coordinates.longitude;

        const distancia = this.haversineDistance(
          lat1, lon1, lat2, lon2
        );

        return [
          cep[0],
          cep[1],
          distancia
        ];
      });
      const result = await Promise.all(pullRequst);
      this.saveImportLocations(result);
      return result;
    },
    haversineDistance(lat1, lon1, lat2, lon2) {
      const toRad = (value) => value * Math.PI / 180;
    
      const R = 6371; // Raio da Terra em km
      const dLat = toRad(lat2 - lat1);
      const dLon = toRad(lon2 - lon1);
    
      const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
      
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      const distance = R * c; // Distância em km
    
      return distance;
    }
  }
});