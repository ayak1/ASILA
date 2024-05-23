<!-- packages/index.vue -->
<template>
  <div class="main_container ">
    <h1 class="title">{{$t('packages_title')}}</h1>
    <div v-if="packages.length!=0">
    <div class="cards ">
      <CardBig v-for="(item, index) in packages" :key="index" :serviceCard="item" />
    </div>
    <button v-if="showLoadMoreButton" @click="loadMorePackages" class="load-more-button">
      {{$t('load_more')}}
    </button>
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
    ...mapState('packages',['packages']),
    showLoadMoreButton() {
      // Show the load more button if there are more packages to fetch
      return this.packages.length % 10 === 0 && this.packages.length > 0;
    }
  },
  watch: {
  '$i18n.locale': function(newVal, oldVal) {
    this.packagesData()
  }
  },
  methods:{
    ...mapMutations('packages', ['setPackages']),
    ...mapActions('packages', ['fetch_packages_by_city']),
    async packagesData(){
      const cityId = this.$route.query.cityId
      await this.fetch_packages_by_city({cityId})
    },
    async loadMorePackages() {
      const cityId = this.$route.query.cityId;
      // Calculate the page number based on the current number of packages fetched
      const page = Math.floor(this.packages.length / 10) + 1;
      await this.fetch_packages_by_city({ cityId, page });
    },
  },
  async created(){
    await this.packagesData()
  }
}

</script>

<style>
.cards{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  gap: 24px;
}
.load-more-button {
  margin-top: 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}

.load-more-button:hover {
  background-color: #0056b3;
}
</style>
