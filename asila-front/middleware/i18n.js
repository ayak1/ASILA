export default function ({ store, route, redirect }) {
  const selectedLang = store.getters['language/getSelectedLang'];
  const path = route.fullPath;
  const city = route.query.cityId;
  const currentLang = path.split('/')[1];
  const restOfPath = path.split('/').slice(2).join('/'); // Join segments after language code

  if (currentLang !== selectedLang) {
    return redirect({
      path: `/${selectedLang}${restOfPath ? `/${restOfPath}` : ''}`, // Append restOfPath if defined
      query: {
        cityId: city,
      },
    });
  }
}
