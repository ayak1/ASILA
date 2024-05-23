// store/modules/cities.js
import * as citiesApi from '~/api/cities';
const localStorageKey = 'selectedCity';

export const state = () => ({
  cities: [],
  selectedCity: null,
});

export const mutations = {
  setCities(state, cities) {
    state.cities = cities;
  },
  setSelectedCity(state, city){
    state.selectedCity = city;
    if (process.client) {
      localStorage.setItem(localStorageKey, JSON.stringify(city));
    }
  }
};

export const actions = {
  async fetchCities({ commit }) {
    try {
      const lang = this.app.i18n.locale;
      const cities = await citiesApi.getCities(lang);
      commit('setCities', cities);
    } catch (error) {
    }
  },
  async fetchSelectedCity({ commit,state }, city) {
    commit('setSelectedCity', city);
  },
  async fetchCityById({ commit }, { id }) {
    try {
      const lang = this.app.i18n.locale;
      const cityById = await citiesApi.getCityById(id, lang);
      commit('setSelectedCity', cityById);
    } catch (error) {
      console.error('Error fetching city:', error);
    }
  },
};

export const getters = {
  getCities: (state) => state.cities,
  getSelectedCity: (state) =>{
    if (process.client) {
      return JSON.parse(localStorage.getItem(localStorageKey)) || null
    }
    return state.selectedCity || null;
  },
}
