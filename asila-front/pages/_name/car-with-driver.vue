<template>
  <div class="main_container">
    <h1 class="title">{{$t('car_services_title')}}</h1>

   <div v-if="car_services.length!=0">
    <div class="car_services" >
      <div class="car_service"  v-for="car_service in car_services" :key="car_service.id">
        <CardCar  :car_title="car_service.title" :car_description="car_service.description" :car_image="car_service.image" />
      </div>
     </div>
   </div>
    <div v-else class="length0">
      <p>
        car services list not available now, but you can contact us and we will book for you in any hotel you want jsut contact us
      </p>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState, mapMutations } from 'vuex';

export default {
  computed:{
    ...mapState('car_services',['car_services']),
  },
  methods:{
    ...mapMutations('car_services', ['SET_CAR_SERVICES']),
    ...mapActions('car_services', ['fetch_car_services']),
    async carServices(){
      await this.fetch_car_services()
    }
  },
  async created(){
    await this.carServices()
  }
}
</script>

<style>
.car_services{
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 24px;
  flex-direction: column;
  margin-bottom: 80px;
}
.car_services .car_service:nth-child(even) .car_card{
  flex-direction: row-reverse;
}
.car_services .car_service:nth-child(odd) .contact{
  position: absolute;
  left: 4px;
  bottom: 4px;
}
.car_services .car_service:nth-child(odd) .car_card .side1 img{
  border: 0;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
}
.car_services .car_service:nth-child(odd)  .car_card .side2{
  border: 0;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}
.car_services .car_service:nth-child(even)  .car_card .side2{
  border: 0;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
}
.car_services .car_service:nth-child(even)  .car_card .side1 img{
  border: 0;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}
.car_services .car_service:nth-child(odd) .contact .contactCard{
  border: 3px solid var(--primary_light_color);
  border-top-right-radius: 20px;
}
.car_services .car_service:nth-child(even) .contact{
  position: absolute;
  right: 4px;
  bottom: 4px;
}
.car_services .car_service:nth-child(even) .contact .contactCard{
  border: 3px solid var(--primary_light_color);
  border-top-left-radius: 20px;
}

</style>
