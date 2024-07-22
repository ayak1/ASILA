<!-- packages/_id.vue -->
<template>
  <div class=" _id_page_wrapper main_container" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">
    <div class="container" v-if="selectedProgram">
      <h1 class="title">{{selectedProgram.title}}</h1>
      <CoverImage :cover_image="selectedProgram.cover_image" />
      <p class="full_description">
        {{selectedProgram.full_description}}
      </p>
      <div class="activity_imgs">
        <CardActivity :img="activity.image" is_50 :name="activity.name" v-for="(activity, index) in selectedProgram.program_activities" :key="index"/>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations, mapState } from 'vuex';

export default {
  computed: {
    ...mapState('programs',['selectedProgram']),
  },
  watch: {
  '$i18n.locale': function(newVal, oldVal) {
    this.fetchProgramData()
  }
  },
  methods: {
    ...mapGetters('programs', ['getSelectedProgram']),
    ...mapMutations('programs', ['setSelectedProgram']),
    ...mapActions('programs', ['fetch_program_by_id']),
     fetchProgramData() {
      const programId = this.$route.params.id;
      this.fetch_program_by_id({ programId });
    },
  },
  async created() {
    if(this.$store.state.programs.selectedProgram.length == 0){
      await this.fetchProgramData()
    }
  },


};
</script>

<style>
.full_description{
  font-weight: 500;
  font-size: var(--fs_xs_500);
  letter-spacing: -2.3%;
  line-height: 180%;
  margin: 80px 0;
}
.activity_imgs{
  display: flex;
  gap: 24px;
  flex-wrap: wrap;
  width: 100%;
}
</style>
