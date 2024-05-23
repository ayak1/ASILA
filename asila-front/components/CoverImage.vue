<template>
  <div class="img_wrapper ">
    <img class="border-ra-10 coverImage" :src="cover_image" alt="">
  </div>
</template>

<script>
export default {
  props:{
    cover_image:{
      type:String,
      required: true
    }
  }
}
</script>

<style>
.img_wrapper{
  width: 100%;
  height: 554px;
}
.img_wrapper .coverImage{
  width: 100%;
  height: 100%;
}
@media(max-width: 1500px) {
  .img_wrapper{
    width: 100%;
    height: 450px;
    object-fit: cover;
  }
}
@media(max-width: 1000px) {
  .img_wrapper{
    width: 100%;
    height: 310px;
    object-fit: cover;
  }
}
@media(max-width: 800px) {
  .img_wrapper{
    width: 100%;
    height: 210px;
    object-fit: cover;
  }
}
</style>
