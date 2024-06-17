<template>
  <div class="w-full lg:w-auto px-4 flex items-center justify-center my-10">
    <a :class="`vue3-page-link ${modelValue === 1 ? 'disabled' : 'link'}`" @click.prevent="increase" href="#">
      <svg width="6" height="8" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.53335 3.99999L4.86668 1.66666C5.13335 1.39999 5.13335 0.999992 4.86668 0.733325C4.60002 0.466659 4.20002 0.466659 3.93335 0.733325L1.13335 3.53333C0.866683 3.79999 0.866683 4.19999 1.13335 4.46666L3.93335 7.26666C4.06668 7.39999 4.20002 7.46666 4.40002 7.46666C4.60002 7.46666 4.73335 7.39999 4.86668 7.26666C5.13335 6.99999 5.13335 6.59999 4.86668 6.33333L2.53335 3.99999Z" fill="#A4AFBB"></path>
      </svg>
    </a>
    <a v-for="(page, key) in totalPages" :key="key" @click.prevent="$emit('update:modelValue', page)" :class="`vue3-page-link ${page === modelValue ? 'active' : 'link'}`" href="#">{{ page }}</a>
    <a :class="`vue3-page-link ${modelValue === totalPages.length ? 'disabled' : 'link'}`" @click.prevent="decrease" href="#">
      <svg width="6" height="8" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4.88663 3.52667L2.05996 0.700006C1.99799 0.637521 1.92425 0.587925 1.84301 0.554079C1.76177 0.520233 1.67464 0.502808 1.58663 0.502808C1.49862 0.502808 1.41148 0.520233 1.33024 0.554079C1.249 0.587925 1.17527 0.637521 1.1133 0.700006C0.989128 0.824915 0.919434 0.993883 0.919434 1.17001C0.919434 1.34613 0.989128 1.5151 1.1133 1.64001L3.4733 4.00001L1.1133 6.36001C0.989128 6.48491 0.919434 6.65388 0.919434 6.83001C0.919434 7.00613 0.989128 7.1751 1.1133 7.30001C1.17559 7.36179 1.24947 7.41068 1.33069 7.44385C1.41192 7.47703 1.49889 7.49385 1.58663 7.49334C1.67437 7.49385 1.76134 7.47703 1.84257 7.44385C1.92379 7.41068 1.99767 7.36179 2.05996 7.30001L4.88663 4.47334C4.94911 4.41136 4.99871 4.33763 5.03256 4.25639C5.0664 4.17515 5.08383 4.08801 5.08383 4.00001C5.08383 3.912 5.0664 3.82486 5.03256 3.74362C4.99871 3.66238 4.94911 3.58865 4.88663 3.52667Z" fill="#A4AFBB"></path>
      </svg>
    </a>
  </div>
</template>

<script>
  export default {
    props: {
      modelValue: {},
      perPage: {
        default: 15,
      },
      total: {
        default: 1,
      },
    },
    computed: {
      totalPages() {
        const pages = [];
        for( let i = 1; i <= Math.ceil(this.total / this.perPage); i++ ) {
          pages.push(i);
        }
        return pages;
      },
    },
    methods: {
      increase() {
        if( this.modelValue > 1 ) {
          this.$emit('update:modelValue', this.modelValue - 1);
        }
      },
      decrease() {
        if( this.modelValue < this.totalPages.length ) {
          this.$emit('update:modelValue', this.modelValue + 1);
        }
      }
    }
  }
</script>

<style scoped>
  .vue3-page-link {
    display: inline-flex;
    margin-right: 0.5rem;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    font-size: 0.75rem;
    line-height: 1rem;
    border-width: 1px;
    border-radius: 0.25rem;
  }
  .vue3-page-link.link {
    cursor: pointer;
    color: rgba(107, 114, 128, 1);
    border-color: rgba(209, 213, 219, 1);
    background-color: rgba(255, 255, 255, 1);
  }
  .vue3-page-link.disabled {
    cursor: default;
    color: rgba(107, 114, 128, 0.7);
    border-color: rgba(209, 213, 219, 0.5);
    background-color: rgba(255, 255, 255, 0.5);
  }
  .vue3-page-link.active {
    cursor: pointer;
    color: #fff;
    border-color: rgba(209, 213, 219, 0.5);
    background-color: rgba(16, 185, 129, 1);
  }
</style>