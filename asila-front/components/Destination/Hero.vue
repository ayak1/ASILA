<template>
  <div v-if="selectedCity" class="heroOfPage">
    <div class="top-of-image topImage"></div>
    <img v-if="selectedCity.cover_image" class="coverImage top-of-image" :src="selectedCity.cover_image" :alt="selectedCity.name">
    <div class="hero_text main_container">
      <p class="cityName textColorGradient">{{ selectedCity.name }}</p>
      <p class="description">{{ selectedCity.description }}</p>
      <p class="hero_call">{{ $t('hero_call') }}</p>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
  computed: {
    ...mapState('cities', ['selectedCity']),
  },
  watch: {
    '$route': 'fetchCity',
    '$i18n.locale': 'fetchCity'
  },
  methods: {
    ...mapActions('cities', ['fetchCityById']),
    async fetchCity() {
      const cityId = this.$route.query.cityId;
      if (cityId) {
        await this.fetchCityById({ id: cityId });
      }
    },
  },
  async created() {
    await this.fetchCity();
  },
}
</script>




<style>
.heroOfPage {
  position: relative;
  width: 100%;
  height: 100vh; /* Adjust the height as needed */
}
.coverImage {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.cityName{
  text-align: center;
  text-shadow: 0px 10px 10px rgba(0, 0, 0, 0.25);
  font-size: var(--fs_xl_800);
  font-weight: 800;
  line-height: 150%; /* 8.4375rem */
  letter-spacing: -0.12938rem;
  margin-bottom: 37px;
}
.description{
  color: var(--primary_light_color);
  font-size: var(--fs_m_800);
  font-weight: 800;
  line-height: 150%; /* 75px */
  letter-spacing: -1.15px;
  margin-bottom: 25px;
}
.hero_text{
  z-index: 2;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.hero_call {
  position: relative;
  color: var(--primary_light_color);
  font-size: var(--fs_s_800);
  font-weight: 800;
  line-height: 150%; /* 60px */
  letter-spacing: -0.92px;
  text-align: center;
  width: fit-content;
  padding: 0 20px;
  padding-bottom: 4px;
  border-bottom: 3px solid transparent; /* Reserve space for the border */
}

.hero_call::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 3px; /* Adjust based on the border size */
  background: linear-gradient(90deg, rgba(191,149,63,1) 0%, rgba(252,246,186,1) 18%, rgba(179,135,40,1) 31%, rgba(179,135,40,1) 75%, rgba(251,245,183,1) 87%, rgba(170,119,28,1) 98%);
}
@media(max-width: 1000px) {
  .call-nav{
    padding: 0px 15px;
  }
}
</style>
