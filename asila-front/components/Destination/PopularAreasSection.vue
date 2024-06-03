<template>
  <div v-if="popularAreas.length !== 0" class="popularAreasSection">
    <p class="title">{{ $t('popular_tourist_area') }}</p>
    <div class="areaWrapper">
      <div v-for="popularArea in popularAreas" :key="popularArea.id">
        <DestinationPopularArea :popularArea="popularArea" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapGetters, mapMutations } from 'vuex';

export default {
  watch: {
    '$route.query.cityId': function(newVal, oldVal) {
    this.fetchPopAreas()
    },
    '$i18n.locale': function(newVal, oldVal) {
      this.fetchPopAreas()
    }
  },
  computed: {
    ...mapState('areas', ['popularAreas']),
  },

  methods: {
    ...mapGetters('areas', ['getPopularAreas']),
    ...mapMutations('areas', ['setPopularAreas']),
    ...mapActions('areas', ['fetchPopularAreas']),

    fetchPopAreas(){
      const cityId = this.$route.query.cityId
      this.fetchPopularAreas({cityId:cityId})
      console.log("fetched")
    }
  },
  async created() {
    await this.fetchPopAreas();
  },
}
</script>

<style>
.popularAreasSection{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.popularAreasSection .areaWrapper{
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 24px;
}

</style>
