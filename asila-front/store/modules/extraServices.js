// store/modules/car_services.js

import * as extraServicesApi from '~/api/extraServices';

export const state = () => ({
  extra_service: null,
});

export const mutations = {
  // define methods to set state
  setExtraService(state, extra_service) {
    state.extra_service = extra_service;
  },

};

export const actions = {
  //  fetch data and commit it to state by calling the mutations
  async fetch_extra_service({ commit },{slug}) {
    try {
      const lang = this.app.i18n.locale;
      const extraService = await extraServicesApi.getExtraService(slug,lang);
      commit('setExtraService', extraService);
    } catch (error) {
      console.error('Error fetching extraService:', error);
    }
  },
};

export const getters = {
  getExtraService: (state) => state.extraService,
};

