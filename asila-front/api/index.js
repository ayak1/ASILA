// store/index.js
import Vuex from 'vuex';
import Vue from 'vue';

import * as cities from './modules/cities'; // Import your store modules
import * as areas from './modules/areas'; // Import your store modules
import * as car_services from './modules/car_services'; // Import your store modules
import * as packages from './modules/packages'; // Import your store modules
import * as programs from './modules/programs'; // Import your store modules
import * as langSelected from './modules/langSelected'; // Import your store modules

Vue.use(Vuex);
const createStore = () => {
  return new Vuex.Store({
    modules: {
      cities: {
        namespaced: true,
        ...cities,
      },
      areas: {
        namespaced: true,
        ...areas,
      },
      car_services: {
        namespaced: true,
        ...car_services,
      },
      packages: {
        namespaced: true,
        ...packages,
      },
      programs: {
        namespaced: true,
        ...programs,
      },
      langSelected:{
        namespaced: true,
        ...langSelected,
      }
      // Add other store modules if you have them
    },
    // Add any other Vuex store options as needed
  });
};

export default createStore;
