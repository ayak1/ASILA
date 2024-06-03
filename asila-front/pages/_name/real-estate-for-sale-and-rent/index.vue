<template>
  <div class="main_container">
    <h1 class="title">{{$t('apartments_for_sell_and_rent')}}</h1>
    <div class="buttons">
      <button :class="{ 'active': isForRentActive } " class="border-ra-10" @click="toggleButtons('forRent')">for rent</button>
      <button :class="{ 'active': isForSaleActive}" class="border-ra-10" @click="toggleButtons('forSale')">for sale</button>
    </div>
    <div v-if="apartments.length!=0">
      <div class="apartment_cards" v-if="apartments">
        <div  class="card" v-for="apartment in apartments" :key="apartment.id">
          <CardApartment :apartment="apartment">
            <div
            class="price"
            :class="[
              {'margin_right': $isRTL(),
              'margin_left':!$isRTL()
            },
            { 'rtl': $isRTL(), 'ltr': !$isRTL() }
              ]"
              v-if="isForRentActive" >
              <p class="number">
                {{ apartment.rent_per_month }}
              </p>
              <p class="text">
                {{ $t('turkish_lira') }} / {{ $t('month') }}
              </p>
            </div>
            <div class="price"
                :class="[
                  {'margin_right': $isRTL(),
                  'margin_left':!$isRTL()
                  },
                  { 'rtl': $isRTL(), 'ltr': !$isRTL() }]"
                          v-if="isForSaleActive">
              <p class="number">{{ apartment.sell_price }}</p>
              <p class="text">
                {{ $t('turkish_lira') }}
              </p>
            </div>
          </CardApartment>
        </div>
      </div>
    </div>
    <div v-else class="length0">
      <p>
        apartments list not available now, but you can contact us and we will book for you in any hotel you want jsut contact us
      </p>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState, mapMutations, mapGetters } from 'vuex';

export default {
  data(){
    return{
      forRent: null,
      isForRentActive: true,
      isForSaleActive: false
    }
  },
  computed:{
    ...mapState('apartments',['apartments']),
  },
  watch: {
  '$i18n.locale': function(newVal, oldVal) {
    this.apartmentsData()
  }
  },
  methods:{
    ...mapMutations('apartments', ['setApartments']),
    ...mapActions('apartments', ['fetch_apartments']),
    async apartmentsData(){
      const apartmentType = this.$forRent
      await this.fetch_apartments({forRent:apartmentType})
    },
    toggleButtons(type) {
      if (type === 'forRent') {
        this.isForRentActive = true;
        this.isForSaleActive = false;
        // Call your method to fetch data for rent
        this.getForRent();
      } else if (type === 'forSale') {
        this.isForRentActive = false;
        this.isForSaleActive = true;
        // Call your method to fetch data for sale
        this.getForSale();
      }
    },
    getForRent(){
      this.$forRent=true
      this.apartmentsData()
    },
    getForSale(){
      this.$forRent=false
      this.apartmentsData()

    },
    },
  async created(){
    this.$forRent = true
    await this.apartmentsData()
  }
}
</script>

<style>
.price{
  display: flex;
  align-items: center;
  margin-top: 20px;
  gap: 8px;
}
.margin_right{
  margin-right: 100px;
}
.margin_left{
  margin-left: 100px;
}
.price .number{
  font-size: var(--fs_s_800);
  font-weight: 800;
}
.apartment_cards{
  width: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
}
.apartment_cards .card{
  width: calc(50% - 13px);
}
.buttons{
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 24px;
}
.buttons button{
  width: 174px;
  height: 40px;
  font-size: var(--fs_xs_700);
  color: var(--color-2);
  font-weight: 700;
  border: none;
  margin-bottom: 88px;
}
.buttons .active{
  background: var(--primary_light_color);
  box-shadow: 0px 4px 10px 0px rgba(0, 0, 0, 0.25);
}
.buttons .deActive{
  background: var(--background_color);
}
.card .text{
  font-size: var(--fs_xxxs_500);
  font-weight: 500;
}
@media (max-width:1000px){
  .margin_right{
    margin-right: 50px;
  }
  .margin_left{
    margin-left: 50px;
  }
  .apartment_cards .card .hasServices{
    flex-wrap: wrap;
  }
}
@media (max-width:700px){
  .margin_right{
    margin-right: 15px;
  }
  .margin_left{
    margin-left: 15px;
  }
}
@media (max-width:550px){
  .apartment_cards .card{
    width: 100%;
  }
  .margin_right{
    margin-right: 5%;
  }
  .margin_left{
    margin-left: 5%;
  }
  .apartment_cards .card .hasServices{
    gap: 24px;
  }
  .apartment_cards .card .hasServices svg{
    width: 20px;
    height: 20px;
  }
}
</style>
