<template>
  <div class="container">
    <h2 class="section-title text-center">Estimate your price</h2>

    <a href="" class="button btn btn-primary">
      Get Started
    </a>
  </div>

</template>

<script>
import axios from "axios";

export default {
  name: 'App',
  components: {
  },
  data(){
    return {
      isLoading : true,
      api: "https://neonpanel.com/php/api.php",
      data : []
    }
  },
  methods:{
    getData() {
      this.isLoading = true;
      axios
          .get(this.api)
          .then(response => {
            this.data = response.data;
            console.log(this.data);
            this.isLoading = false
          })
          .catch(error => {
            console.log(error);
          });
    }
  },
  mounted() {
    this.getData()
  }
}
</script>

<style lang="scss">
.button{
  color: #fff;
  background: #5956E9;
  border:1px solid #5956E9;
  border-radius: 5px 5px;
  padding: 15px 30px;
  display: inline-block;
  font-size: 15px;
  line-height: 24px;
  font-weight: 600;
  margin: 30px 0 5px 0;
  transition: all 0.3s ease-in-out;
  -webkit-transition: all 0.3s ease-in-out;
}
</style>
