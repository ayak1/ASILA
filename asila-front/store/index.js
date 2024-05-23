// store/index.js
import Vuex from 'vuex';
import Vue from 'vue';

import * as cities from './modules/cities'; // Import your store modules
import * as areas from './modules/areas'; // Import your store modules
import * as packages from './modules/packages'; // Import your store modules
import * as car_services from './modules/car_services'; // Import your store modules
import * as programs from './modules/programs'; // Import your store modules
import * as extraServices from './modules/extraServices'; // Import your store modules
import * as apartments from './modules/apartments'; // Import your store modules
import * as previousPaths from './modules/previousPaths'; // Import your store modules
import * as language from './modules/language'; // Import your store modules
import * as hotels from './modules/hotels'; // Import your store modules
import * as restaurants from './modules/restaurants'; // Import your store modules
import * as toursDestinations from './modules/tours_destinations'; // Import your store modules
// import * as langSelected from './modules/langSelected'; // Import your store modules

Vue.use(Vuex);
const createStore = () => {
  return new Vuex.Store({
    modules: {
      previousPaths:{
        namespaced: true,
        ...previousPaths,
      },
      language:{
        namespaced: true,
        ...language,
      },
      cities: {
        namespaced: true,
        ...cities,
      },
      areas: {
        namespaced: true,
        ...areas,
      },
      packages: {
        namespaced: true,
        ...packages,
      },
      car_services: {
        namespaced: true,
        ...car_services,
      },
      programs: {
        namespaced: true,
        ...programs,
      },
      extraServices: {
        namespaced: true,
        ...extraServices,
      },
      apartments: {
        namespaced: true,
        ...apartments,
      },
      hotels: {
        namespaced: true,
        ...hotels,
      },
      restaurants: {
        namespaced: true,
        ...restaurants,
      },
      toursDestinations: {
        namespaced: true,
        ...toursDestinations,
      },
      // langSelected:{
      //   namespaced: true,
      //   ...langSelected,
      // }
    },
  });
};

export default createStore;
