export default function ({ app }, inject) {
  inject('isRTL', function() {
    return app.i18n.locale === 'ar';
  });
}
