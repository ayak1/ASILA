// store/modules/restaurants.js

import * as restaurantsApi from '~/api/restaurants';
const localStorageKey = 'selectedRestaurant';

export const state = () => ({
  restaurants: [],
  selectedRestaurant:[],
});

export const mutations = {
  // define methods to set state
  setRestaurants(state, restaurants) {
    state.restaurants = restaurants;
  },
  setSelectedRestaurant(state, restaurantSelected){
    state.selectedRestaurant = restaurantSelected;
    if (process.client) {
      localStorage.setItem(localStorageKey, JSON.stringify(restaurantSelected));
    }
  },
};

export const actions = {
  async fetch_restaurants_by_city({ commit },{cityId}) {
    try {
      const lang = this.app.i18n.locale;
      const restaurants = await restaurantsApi.getRestaurantsByCity(cityId,lang);
      commit('setRestaurants', restaurants);
    } catch (error) {
      console.error('Error fetching restaurants by city:', error);
    }
  },
  async fetch_selected_restaurant({commit,state},restaurantSelected){
    await commit('setSelectedRestaurant', restaurantSelected);
  },
  async fetch_restaurant_by_id({commit},{restaurantId}){
    try{
      const lang = this.app.i18n.locale;
      const restaurantById = await restaurantsApi.getRestaurant(restaurantId , lang);
      commit('setSelectedRestaurant', restaurantById);
    } catch (error){
      console.error("Error fetching restaurant by ID:", error);
    }
  }
};

export const getters = {
  getRestaurants: (state) => state.restaurants,
  getSelectedRestaurant: (state) =>{
    if (process.client) {
      return JSON.parse(localStorage.getItem(localStorageKey)) || null;
    }
    return state.restaurantSelected || null;
  },
};

