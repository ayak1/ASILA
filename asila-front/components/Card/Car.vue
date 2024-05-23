<template>
  <div class="car_card ">
    <div class="side1">
      <img :src="car_image" :alt="car_title">
    </div>
    <div class="side2">
      <p class="secondTitle" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{car_title}}</p>
      <p class="text" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{car_description}}</p>
      <div class="contact">
        <CardContact />
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props:{
    car_title:{
      type:String,
      required:true
    },
    car_image:{
      type:String,
      required:true
    },
    car_description:{
      type:String,
      required:true
    },
  },
  computed:{
    isRTL() {
    return this.$i18n.locale === 'ar';
  },
  }
}
</script>


<style>
.car_card{
  display: flex;
  justify-content: flex-start;
  gap: 0;
}
  .car_card .side1,.side2{
    width: 50%;
    height: 582px;
  }

  .car_card .side1 img{
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .car_card .side1 img[alt]{
    font-size: var(--fs_xs_800);
    font-weight: 800;
    text-align: center;
    color: var(--main_color);
  }
  .car_card .side2{
    position: relative;
    background: var(--background_color);
    display: flex;
    justify-content: flex-start;
    align-items: center;
    flex-direction: column;
  }

  .car_card .side2 .text{
    padding:0 24px;
    color: var(--primary_dark_color);
    font-size: var(--fs_xs_500);
    font-weight: 500;
    line-height: 180%;
    letter-spacing: 0.552px;
    margin-top: 24px;
  }

  @media(max-width: 1300px){
    .car_card{
      flex-direction: column;
    }
    .car_card .side1, .car_card .side1 img{
      border-radius: 0;
      border-top-right-radius: 10px;
      border-top-left-radius: 10px;
    }
    .car_card .side2{
      border-radius: 0;
      border-bottom-right-radius: 10px;
      border-bottom-left-radius: 10px;
    }
    .car_card .side1{
      width: 100%;
      height: 232px;
    }
    .car_card .side1,.side2{
      width: 100%;
      height: 332px;
    }
  }
</style>
