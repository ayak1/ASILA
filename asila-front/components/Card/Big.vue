<!-- components/BigCard.vue -->
<template>
  <div class="card_wrapper border-ra-10 " >
    <div class="imgWrapper">
      <img :src="serviceCard.cover_image" alt="" class="cover">
    </div>
    <div class="card_body">
      <CardContact/>
      <h2 class="secondTitle" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }" >{{serviceCard.title}}</h2>
     <div class="card_text" :class="{'descRight':$isRTL()  , 'descLeft':!$isRTL() }">
      <p class="serviceDescription" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{serviceCard.short_description}}</p>
      <ul v-if="!isProgram" class="detailTitlesList" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }" >
        <li :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL()  }" v-for="(dayActivity,index) in serviceCard.days_activities" :key="index">
          {{ dayActivity.content }}
        </li>
      </ul>
      <p class="sectionTitle" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL()  }" v-if="isProgram">
        {{$t('section_program_title')}}
      </p>
      <ul v-if="isProgram" class="detailTitlesList" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }" >
        <li :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL()  }" v-for="(activity,index) in serviceCard.program_activities" :key="index">
          {{ activity.name }}
        </li>
      </ul>
     </div>
    </div>
      <MoreDetailsButton @button-clicked="goToDetailsPage(serviceCard)"/>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex';

export default {
  props:{
    serviceCard:Object,
    isProgram:{
      type:Boolean,
      default:false,
    },
  },
  created(){
  },
   computed:{
    ...mapState('packages', ['selectedPackage']),
    ...mapState('programs', ['selectedProgram']),
  },
  methods:{
    ...mapMutations('packages', ['setSelectedPackage']),
    ...mapMutations('programs', ['setSelectedProgram']),
    ...mapActions('packages', ['fetchSelectedPackage']),
    ...mapActions('programs', ['fetchSelectedProgram']),
    async goToDetailsPage(itemSelected) {
      console.log("card big/goToDetailsPage",itemSelected)

      if(!this.isProgram){
        await this.fetchSelectedPackage(itemSelected)
      }else{
        await this.fetchSelectedProgram(itemSelected.id)
      }
      const path = this.$route.path;
      this.$router.push({
        path: `${path}/${this.serviceCard.id}`,
        query: {
          cityId: this.$route.query.cityId,
        },
      });
    }
  }
}
</script>

<style>
.descRight{
  margin-right: 79px;
}
.descLeft{
  margin-left: 79px;
}
.card_wrapper{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  background: var(--background_color);
  width: 100%;
}
.card_wrapper .imgWrapper{
  width: 100%;
  height: 513px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}
.card_wrapper .imgWrapper img{
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}
.card_wrapper .card_body{
  width: 100%;
  display: flex;
  position: relative;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.card_wrapper .card_body .contactCard{
  position: absolute;
  top: 4px;
  right: 4px;
  border: 3px solid var(--primary_light_color);
  border-top-right-radius: 20px;
}
.card_wrapper .card_body .card_text{
  display: flex;
  flex-direction: column;
  padding: 10px 20px;
  width: 85%;
}
.card_wrapper .card_body .card_text .serviceDescription{
  color: var(--primary_dark_color);
  font-size: var(--fs_xs_500);
  font-weight: 500;
  line-height: 180%;
  letter-spacing: -0.552px;
  margin-top: 40px;
}
.card_wrapper .card_body .card_text .detailTitlesList{
  list-style-type: disc;
  column-count: 3;
  margin: 20px;
}
.card_wrapper .card_body .card_text .detailTitlesList li{
  color: var(--primary_dark_color);
  font-size: var(--fs_xxs_500);
  font-weight: 500;
  letter-spacing: -0.506px;
  margin: 0 10px;
  padding-top: 7px;
  width: fit-content;
}
.sectionTitle{
  font-size: var(--fs_xs_700);
  font-weight: 700;
  margin: 10px 0;
}
@media(max-width: 1500px) {
  .descRight{
    margin-right: 59px;
  }
  .descLeft{
    margin-left: 59px;
  }
  .card_wrapper .card_body .card_text .detailTitlesList{
    column-count: 2;
  }
  .card_wrapper .imgWrapper{
    width: 100%;
    height: 413px;
  }
}
@media(max-width: 1200px) {
  .descRight{
    margin-right: 29px;
  }
  .descLeft{
    margin-left: 29px;
  }
  .card_wrapper .imgWrapper{
    width: 100%;
    height: 313px;
  }
}
@media(max-width: 1000px) {
  .descRight{
    margin-right: 19px;
  }
  .descLeft{
    margin-left: 19px;
  }
  .card_wrapper .card_body .card_text .detailTitlesList{
    column-count: 1;
  }
  .card_wrapper .imgWrapper{
    width: 100%;
    height: 213px;
  }
}
</style>
