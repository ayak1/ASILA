<!-- NavBar -->
<template>
  <div class="navBar">
    <nav class="main_container">
      <NavChangeLocale v-if="!isSmallSize"/>
      <div class="mb_size toggle-button" @click="toggleNav" :class="{ 'active': isNavOpen }">
        <span class="mb_size line1"></span>
        <span class="mb_size line2"></span>
        <span class="mb_size line3"></span>
      </div>
      <NavCallButton v-if="!isSmallSize"/>
      <ul class="desktop_size " :class="[{ 'sideBar': isNavOpen },{ 'rtl': $isRTL(), 'ltr': !$isRTL() }]">
        <li @click="toggleNav">
          <NuxtLink class="nav_item" :to="localePath('/')">
            {{ $t('nav_home') }}
          </NuxtLink>
        </li>
        <li>
          <NavDropdownList :title="$t('nav_destination')" :list="cities" />
        </li>
        <!-- <li  @click="toggleNav">
          <NuxtLink class="nav_item" :to="localePath('/blog')">
            {{$t('nav_blog')}}
          </NuxtLink>
        </li> -->
        <li  @click="toggleNav">
          <NuxtLink class="nav_item" :to="localePath('/about')">
            {{$t('nav_about')}}
          </NuxtLink>
        </li>
        <li v-if="isSmallSize">
          <NavChangeLocale />
        </li>
        <li v-if="isSmallSize">
          <NavCallButton/>
        </li>
      </ul>
      <NuxtLink class="logo" :to="localePath('/')">
        <img src="@/assets/imgs/logo.svg" alt="">
      </NuxtLink>

    </nav>
  </div>
</template>

<script>
import { mapMutations, mapActions, mapState } from 'vuex';

export default {
  data() {
    return {
      isNavOpen: false, // Track if the sidebar is open or closed
      isWindowLarge: false, // Track if the window size is larger than 1150 pixels
      isSmallSize: false // Track if the window size is smaller than or equal to 650 pixels
    };
  },
  computed: {
    isRTL() {
      return this.$i18n.locale === 'ar';
    },
    ...mapState('cities', ['cities'])
  },
  watch: {
    '$i18n.locale': function(newVal, oldVal) {
      this.fetchCities();
    },
    isWindowLarge(newValue) {
      if (newValue) {
        this.isNavOpen = false; // Close the navigation bar if the window size becomes larger than 1150 pixels
      }
    }
  },
  methods: {
    ...mapMutations('cities', ['setCities']),
    ...mapActions('cities', ['fetchCities']),
    toggleNav() {
      if (!this.isWindowLarge) {
        // Only toggle the navigation bar if the window size is not larger than 1150 pixels
        this.isNavOpen = !this.isNavOpen;
        if (this.isSmallSize) {
          // Do something specific for small screens
        }
      }
    },
    checkWindowSize() {
      if (process.client) {
        this.isWindowLarge = window.innerWidth > 1150;
        this.isSmallSize = window.innerWidth <= 650; // Update isSmallSize based on the window size
      }
    }
  },
  async created() {
    await this.fetchCities();
    this.checkWindowSize(); // Check window size on component creation
  },
  mounted() {
    if (process.client) {
      window.addEventListener('resize', this.checkWindowSize);
    }
    this.checkWindowSize(); // Check window size on component creation
  },
  beforeDestroy() {
    if (process.client) {
      window.removeEventListener('resize', this.checkWindowSize);
    }
  }
};
</script>


<style>

.navBar{
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9;
}
nav{
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--color-1);
  position: relative;
}
nav ul{
  display: flex;
  justify-content: center;
  align-items: center;
  column-gap: 4vw;
}
.nav_item{
  color:var(--color-3);
  font-size: var(--fs_xxxs_800);
  font-weight: 800;
  line-height: 150%;
  letter-spacing: -0.414px;

}

.toggle-button {
  display: block; /* Show the toggle button */
  cursor: pointer;
}

.toggle-button.active .line1 {
  transform: rotate(-45deg) translate(-4px, 8px);
}

.toggle-button.active .line2 {
  opacity: 0;
}

.toggle-button.active .line3 {
  transform: rotate(45deg) translate(-3px, -7px);
}
.toggle-button span {
  display: block;
  background-color: var(--color-4);
  width: 25px;
  height: 3px;
  margin: 5px auto;
  transition: transform 0.3s, opacity 0.3s; /* Add transition for smooth animation */
}
.mb_size{
  display: none;
}
@media (max-width:1500px){

}
@media (max-width:1200px){

}
@media (max-width:1150px){
  nav{
    position: relative;
  }
  .mb_size{
    display: block;
  }
  .desktop_size{
    display: none;
  }
  .nav_item{
    color:var(--primary_light_color);
    font-size: var(--fs_xs_800);
    font-weight: 800;
    line-height: 150%;
    letter-spacing: -0.414px;
    padding-top: 0;
    padding-bottom: 0;
  }
  .toggle-button{
    position: absolute;
    left: 80px;
  }

}
@media (max-width: 800px) {
  .toggle-button{
    left: 40px;
  }
}
@media (max-width: 650px) {
  .toggle-button{
    position: static;
    left: 0;
  }
}
.sideBar {
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
  flex-direction: column;
  gap: 20px;
  width: 300px; /* Adjust the width as needed */
  height: 100vh;
  position: fixed;
  top: 8vh;
  left: 0;
  background-color: var(--color-1); /* Light gray background color */
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Subtle box shadow */
  padding: 20px;
  padding-top: 100px;
  z-index: 10; /* Ensure the sidebar is above other elements */
}
.sideBar li{
  margin: 0 20px;
}
.sideBar .nav_item{
  color: var(--color-3);
}
.sideBar .change_lang_container{
  position: static;
}
@media (max-width: 350px) {
  .sideBar{
    width: 100%;
  }
}
</style>


<!-- i have tabel of data, i want to send it to api to store it in data base , this is example form post man how i enter data , city_id:1
area_id:
destination_type_id:2
translations[0][locale]:ar
translations[0][name]:زيارة البيت المقلوب واخذ صور تذكارية
translations[0][short_description]:استمتع بتجربة ركوب الامواج المليئة بالاثارة و المتعة
translations[0][full_description]:يقصد كثير من السائحين مدينة أنطاليا لإرواء غليلهم بمتعة تجربة ركوب الأمواج و(الركمجة)، وعلى الرغم من عدم توفر أمواج عالية تستحق منافسة ركوب الأمواج الدولية إلا أنها يمكن أن توفر لراكبي الأمواج السهلة كثيراً من المتعة، وخاصة عند ممارسة رياضة ركوب المراكب الشراعية. حيث تكون الأمواج البحر الأبيض على السواحل الجنوبية لتركيا، والتي تدوم عادة ما بين سبع إلى اثنتي عشرة ثانية ويصل ارتفاعها إلى ثلاثة أمتار تقريباً، مثالية لركوب الأمواج من المستوى المتوسط. ولتحقيق المتعة على أصولها برز العديد من مدارس ركوب الأمواج على طول ساحل أنطاليا، مما يسمح للسياح بتحسين قدراتهم، واستئجار المعدات اللازمة عند الحاجة.
translations[1][locale]:tr
translations[1][name]:Antalya'da sörf
translations[1][short_description]:Heyecan ve eğlence dolu bir sörf deneyiminin tadını çıkarın
translations[1][full_description]:Birçok turist susuzluğunu sörf ve rüzgar sörfü keyfiyle gidermek için Antalya şehrine gelmektedir.Uluslararası sörf yarışmasına layık yüksek dalgalar olmasa da özellikle yelkenli sörfü yaparken sörfçülere oldukça keyif verebilirler. Türkiye'nin güney kıyısındaki genellikle yedi ila on iki saniye süren ve yaklaşık üç metre yüksekliğe ulaşan Beyaz Deniz dalgaları orta seviye sörf için idealdir. Eğlenceyi hayata geçirmek için Antalya kıyılarında turistlerin yeteneklerini geliştirmelerine ve gerektiğinde gerekli ekipmanı kiralamalarına olanak tanıyan birçok sörf okulu ortaya çıktı.
translations[2][locale]:en
translations[2][name]:Surfing in Antalya
translations[2][short_description]:Enjoy a surfing experience full of excitement and fun
translations[2][full_description]:Many tourists come to the city of Antalya to quench their thirst with the pleasure of surfing and windsurfing. Although there are no high waves worthy of international surfing competition, they can provide surfers with a lot of pleasure, especially when practicing sailboat riding. The White Sea waves on the southern coast of Turkey, which usually last between seven and twelve seconds and reach a height of approximately three metres, are ideal for intermediate level surfing. To bring the fun to life, many surfing schools have emerged along the coast of Antalya, allowing tourists to improve their abilities and rent the necessary equipment when needed. -->
