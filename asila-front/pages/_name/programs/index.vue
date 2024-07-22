<template>
  <div class="main_container">
    <h1 class="title">{{$t('programs_title')}}</h1>
    <div v-if="programs.length!=0">

    <div class="cards">
      <CardBig v-for="(item,index) in programs" :key="index" :serviceCard="item" isProgram />
    </div>
  </div>
  <div v-else class="length0">
    <p>
      programs list not available now, but you can contact us and we will book for you in any hotel you want jsut contact us
    </p>
  </div>
  </div>
</template>
<script>
import { mapActions, mapState, mapMutations } from 'vuex';

export default {
  computed:{
    ...mapState('programs',['programs']),
  },
  watch: {
  '$i18n.locale': function(newVal, oldVal) {
    this.programsData()
  }
  },
  methods:{
    ...mapMutations('programs', ['setPrograms']),
    ...mapActions('programs', ['fetchProgramsByCity']),
    async programsData(){
      const cityId = this.$route.query.cityId
      await this.fetchProgramsByCity({cityId})
    }
  },
  async created(){
    console.log("created/programs/index")
    await this.programsData()
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
</style>
