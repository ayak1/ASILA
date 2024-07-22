<!-- /destination/_name.vue -->

<template>
  <div class="destinationPage">
    <DestinationHero />
    <div class="main_container ">
    <!-- <DestinationPopularAreasSection /> -->
    <DestinationOurTourismServicesSection />
   </div>
  </div>
</template>

<script>
import LogoOnSide from '../../components/LogoOnSide.vue';
// import {mapGetters,mapActions,mapMutations,mapState} from 'vuex';

export default {
  components: { LogoOnSide },
  computed: {
  },
  methods: {
  },

};
</script>

<style scoped>
.destinationPage{
  width: 100%;
  position: relative;
}

</style>
