<template>
  <div class="change_lang_container">
    <div class="drop_down">
      <p class="drop_down_lang_choose" @click="toggleDropDown">
        {{ $i18n.locale.toUpperCase() }}
        <svg :class="{ rotated: isShowen, unrotated: !isShowen }" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 16L3 7H21L12 16Z" fill="white"/>
        </svg>
      </p>
      <div :class="{ show: isShowen, hide: !isShowen }" v-show="isShowen" class="drop_down_lang_list border-ra-10" @click.stop>
        <div @click="toggleDropDown">
          <nuxt-link class="lang_link pointer" :to="switchLocalePath('en')">EN</nuxt-link>
        </div>
        <div @click="toggleDropDown" >
          <nuxt-link class="lang_link pointer" :to="switchLocalePath('ar')">AR</nuxt-link>
        </div>
        <div @click="toggleDropDown">
          <nuxt-link class="lang_link pointer"  :to="switchLocalePath('tr')">TR</nuxt-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isShowen: false
    };
  },
  methods: {
    toggleDropDown() {
      this.isShowen = !this.isShowen;
    },
    closeDropDownOnOutsideClick(event) {
      if (!this.$el.contains(event.target) && this.isShowen) {
        this.isShowen = false;
      }
    },
    closeDropDownOnScroll() {
      if (this.isShowen) {
        this.isShowen = false;
      }
    },
  },
  mounted() {
    if(process.client){
      window.addEventListener('click', this.closeDropDownOnOutsideClick);
      window.addEventListener('scroll', this.closeDropDownOnScroll);
    }
  },
  beforeDestroy() {
    if(process.client){
      window.removeEventListener('click', this.closeDropDownOnOutsideClick);
      window.removeEventListener('scroll', this.closeDropDownOnScroll);
    }
  },
};
</script>

<style>
.change_lang_container{
  position: absolute;
  left: 50px;
}

.drop_down_lang_choose {
  cursor: pointer;
  display: flex;
  align-items: center;
  color: var(--color-2);
  font-size: var(--fs_xxs_500);
  font-weight: 500;
}

.drop_down_lang_list {
  display: none;
  position: absolute;
  top: 70px;
  right: 0;
  z-index: 1000;
  min-width: 100%;
  background-color: var(--color-1);
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}

.drop_down_lang_list.show {
  display: block;
}

.lang_link {
  display: block;
  color: var(--color-2);
  font-size: var(--fs_xxs_500);
  font-weight: 500;
  padding: 8px 16px;
  transition: background-color 0.3s;
}

@media (max-width: 1000px) {
  .drop_down_lang_choose svg{
    width: 16px;
    height: 16px;
  }
  .lang_link {
    padding: 4px 8px;
  }
}
@media (max-width: 800px) {
  .change_lang_container{
    left: 20px;
  }
}
@media (max-width: 650px) {
  .drop_down_lang_list{
    position: static;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    background: var(--primary_light_color);
    box-shadow: none;
  }
  .drop_down{
    width: 100%;
  }
  .lang_link{
    color: var(--main_color);
    font-size: var(--fs_xs_700);
    font-weight: 700;
    padding: 10px;
  }
  .drop_down_lang_choose {
    font-size: var(--fs_xs_800);
    font-weight: 800;
  }
  .change_lang_container{
    width: 100%;
  }
}
</style>
