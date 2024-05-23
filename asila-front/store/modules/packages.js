// store/modules/packages.js

import * as packagesApi from '~/api/packages';
const localStorageKey = 'selectedPackage';

export const state = () => ({
  packages: [],
  selectedPackage:[],
});

export const mutations = {
  // define methods to set state
  setPackages(state, packages) {
    if (state.packages.length > 0) {
      state.packages.push(...packages);
    } else {
      state.packages = packages;
    }
  },
  setSelectedPackage(state, packageSelected){
    state.selectedPackage = packageSelected;
    if (process.client) {
      localStorage.setItem(localStorageKey, JSON.stringify(packageSelected));
    }
  },
};

export const actions = {
  async fetch_packages_by_city({ commit, state }, { cityId, page }) {
    try {
      const lang = this.app.i18n.locale;
      const packages = await packagesApi.getPackagesByCity(cityId, page, lang);
      commit('setPackages', packages);
    } catch (error) {
      console.error('Error fetching packages by city:', error);
    }
  },
  async fetch_selected_package({commit,state},packageSelected){
    await commit('setSelectedPackage', packageSelected);
  },
  async fetch_package_by_id({commit},{packageId}){
    try{
      const lang = this.app.i18n.locale;
      const packageById = await packagesApi.getPackage(packageId , lang);
      commit('setSelectedPackage', packageById);
    } catch (error){
      console.error("Error fetching package by ID:", error);
    }
  }
};

export const getters = {
  getPackages: (state) => state.packages,
  getSelectedPackage: (state) =>{
    if (process.client) {
      return JSON.parse(localStorage.getItem(localStorageKey)) || null;
    }
    return state.packageSelected || null;
  },
  // getPackageById:(state)=> state.packageById,
};

