<template>
  <div  class="call-nav drop-shadow" >
    <p class="textColorGradient">{{ $t('nav_call') }}</p>
  </div>
</template>
<script>

</script>

<style>
.call-nav{
  border-radius: 20px;
  border: 3px solid var(--color-2);
  background: var(--color-1);
}
.call-nav p{
  font-size: var(--fs_xxs_800);
  font-weight: 800;
  padding: 2px 24px;
}

</style>
