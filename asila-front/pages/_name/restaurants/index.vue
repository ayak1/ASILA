<template>
  <div class="main_container">
    <h1 class="title">{{$t('restaurants_title')}}</h1>

    <div v-if="restaurants.length!=0">
      <div class="RHcards ">
        <CardRestHotel v-for="(item,index) in restaurants" :key="index" :card="item"  />
      </div>
     </div>
      <div v-else class="length0">
        <p>
          restaurants list not available now, but you can contact us and we will book for you in any hotel you want jsut contact us
        </p>
      </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations,mapGetters } from 'vuex';

export default {
  computed:{
    ...mapState('restaurants',['restaurants']),
  },
  watch: {
  '$i18n.locale': function(newVal, oldVal) {
    this.restaurantsData()
  }
  },
  methods:{
    ...mapMutations('restaurants', ['setRestaurants']),
    ...mapActions('restaurants', ['fetch_restaurants_by_city']),
    async restaurantsData(){
      const cityId = this.$route.query.cityId
      await this.fetch_restaurants_by_city({cityId})
    }
  },
  async created(){
    await this.restaurantsData()
  }
}
</script>

<style>
.RHcards{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  gap: 24px;
}
</style>
