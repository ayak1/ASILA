<template>

  <footer class="footer">
    <div class="main_container">
      <p class="footer_contact">{{ $t('footer_contact') }}</p>
      <p class="footer_description" :class="{ 'rtl': $isRTL(), 'ltr': !$isRTL() }">{{ $t('footer_description') }}</p>
    </div>
    <div class="container_footer ">
      <div class="background drop-shadow"></div>
      <div class="main_container content_container ">
        <div class="top">
          <div class="left">
            <div class="social">
              <div class="icon_container border-ra-10 iconBgColorGradient">
                <img class="footer_social_icon" src="@/assets/imgs/x.svg"/>
              </div>
              <div class="icon_container border-ra-10 iconBgColorGradient">
                <img class="footer_social_icon" src="@/assets/imgs/insta.svg"/>
              </div>
              <div class="icon_container border-ra-10 iconBgColorGradient">
                <img class="footer_social_icon" src="@/assets/imgs/whatsapp.svg"/>
              </div>
              <div class="icon_container border-ra-10 iconBgColorGradient">
                <img class="footer_social_icon" src="@/assets/imgs/facebook.svg"/>
              </div>
            </div>
            <div class="subscribe ">
                <button class="drop-shadow iconBgColorGradient">{{$t('subscribe')}}</button>
                <input type="email" required :placeholder="$t('email_address')">
            </div>
          </div>
            <NuxtLink class="logo" :to="localePath('/')">
              <img src="@/assets/imgs/logo.svg" alt="">
              <h1 class="textColorGradient">{{$t('footer_company_title')}}</h1>
            </NuxtLink>
        </div>
        <div class="bottom">
          <!-- loop on links -->
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
.footer .top{
  display: flex;
  justify-content: space-between;
  align-items: center;

}
.background{
  position: absolute;
  background-color: var(--color-1);
  top: 0;
  left: 50%;
  transform: translate(-50%,-84px);
  width: 90%;
  height: 450px;
  border-radius: 40px;
  z-index: 1;
}
.content_container{
  position: relative;
  top: -10px;
  z-index: 3;
}
.footer .left{
  display: flex;
  flex-direction: column;
  gap: 60px;
}
.footer .logo img{
  width: 66px;
  height: 88px;
  align-self: flex-end;
}
footer .logo h1{
  width: 100%;
  font-size: var(--fs_xs_500);
  font-weight: 500;
  margin-top: 4px;
}
.footer .logo{
  display: flex;
  flex-direction: column;
}
.social{
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 42px;
}
.social .icon_container{
  padding: 10px 10px 5px 10px;
}
.subscribe{
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 0;
  background: transparent;
}
.subscribe button{
  padding: 15px 24px;
  border: transparent;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  font-size: var(--fs_xxs_700);
  font-weight: 700;
  color: var(--color-1);
}
.subscribe input{
  padding: 18px;
  width: 250px;
  border: 0;
  background: var(--body_color);
}
@media(max-width: 800px){
  .background{
    width: 95%;
  }
  .top{
    flex-direction: column-reverse;
    gap: 20px;
    align-items: center;
  }
  .top .logo{
    justify-content: center;
    align-items: center;
  }
  .top .logo img{
    align-self: center;
  }

  .subscribe{
    justify-content: center;
    align-items: center;
  }
}
</style>
