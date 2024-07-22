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
  async fetchSelectedLang({ commit }, lang) {
    commit('setSelectedLang', lang);
  },
  async switchLanguage({ commit, state }, { lang, route }) {
    commit('setSelectedLang', lang);
    this.$i18n.setLocale(lang);

    const newRoute = {
      ...route,
      params: { ...route.params, 0: lang },  // Ensuring the language prefix
      query: { ...route.query }
    };

    await this.$router.push(newRoute);
  }
};

export const getters = {
  getSelectedLang: state => state.selectedLanguage || 'en'
};
