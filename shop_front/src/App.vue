<template>
  <div class="container mx-auto px-4 py-2">
    <NavigationBar />
    <router-view></router-view>
    <AboutUs />
  </div>
</template>

<script>

import NavigationBar from "@/components/Navigation/NavigationBar.vue";
import AboutUs from "@/components/Navigation/AboutUs.vue";

export default {
  components: {
    NavigationBar,
    AboutUs
  }
};
</script>
