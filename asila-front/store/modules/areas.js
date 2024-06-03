// store/modules/areas.js

import * as areasApi from '~/api/areas';

export const state = () => ({
  areas: [],
  popularAreas: [],
});

export const mutations = {
  setAreas(state, areas) {
    state.areas = areas;
  },
  setPopularAreas(state, popularAreas) {
    console.log("setPopularAreas",popularAreas)
    state.popularAreas = popularAreas;
  },
};

export const actions = {
  async fetchPopularAreas({ commit },{cityId}) {
    try {
      const lang = this.app.i18n.locale;
      const popularAreas = await areasApi.getPopularArea(cityId,lang);
      console.log("fetch popular",popularAreas)
      commit('setPopularAreas', popularAreas);
    } catch (error) {
      console.error('Error fetching popular areas:', error);
    }
  },
};

export const getters = {
  getPopularArea: (state) => state.popularAreas,
};

