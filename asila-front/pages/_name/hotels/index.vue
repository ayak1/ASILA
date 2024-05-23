<template>
  <div class="main_container">
    <h1 class="title">{{$t('hotels_title')}}</h1>
   <div v-if="hotels.length!=0">
    <div  class="RHcards ">
      <CardRestHotel v-for="(item,index) in hotels" :key="index" :card="item" :isHotel="true" />
      <button v-if="showLoadMoreButton" @click="loadMorePackages" class="load-more-button">
        {{$t('load_more')}}
      </button>
    </div>
   </div>
    <div v-else class="length0">
      <p>
        hotels list not available now, but you can contact us and we will book for you in any hotel you want jsut contact us
      </p>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations,mapGetters } from 'vuex';

export default {
  computed:{
    ...mapState('hotels',['hotels']),
    showLoadMoreButton() {
      // Show the load more button if there are more packages to fetch
      return this.hotels.length % 10 === 0 && this.hotels.length > 0;
    }
  },
  watch: {
  '$i18n.locale': function(newVal, oldVal) {
    this.hotelsData()
  }
  },
  methods:{
    ...mapMutations('hotels', ['setHotels']),
    ...mapActions('hotels', ['fetch_hotels_by_city']),
    async hotelsData(){
      const cityId = this.$route.query.cityId
      console.log("city id", cityId)
      await this.fetch_hotels_by_city({cityId})
    },
    async loadMoreHotels() {
      const cityId = this.$route.query.cityId;
      // Calculate the page number based on the current number of hotels fetched
      const page = Math.floor(this.hotels.length / 10) + 1;
      await this.fetch_hotels_by_city({ cityId });
    },
  },
  async created(){
    await this.hotelsData()
    console.log('hotels')
  }
}
</script>

<style>
.RHcards{
  width: 100%;
  height: fit-content;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  gap: 24px;
}

</style>
