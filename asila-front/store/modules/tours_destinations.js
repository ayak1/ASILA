// store/modules/tours_destinations.js

import * as toursDestinationsApi from '~/api/tours_destinations';
const localStorageKey = 'selectedRestaurant';

export const state = () => ({
  toursDestinations: [],
  selectedToursDestination:[],
});

export const mutations = {
  // define methods to set state
  setToursDestinations(state, toursDestinations) {
    state.toursDestinations = toursDestinations;
  },
  setSelectedDestination(state, toursDestinationSelected){
    state.selectedToursDestination = toursDestinationSelected;
    if (process.client) {
      localStorage.setItem(localStorageKey, JSON.stringify(toursDestinationSelected));
    }
  },
};

export const actions = {
  async fetch_toursDestinations({ commit }) {
    try {
      const lang = this.app.i18n.locale;
      const toursDestinations = await toursDestinationsApi.getToursDestinations(lang);
      commit('setToursDestinations', toursDestinations);
    } catch (error) {
      console.error('Error fetching toursDestinations by city:', error);
    }
  },
  async fetch_selected_destination({commit,state},toursDestinationSelected){
    const destSelected = fetch_destination_by_id()
    await commit('setSelectedDestination', toursDestinationSelected);
  },
  async fetch_destination_by_id({commit},{cityId,destinationId}){
    try{
      const lang = this.app.i18n.locale;
      const destinationById = await toursDestinationsApi.getByCityAndDestinationType(cityId,destinationId , lang);
      commit('setSelectedDestination', destinationById);
    } catch (error){
      console.error("Error fetching destination by ID:", error);
    }
  }
};

export const getters = {
  getToursDestinations: (state) => state.toursDestinations,
  getSelectedToursDestinations: (state) =>{
    if (process.client) {
      return JSON.parse(localStorage.getItem(localStorageKey)) || null;
    }
    return state.selectedToursDestination || null;
  },
};

