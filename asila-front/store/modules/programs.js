// store/modules/programs.js

import * as programsApi from '~/api/programs';

const localStorageKey = 'selectedProgram';

export const state = () => ({
  programs: [],
  currentPage: 1,
  lastPage: 1,
  totalPrograms: 0,
  selectedProgram: [],
});

export const mutations = {
  setPrograms(state, { programs, meta }) {
    state.programs = programs;
    state.currentPage = meta.current_page;
    state.lastPage = meta.last_page;
    state.totalPrograms = meta.total;
  },
  setSelectedProgram(state, programSelected) {
    state.selectedProgram = programSelected;
    if (process.client) {
      localStorage.setItem(localStorageKey, JSON.stringify(programSelected));
    }
  },
};

export const actions = {
  async fetchProgramsByCity({ commit }, { cityId }) {
    try {
      const lang = this.app.i18n.locale;
      const { programs, meta } = await programsApi.getProgramsByCity(cityId, lang);
      commit('setPrograms', { programs, meta });
    } catch (error) {
      console.error('Error fetching programs:', error);
      throw error;
    }
  },
  async fetchSelectedProgram({ commit }, programId) {
    try {
      const lang = this.app.i18n.locale;
      const programSelected = await programsApi.getProgram(programId, lang);
      commit('setSelectedProgram', programSelected);
    } catch (error) {
      console.error('Error fetching selected program:', error);
      throw error;
    }
  },
  async fetch_program_by_id({commit},{programId}){
    try{
      const lang = this.app.i18n.locale;
      const programById = await programsApi.getProgram(programId , lang);
      commit('setSelectedProgram', programById);
    } catch (error){
      console.error("Error fetching program by ID:", error);
    }
  }
};

export const getters = {
  getProgramsByCity: (state) => state.programs,
  getSelectedProgram: (state) => {
    console.log("state/getSelectedProgram")

    if (process.client) {
      return JSON.parse(localStorage.getItem(localStorageKey)) || null;
    }
    return state.selectedProgram;
  },
};
