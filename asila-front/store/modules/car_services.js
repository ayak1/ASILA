// store/modules/car_services.js

import * as carServicesApi from '~/api/car-services';

export const state = () => ({
  car_services: [],
});

export const mutations = {
  // define methods to set state
  SET_CAR_SERVICES(state, car_services) {
    state.car_services = car_services;
  },

};

export const actions = {
  //  fetch data and commit it to state by calling the mutations
  async fetch_car_services({ commit }) {
    try {
      const lang = this.app.i18n.locale;
      const car_services = await carServicesApi.getCarServices(lang);
      commit('SET_CAR_SERVICES', car_services);
    } catch (error) {
      console.error('Error fetching car_services:', error);
    }
  },
};

export const getters = {
  getCarServices: (state) => state.car_services,
};

