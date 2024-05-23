// store/modules/programs.js

import * as programsApi from '~/api/programs';
const localStorageKey = 'selectedProgram';

export const state = () => ({
  programs: [],
  selectedProgram:[],
});

export const mutations = {
  setPrograms(state, programs) {
    state.programs = programs;
  },
  setSelectedProgram(state, programSelected){
    state.selectedProgram = programSelected;
    if (process.client) {
      localStorage.setItem(localStorageKey, JSON.stringify(programSelected));
    }
  },
};

export const actions = {
  async fetch_programs_by_city({ commit },{cityId}) {
    try {
      const lang = this.app.i18n.locale;
      const programs = await programsApi.getProgramsByCity(cityId,lang);
      commit('setPrograms', programs);
    } catch (error) {
      console.error('Error fetching programs:', error);
    }
  },
  async fetch_selected_program({commit,state},programSelected){
    await commit('setSelectedProgram', programSelected);
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
  getSelectedProgram: (state) =>{
    if (process.client) {
      return JSON.parse(localStorage.getItem(localStorageKey)) || null;
    }
    return state.programSelected || null;
  },
};

