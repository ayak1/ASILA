<template>
  <div class="border-ra-10 service_card pointer" @click="routeToService(page_route)" >
    <!-- <NuxtLink :to=""> -->
     <div class="top-of-image topImage border-ra-10 "></div>
    <img class="border-ra-10 " :src="service_image" alt="">
    <p>{{service_name}}</p>
    <!-- </NuxtLink> -->
  </div>
</template>

<script>
export default {
  props:{
    service_name:{
      type:String,
      required:true
    },
    service_image:{
      type:String,
    },
    page_route:{
      type:String
    }
  },
  computed:{

  },
  methods:{
    routeToService(toRoute){
      const path = this.$route.path;
      // Use $router.push() to navigate to the first page
      this.$router.push({
        path: `${path}/${toRoute}`,
        query: {
          cityId: this.$route.query.cityId,
        },
      });
    }
  },
  created(){

  }
}
</script>

<style>
  .service_card{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .service_card img{
    width: 100%;
    height: 100%;
    position: relative;
  }
  .service_card p{
    position: absolute;
    top:50%;
    left: 50%;
    transform: translate(-50%,-50%);
    font-size: var(--fs_xs_800);
    font-weight: 800;
    text-align: center;
    width: 100%;
    color:var(--primary_light_color);
    padding: 10px;
    z-index: 2;
  }
@media (max-width:1000px) {
  .service_card p{
    padding: 7px;
  }
}

</style>
