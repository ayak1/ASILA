<template>
    <div v-if="extra_service" class="main_container">
      <h1 class="title">{{extra_service.title}}</h1>
      <p class="main_description" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">
        {{ extra_service.main_description }}
      </p>
      <CoverImage v-if="extra_service.cover_image" :cover_image="extra_service.cover_image" />
      <div v-for="(section,index) in extra_service.sections" :key="index">
        <ExtraServiceSectionsTitle v-if="section.title" :title="section.title"/>
        <ExtraServiceSectionsDescription v-if="section.description" :description="section.description"/>
      </div>
      <div class="images">
        <div v-for="(image,index) in extra_service.images" :key="index" class="w-50 border-ra-10">
          <img :src="image.path" :alt="extra_service.title" class="border-ra-10">
        </div>
      </div>
    </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex';

export default {
  computed:{
    ...mapState('extraServices',['extra_service']),
  },
  methods:{
    ...mapMutations('extraServices', ['setExtraService']),
    ...mapActions('extraServices', ['fetch_extra_service']),
    async extraServiceData(){
      const slug = this.$route.params.extraService
      await this.fetch_extra_service({slug})
    }
  },
  async created(){
    await this.extraServiceData()
  }
}
</script>

<style>
.main_description{
  font-size: var(--fs_xs_500);
  font-weight: 500;
  margin-bottom: 112px;
}
.images{
  display: flex;
  flex-wrap: wrap;
  gap: 24px;
}
.w-50{
  width: calc(50% - 12px);
}
.images div img{
  width: 100%;
  height: 408px;
  object-fit: cover;
}
@media(max-width: 1500px) {
  .images div img{
    width: 100%;
    height: 308px;
    object-fit: cover;
  }
  .main_description{
    margin-bottom: 96px;
  }
}
@media(max-width: 1000px) {
  .images div img{
    width: 100%;
    height: 208px;
    object-fit: cover;
  }
  .main_description{
    margin-bottom: 76px;
  }
}
@media(max-width: 600px) {
  .images{
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    gap: 24px;
  }
  .w-50{
    width: 100%;
  }
  .main_description{
    margin-bottom: 68px;
  }
}
</style>
