<!-- packages/_id.vue -->
<template>
  <div class="_id_page_wrapper main_container"
       :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">
   <div class="container">
    <h1 class="title">{{selectedPackage.title}}</h1>
    <!-- <div class="img_wrapper">
      <img class="coverImage" :src="selectedPackage.cover_image" alt="">
    </div> -->
    <CoverImage :cover_image="selectedPackage.cover_image" />
    <!-- <p class="main_p" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">
      <span> {{$t('book_with_us')}}</span>
      {{$t('package_title_sec_duration') }}
      {{selectedPackage.duration}} {{$t('days')}} {{$t('and')}} {{Number(selectedPackage.duration) - 1}} {{$t('nights')}}
      {{$t('in')}} {{ selectedCity.name }}
    </p> -->
    <p  class="can_choose_hotel" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{selectedPackage.description}}</p>
    <div class="can_choose_hotel" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }" >
      <div class="img">
        <svg v-if="selectedPackage.can_choose_hotel ==1" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8.4911 24.4554L0.366101 16.3304C-0.122034 15.8422 -0.122034 15.0508 0.366101 14.5626L2.13383 12.7948C2.62196 12.3066 3.41346 12.3066 3.9016 12.7948L9.37499 18.2682L21.0984 6.54481C21.5865 6.05668 22.378 6.05668 22.8662 6.54481L24.6339 8.31259C25.122 8.80072 25.122 9.59218 24.6339 10.0804L10.2589 24.4554C9.77069 24.9435 8.97924 24.9435 8.4911 24.4554Z" fill="#C03131"/>
        </svg>
        <svg v-else width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12.0007 10.5865L16.9504 5.63672L18.3646 7.05093L13.4149 12.0007L18.3646 16.9504L16.9504 18.3646L12.0007 13.4149L7.05093 18.3646L5.63672 16.9504L10.5865 12.0007L5.63672 7.05093L7.05093 5.63672L12.0007 10.5865Z" fill="black"/>
        </svg>
      </div>
        <p>{{$t('can_choose_hotel')}}</p>
      </div>
      <p class="package_detail">{{$t('package_details')}}</p>
     <div class="package_details">
      <p class="price_text" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{$t('price_start_from')}}</p>
      <p class="price" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{selectedPackage.price}}</p>
      <div class="days_activities">
       <PackageDayDetails v-for="(day_activities, index) in selectedPackage.days_activities" :key="index" :day_activities="day_activities"/>
      </div>
     </div>
   </div>
   <!-- <client-only>
    <NuxtHead>
      <title>{{ selectedPackage.meta.title }}</title>
      <meta name="description" :content="selectedPackage.meta.description" />
    </NuxtHead>
  </client-only> -->
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations, mapState } from 'vuex';

export default {
  // data() {
  //   return {
  //     localSelectedPackage: null,
  //   };
  // },
  computed: {
    ...mapState('packages',['selectedPackage']),
  },
  watch: {
  '$i18n.locale': function(newVal, oldVal) {
    this.fetchPackageData()
  }
  },
  methods: {
    ...mapGetters('packages', ['getSelectedPackage']),
    ...mapMutations('packages', ['setSelectedPackages']),
    ...mapMutations('packages', ['setSelectedPackages']),
    ...mapActions('packages', ['fetch_package_by_id']),
     fetchPackageData() {
      const packageId = this.$route.params.id;
      this.fetch_package_by_id({ packageId });
    },
  },
  async created() {
    if(this.$store.state.packages.selectedPackage.length == 0){
      await this.fetchPackageData()
    }
  },
};
</script>

<style>
._id_page_wrapper .main_p{
  color: var(-- );
  font-size: var(--fs_s_500);
  font-weight: 500;
  line-height: 180%;
  letter-spacing: -0.92px;
  margin-top: 60px;
}
._id_page_wrapper .main_p span{
  color: var(--main_color);
}
._id_page_wrapper .can_choose_hotel{
  margin: 24px 100px;
  display: flex;
  align-items: center;
}
._id_page_wrapper .can_choose_hotel .img{
  width: 25px;
  height: 18.643px;
}
._id_page_wrapper .can_choose_hotel .img svg{
  width: 100%;
  height: 100%;
}
._id_page_wrapper .can_choose_hotel .img svg path{
  fill: var(--color-2);
}
._id_page_wrapper .can_choose_hotel p{
  color: var(-- );
  font-size: var(--fs_xs_800);
  font-weight: 500;
  letter-spacing: -0.69px;
}
._id_page_wrapper .package_detail{
  color: var(-- );
  font-size: var(--fs_s_700);
  font-weight: 700;
  letter-spacing: -0.92px;
}
._id_page_wrapper .package_details{
  margin-left: 32px;
  margin-right: 32px;
}
._id_page_wrapper .package_details .price_text{
  color: var(-- );
  font-size: var(--fs_s_500);
  font-weight: 500;
  letter-spacing: -0.92px;
  margin-top: 52px;
}
._id_page_wrapper .package_details .price{
  color: var(--color-2);
  font-size: var(--fs_xl_800);
  font-weight: 800;
  letter-spacing: -1.61px;
  margin-top: 32px;
}
</style>
