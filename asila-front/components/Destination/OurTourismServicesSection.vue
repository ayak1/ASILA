<template>
  <div class="">
    <p class="title">{{ $t('our_tourism_services') }}</p>
   <div class="services">
    <ul class="services-list">
      <li v-for="(serviceKey, key) in buttons" :key="key">
        <CardService :service_name="$t('services.' + serviceKey.labelKey)" :service_image="getImagePath(serviceKey.labelKey)" :page_route="serviceKey.route" @click="navigateToExtraService(serviceKey.route)"/>
      </li>
    </ul>
   </div>
  </div>
</template>
<script>
export default {
  data(){
    return {
      buttons: [
        { labelKey: 'car_services', route: 'car-with-driver', },
        { labelKey: 'programs', route: 'programs' },
        { labelKey: 'packages', route: 'packages' },
        { labelKey: 'touristDestinations', route: 'tourist-destinations' },
        { labelKey: 'restaurants', route: 'restaurants' },
        { labelKey: 'hotels', route: 'hotels' },
        { labelKey: 'flightTicketsReservations', route: 'flight-ticket-booking' },
        { labelKey: 'privateYachtRental', route: 'private-yacht-rental' },
        { labelKey: 'airportMeetAndGreet', route: 'airport-meet-and-greet' },
        { labelKey: 'propertiesForSaleAndRent', route: 'real-estate-for-sale-and-rent' },
        { labelKey: 'privateVillaReservations', route: 'private-villa-and-hotel-apartment-bookings' },
      ]
    };
  },
  methods: {
    getImagePath(serviceKey) {
      const imageFiles = require.context('~/assets/imgs/', false, /\.(jpg|jpeg|png)$/);

      const imagePath = imageFiles.keys().find(file => file.includes(serviceKey));

      if (imagePath) {
        return imageFiles(imagePath);
      }
      return null;
    },
    getExtraServicePageRoute(route) {
      return {
        name: 'ExtraService',
        params: { slug: this.generateSlug(route) },
      };
    },
    generateSlug(route) {
      // Replace this with your actual slug generation logic
      return route.replace(/-/g, '_');
    },
    navigateToExtraService(route) {
      const slug = this.generateSlug(route);
      this.$router.push(this.getExtraServicePageRoute(route));
      this.$store.dispatch('extraServices/fetch_extra_service', { slug });
    },
  },
}
</script>

<style>

.services{
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
.services-list{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  gap: 24px;
}
.services-list li{
  width: calc((100% - 48px)/3);
  height: 27.9259vh;
  max-height: 280px;
}
@media(max-width: 1700px) {
  .services-list li{
    height: 22.9259vh;
  }
}
@media(max-width: 1400px) {
  .services-list li{
    width: calc((100% - 24px)/2);
    height: 22.9259vh;
  }
}
@media (max-width:400px) {
  .services-list li{
    width: 100%;
    height: 17.9259vh;
  }
}
</style>
