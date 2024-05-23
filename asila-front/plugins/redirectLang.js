// plugins/redirectLang.js
export default function ({ app, store, route, redirect }) {
  const previousPaths = store.state.previousPaths || {};

  if (route.params.lang) {
    const currentPath = route.fullPath.replace(`/${route.params.lang}`, '');

    if (previousPaths[route.params.lang] && previousPaths[route.params.lang] !== currentPath) {
      return redirect(302, previousPaths[route.params.lang]);
    }

    store.commit('setPreviousPath', { lang: route.params.lang, path: currentPath });
  }
}
