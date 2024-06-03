<template>
  <div class="main_container" v-if="selectedToursDestination.length!=0" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">
    <h1 class="title" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{selectedToursDestination.destination.name}}</h1>
    <div class="cards_container" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }" v-if="selectedToursDestination.packages.length!=0">
      <p class="section_title textColorGradient">{{$t('packages')}}</p>
      <div class="wrapper_cards">
        <div class="card_wrapper border-ra-10" v-for="(pack,index) in selectedToursDestination.packages" :key="index"  @click="navigateToPackage(pack.id)">
          <CardByDestArea v-if="pack" :img="pack.cover_image" :title="pack.title"/>
        </div>
      </div>
    </div>
    <div class="cards_container" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }" v-if="selectedToursDestination.programs.length!=0">
      <p class="section_title textColorGradient">{{$t('programs')}}</p>
      <div class="wrapper_cards">
        <div class="card_wrapper border-ra-10" v-for="(program,index) in selectedToursDestination.programs" :key="index"  @click="navigateToProgram(program.id)">
          <CardByDestArea v-if="program" :img="program.cover_image" :title="program.title"/>
        </div>
      </div>
    </div>
    <div class="cards_container" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }" v-if="selectedToursDestination.activities.length!=0" >
      <p class="section_title textColorGradient">{{$t('activities')}}</p>
      <div class="wrapper_cards">
        <div class="card_wrapper border-ra-10" v-for="(activity,index) in selectedToursDestination.activities" :key="index" @click="navigateToActivity(activity.id)">
          <CardByDestArea v-if="activity" :img="activity.cover_image" :title="activity.name"/>
        </div>
      </div>
    </div>
</div>
</template>
<script>
import { mapGetters, mapActions, mapMutations, mapState } from 'vuex';

export default {
computed: {
  ...mapState('toursDestinations',['selectedToursDestination']),
},
watch: {
'$i18n.locale': function(newVal, oldVal) {
  this.fetchToursDestinations()
}
},
methods: {
  ...mapGetters('toursDestinations', ['getSelectedToursDestinations']),
  ...mapMutations('toursDestinations', ['setSelectedDestination']),
  ...mapActions('toursDestinations', ['fetch_destination_by_id']),
  async fetchToursDestinations() {
    const destinationId = this.$route.params.id;
    const cityId = this.$route.query.cityId;
    await this.fetch_destination_by_id({ cityId, destinationId });
  },
  navigateToPackage(packageId) {
    const cityName = this.$route.params.name;
    this.$router.push({ path: `/${this.$i18n.locale}/${cityName}/packages/${packageId}`, query: { cityId: this.$route.query.cityId } });
  },
  navigateToProgram(programId) {
    const cityName = this.$route.params.name;
    this.$router.push({ path: `/${this.$i18n.locale}/${cityName}/programs/${programId}`, query: { cityId: this.$route.query.cityId } });
},
  navigateToActivity(activityId) {
    const cityName = this.$route.params.name;
    this.$router.push({ path: `/${this.$i18n.locale}/${cityName}/activities/${activityId}`, query: { cityId: this.$route.query.cityId } });
  }
},
created() {
  if (this.selectedToursDestination.length == 0) {
    this.fetchToursDestinations();
  }
},
};
</script>
<style>
.cards_container{
width: 100%;
display: flex;
flex-wrap: wrap;
flex-direction: column;
gap: 80px;
margin-bottom: 100px;
}
.cards_container .wrapper_cards{
width: 100%;
display: flex;
flex-wrap: wrap;
gap: 24px;
}
.cards_container .card_wrapper{
background: transparent;
width: calc((100% / 3 ) - 48px / 3);
}
.section_title{
font-size: var(--fs_xs_700);
font-weight: 700;
}
</style>
