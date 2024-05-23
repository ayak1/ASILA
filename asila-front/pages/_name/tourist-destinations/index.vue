<template>
  <div class="main_container">
    <h1 class="title">{{$t('tours_destinations_title')}}</h1>
   <div class="destination_wrapper">
    <div class="destination border-ra-10" v-for="destination in toursDestinations" :key="destination.id" @click="navigateToDestination(destination)">
      <div  class="border-ra-10 top-of-image topImage"></div>
      <img class=" border-ra-10 coverImage top-of-image" :src="destination.cover_image" :alt="destination.name">
      <p>{{ destination.name }}</p>
    </div>
   </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations,mapGetters } from 'vuex';

export default {
  computed:{
    ...mapState('toursDestinations',['toursDestinations']),
  },
  watch: {
    '$i18n.locale': function(newVal, oldVal) {
      this.toursDestinationsData()
    }
  },
  methods:{
    ...mapMutations('toursDestinations', ['setToursDestinations']),
    ...mapMutations('toursDestinations', ['setSelectedDestination']),
    ...mapActions('toursDestinations', ['fetch_toursDestinations']),
    ...mapActions('toursDestinations', ['fetch_destination_by_id']),
    async toursDestinationsData(){
      await this.fetch_toursDestinations()
    },
    async navigateToDestination(destination) {
      const destinationId = destination.id;
      const cityId = this.$route.query.cityId
      console.log(destinationId,cityId);
      await this.fetch_destination_by_id({cityId, destinationId })
      const path = this.$route.path;
      this.$router.push({
        path: `${path}/${destination.id}`,
        query: {
          cityId: this.$route.query.cityId,
        },
      });

    },
  },
  async created(){
    await this.toursDestinationsData()
    console.log('toursDestinations')
  }
}
</script>

<style>
  .destination_wrapper{
    display: flex;
    justify-content: flex-start;
    align-items: center;
    flex-wrap: wrap;
    gap: 24px;
  }
  .destination_wrapper .destination{
    position: relative;
    width: calc(50% - 12px);
    height: 291px;
  }
  .destination_wrapper .destination p{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    color: var(--primary_light_color);
    font-size: var(--fs_s_800);
    font-weight: 800;
    z-index: 2;
    text-align: center;

  }
</style>
