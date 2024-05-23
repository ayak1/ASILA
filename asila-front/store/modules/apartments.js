// store/modules/apartments.js

import * as apartmentsApi from '~/api/apartments';

export const state = () => ({
  apartments: [],
  selectedApartment:[]
});

export const mutations = {
  // define methods to set state
  setApartments(state, apartments) {
    state.apartments = apartments;
  },
  setSelectedApartment(state, apartment) {
    state.selectedApartment = apartment;
  },
};

export const actions = {
  //  fetch data and commit it to state by calling the mutations
  async fetch_apartments({ commit },{forRent}) {
    try {
      const lang = this.app.i18n.locale;
      let apartmentsData = null;
      if(forRent==true){
        apartmentsData = await apartmentsApi.getApartmentsForRent(lang);
      }else{
        apartmentsData = await apartmentsApi.getApartmentsForSale(lang);
      }
      commit('setApartments', apartmentsData);
    } catch (error) {
      console.error('Error fetching apartments:', error);
    }
  },
  async fetch_selected_apartment({commit,state},apartmentSelected){
    await commit('setSelectedApartment', apartmentSelected);
  },
  async fetch_apartment_by_id({commit},{apartmentId}){
    try{
      const lang = this.app.i18n.locale;
      const apartmentById = await apartmentsApi.getApartment(apartmentId , lang);
      commit('setSelectedApartment', apartmentById);
    } catch (error){
      console.error("Error fetching apartment by ID:", error);
    }
  }
};

export const getters = {
  getApartments: (state) => state.apartments,
  getSelectedApartment: (state) =>{
    if (process.client) {
      return JSON.parse(localStorage.getItem(localStorageKey)) || null;
    }
    return state.selectedApartment || null;
  },
};
