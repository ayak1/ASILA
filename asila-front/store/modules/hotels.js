// store/modules/hotels.js

import * as hotelsApi from '~/api/hotels';
const localStorageKey = 'selectedHotel';

export const state = () => ({
  hotels: [],
  selectedHotel:[],
});

export const mutations = {
  // define methods to set state
  setHotels(state, hotels) {
    if (state.hotels.length > 0) {
      state.hotels.push(...hotels);
    } else {
      state.hotels = hotels;
    }
  },
  setSelectedHotel(state, hotelSelected){
    state.selectedHotel = hotelSelected;
    if (process.client) {
      localStorage.setItem(localStorageKey, JSON.stringify(hotelSelected));
    }
  },
};

export const actions = {
  async fetch_hotels_by_city({ commit },{cityId }) {
    try {
      console.log("Fetching")
      const lang = this.app.i18n.locale;
      const hotels = await hotelsApi.getHotelsByCity(cityId,lang);
      console.log("hotels",hotels)
      commit('setHotels', hotels);
      console.log("state",state.hotels)
    } catch (error) {
      console.error('Error fetching hotels by city:', error);
    }
  },
  async fetch_selected_hotel({commit,state},hotelSelected){
    await commit('setSelectedHotel', hotelSelected);
  },
  async fetch_hotel_by_id({commit},{hotelId}){
    try{
      const lang = this.app.i18n.locale;
      const hotelById = await hotelsApi.getHotel(hotelId , lang);
      commit('setSelectedHotel', hotelById);
    } catch (error){
      console.error("Error fetching hotel by ID:", error);
    }
  }
};

export const getters = {
  getHotels: (state) => state.hotels,
  getSelectedHotel: (state) =>{
    if (process.client) {
      return JSON.parse(localStorage.getItem(localStorageKey)) || null;
    }
    return state.hotelSelected || null;
  },
};

