<template>
  <div class="container">
    <h2 class="section-title text-center">Estimate your price</h2>
    <div class="ba-row" v-for="(row, index) in pricesLive" :key="index">
      <span class="ba-row__name">
        {{ row.parameter }}
      </span>
      <span class="ba-row__index" v-if="row.type != 1">
        {{ formatThousands(row.current) }}
      </span>
      <Slider @change="onChange" @update="onChange" :format="formatPrice" showTooltip="always" v-model="row.current" :min="row.included" :max="row.max || 100" v-if="row.type != 1" class="ba-row__slider"/>
      <span class="ba-row__dots" v-if="row.type == 1"></span>
      <span class="ba-row__value">
        {{ getParameterTotal(row.included, row.current, row.value, row.type, true) }}
      </span>
    </div>
    <div class="ba-total-row">
      <div class="ba-total-price">
        <div class="ba-total-price__sup">Your price is</div>
        <span>{{ getTotalPrice() }}</span>
        <span class="ba-total-price__sub">/mo</span>
      </div>
      <a href="" class="ba-button">
        Get Started
      </a>
    </div>
    <!-- /.ba-total-row -->
    <h2 class="ba-section-title-alt">Coming soon:</h2>
    <div class="ba-row" v-for="(row, index) in pricesComing" :key="index">
      <span class="ba-row__name">
        {{ row.parameter }}
      </span>
      <span class="ba-row__index" v-if="row.type != 1">
        {{ formatThousands(row.current) }}
      </span>
      <Slider :disabled="true" :tooltips="false" showTooltip="always" v-model="row.current" :min="row.included" :max="row.max || 100" v-if="row.type != 1" class="ba-row__slider"/>
      <span class="ba-row__dots" v-if="row.type == 1"></span>
      <span class="ba-row__value">
        {{ getParameterTotal(row.included, row.current, row.value, row.type, true) }}
      </span>
    </div>
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
      data: [],
      formatPrice: {
        thousand: ',',
        decimals: 0
      }
    }
  },
  methods: {
    onChange($event){
      console.log($event);
    },
    getParameterTotal(included, current, value, type, format) {
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
      if(format && (type == 1 || type == 2))
        return this.formatCurrency(+total)

      if(format && (type == 3))
        return this.formatPercent(+total)

      return +total
    },
    formatCurrency(value){
      let formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        // These options are needed to round to whole numbers if that's what you want.
        //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
      });

      return formatter.format(value);
    },
    formatPercent(value){
      return '+' + ((value - 1) * 100).toFixed(0) + '%';
    },
    formatThousands(value){
      if(value <= 1000) return value;

      return (value/1000).toFixed(0) + 'k';
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
      return this.formatCurrency(total);
    }
  },
  mounted() {
    this.getData()
  },
  computed: {
    pricesLive() {
      let prices = this.data.filter(el => el.price_id == 1 && el.live == 1)
      return prices.map(el => {
        el.current = el.included * 20;
        return el
      })
    },
    pricesComing() {
      let prices = this.data.filter(el => el.price_id == 1 && el.live == 0)
      return prices.map(el => {
        el.current = el.included * 20;
        return el
      })
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