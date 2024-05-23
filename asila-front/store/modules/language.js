// store/modules/language.js
export const state = () => ({
  selectedLanguage: 'en'
});

export const mutations = {
  setSelectedLang(state, lang) {
    state.selectedLanguage = lang;
  }
};

export const actions = {
  async fetchSelectedLang({ commit,state }, lang) {
    commit('setSelectedLang', lang);
  },
};
