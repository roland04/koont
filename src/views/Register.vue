<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png" />
    <HelloWorld msg="Welcome to Your Vue.js App" />
  </div>
</template>

<script>
// @ is an alias to /src
import RegisterPage from "@/components/RegisterPage.vue";

export default {
  name: "register",
  components: {
    /* eslint-disable vue/no-unused-components */
    RegisterPage
  }
};
</script>
