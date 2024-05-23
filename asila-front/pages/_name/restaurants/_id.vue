<!-- packages/_id.vue -->
<template>
  <div class=" _id_page_wrapper main_container" >
    <div class="container">
      <div class="content" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">
        <h1 class="title">{{selectedRestaurant.name}}</h1>
        <p class="restaurant_description">{{selectedRestaurant.full_description}}</p>
      </div>
      <div class="restaurant_images" v-if="selectedRestaurant && selectedRestaurant.images">
        <div class="sec1">
          <div class="image border-ra-10 item-0" v-if="selectedRestaurant.images[0]" >
            <img class="border-ra-10" :src="selectedRestaurant.images[0].path" alt="">
          </div>
          <div class="image border-ra-10 item-1" v-if="selectedRestaurant.images[1]" >
            <img class="border-ra-10" :src="selectedRestaurant.images[1].path" alt="">
          </div>
        </div>
        <div class="sec2">
          <div class="image border-ra-10" v-if="selectedRestaurant.images[2]" >
            <img class="border-ra-10" :src="selectedRestaurant.images[2].path" alt="">
          </div>
          <div class="image border-ra-10" v-if="selectedRestaurant.images[3]" >
            <img class="border-ra-10" :src="selectedRestaurant.images[3].path" alt="">
          </div>
          <div class="image border-ra-10" v-if="selectedRestaurant.images[4]" >
            <img class="border-ra-10" :src="selectedRestaurant.images[4].path" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations, mapState } from 'vuex';

export default {
  computed: {
    ...mapState('restaurants',['selectedRestaurant']),
  },
  watch: {
  '$i18n.locale': function(newVal, oldVal) {
    this.fetchRestaurantData()
  }
  },
  methods: {
    ...mapGetters('restaurants', ['getSelectedRestaurant']),
    ...mapMutations('restaurants', ['setSelectedRestaurant']),
    ...mapActions('restaurants', ['fetch_restaurant_by_id']),
     fetchRestaurantData() {
      const restaurantId = this.$route.params.id;
      this.fetch_restaurant_by_id({ restaurantId });
    },
  },
  async created() {
    if(this.$store.state.restaurants.selectedRestaurant.length == 0){
      await this.fetchRestaurantData()
    }
  },


};
</script>

<style>
.restaurant_description{
  font-weight: 500;
  font-size: var(--fs_xs_500);
  letter-spacing: -2.3%;
  line-height: 180%;
  margin-bottom: 40px;
}
.restaurant_images{
  display: flex;
  justify-content: flex-start;
  align-items: center;
  width: 100%;
  flex-direction: column;
  gap: 24px;
}
.restaurant_images .sec1{
  display: flex;
	flex-wrap: wrap;
	align-items: center;
  width: 100%;
  gap: 24px;
}
.restaurant_images .sec2{
  display: flex;
	flex-wrap: wrap;
	align-items: center;
  justify-content: flex-end;
  width: 100%;
  gap: 24px;
}
.restaurant_images .sec2 .image{
  width: calc(((100% / 3) * 1) - (48px / 3));
  height: 340px;
}
.restaurant_images .sec2 .image img{
  width: 100%;
  height: 100%;
}
.restaurant_images .sec1 .item-0 {
	flex-grow: 1.5;
	flex-shrink: 2;
	align-self: flex-start;
  width: calc(((100% / 2) ) - 12px);
  height: 340px;
}
.restaurant_images .sec1 .item-0 img{
  width: 100%;
  height: 100%;
}
.restaurant_images .sec1 .item-1 {
	flex-grow: 1;
	flex-shrink: 2;
  width: calc(((100% / 2) * 0.7) - 12px);
  height: 340px;
}
.restaurant_images .sec1 .item-1 img{
  width: 100%;
  height: 100%;
}
.activity_imgs{
  display: flex;
  gap: 24px;
  flex-wrap: wrap;
  width: 100%;
}
</style>
