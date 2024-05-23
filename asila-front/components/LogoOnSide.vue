<template>
  <div class="logo_on_side">
    <img src="@/assets/imgs/logo.svg" alt="" />
  </div>
</template>

<script>
  export default{

  }
</script>
<style>
  .logo_on_side{
    position: fixed;
    bottom: 30px;
    left: 30px;
    width: 150px;
    height: 170px;
  }
  .logo_on_side img{
    width: 100%;
    height: 100%;
    object-fit: fill;
  }
  .logo_on_side svg{
    width: 100%;
    height: 100%;
  }
  @media(max-width:1350px){
    .logo_on_side{
      width: 100px;
      height: 120px;
    }
  }
  @media(max-width:1000px){
    .logo_on_side{
      width: 60px;
      height: 80px;
    }
  }
  @media(max-width:800px){
    .logo_on_side{
      display: none;
    }
  }
</style>
