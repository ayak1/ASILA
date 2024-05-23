<template>
  <div class="dest_area_wrapper border-ra-10">
    <div class="img_wrapper border-ra-10">
      <div  class="top-of-image topImage border-ra-10"></div>
      <img class="coverImage top-of-image border-ra-10" :src="img" :alt="title">
    </div>
    <div class="card_title border-ra-10">
      <p >{{title}}</p>
    </div>
  </div>
</template>

<script>
export default {
  props:{
    img:String,
    title:String,
  }
}
</script>


<style>
.dest_area_wrapper{
  width: 100%;
  display: flex;
  align-items: center;
  flex-direction: column;
}
.dest_area_wrapper .img_wrapper{
  position: relative;
  height: 300px;
}
.dest_area_wrapper img{
  width: 100%;
  height: 100%;
}
.dest_area_wrapper .card_title{
  position: relative;
  width: calc(100% - 22px);
  padding: 16px 0;
  background: var(--background_color);
  margin-top: -30px;
  z-index: 1;
}
.dest_area_wrapper .card_title p{
  text-align: center;
  color: var(--color-4);
  font-size: var(--fs_xxs_700);
  font-weight: 700;
}
</style>
