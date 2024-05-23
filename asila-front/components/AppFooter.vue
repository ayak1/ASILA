<template>

  <footer class="footer">
    <div class="main_container">
      <p class="footer_contact">{{ $t('footer_contact') }}</p>
      <p class="footer_description" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{ $t('footer_description') }}</p>
    </div>
    <div class="container_footer">
      <div class="background drop-shadow"></div>
      <div class="main_container content_container ">
        <div class="top">
          <div class="social"></div>
          <div class="logo"></div>
        </div>
        <div class="bottom">
          loop on links
        </div>
      </div>
    </div>
  </footer>
</template>
<script>
export default {

}
</script>
<style>
.footer{
  margin-top: 80px;
  width: 100%;
}

.footer_contact{
  text-align: center;
  color: var(--color-2);
  font-weight: 900;
  font-size: var(--fs_s_900);
}
.footer_description{
  margin-top: 44px;
  font-size: var(--fs_xxs_500);
  font-weight: 500;
  letter-spacing: 0.8px;
  line-height: 180%;
}
.container_footer{
  margin-top: 200px;
  width: 100%;
  height: 383px;
  background: var(--color-2);
  position: relative;
}
.background{
  position: absolute;
  background-color: var(--color-1);
  top: 0;
  left: 50%;
  transform: translate(-50%,-84px);
  width: 90%;
  height: 400px;
  border-radius: 40px;
  z-index: 1;
}
.content_container{
  position: relative;
  top: -10px;
  z-index: 3;
}
@media(max-width: 767px){
  .background{
    width: 95%;
  }
}
</style>
