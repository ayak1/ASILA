// store/modules/previousPaths.js
export const state = () => ({
  previousPaths: {} // Initialize previousPaths state
});

export const mutations = {
  setPreviousPath(state, { lang, path }) {
    // Ensure the language entry exists
    if (!state.previousPaths[lang]) {
      state.previousPaths[lang] = [];
    }
    // Add the path to the language's previous paths
    state.previousPaths[lang].push(path);
  }
};
