<template>
  <div class="container">
    <h2 class="section-title text-center">Estimate your price</h2>
    <div class="ba-row flex-container" v-for="(row, index) in pricesLive" :key="index">
      <span class="ba-row__name">
        {{ row.parameter }}
      </span>
      <span class="ba-row__index">
        {{ row.current }}
      </span>
      <Slider showTooltip="always" v-model="row.current" :min="row.included" :max="row.max || 100" v-if="row.type != 1" class="ba-row__slider"/>
      <span class="ba-row__dots" v-if="row.type == 1"></span>
      <span class="ba-row__value">
        {{ getParameterTotal(row.included, row.current, row.value, row.type) }}
      </span>
    </div>
    <div class="ba-total-price">
      {{ getTotalPrice() }}
    </div>
    <a href="" class="button btn btn-primary">
      Get Started
    </a>
  </div>

</template>

<script>
/* eslint-disable */
import axios from "axios";
import Slider from '@vueform/slider'

export default {
  name: 'App',
  components: {
    Slider
  },
  data() {
    return {
      value: 20,
      isLoading: true,
      api: "https://neonpanel.com/php/api.php",
      data: []
    }
  },
  methods: {
    getParameterTotal(included, current, value, type) {
      let total = 0;
      switch (type) {
        case 1:
          total = value;
          break;
        case 2:
          total = (current - included) * value;
          break;
        case 3:
          total = (1 + (current - included) * value / 100)
          break;
      }
      return +total.toFixed(2)
    },
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
    },
    getTotalPrice() {
      let total = 0;
      this.pricesLive.forEach((row) => {
        if (row.type == 3) {
          total *= this.getParameterTotal(row.included, row.current, row.value, row.type)
        } else {
          total += this.getParameterTotal(row.included, row.current, row.value, row.type)
        }
      })
      return total.toFixed(2);
    }
  },
  mounted() {
    this.getData()
  },
  computed: {
    pricesLive() {
      let prices = this.data.filter(el => el.price_id == 1 && el.live == 1)
      return prices.map(el => {
        el.current = el.included;
        return el
      })
    },
    pricesComing() {
      return this.data.filter(el => el.price_id == 1 && el.live == 0)
    }
  }
}
</script>

<style lang="scss">
.button {
  color: #fff;
  background: #5956E9;
  border: 1px solid #5956E9;
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
<style src="@vueform/slider/themes/default.css"></style>