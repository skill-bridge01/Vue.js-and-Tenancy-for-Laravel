<script setup>
import { reactive, watch, ref } from 'vue';
import { useI18n } from "vue-i18n";
import { onClickOutside, useDebounceFn } from '@vueuse/core'
import UserBlackIcon from '@/assets/icons/sidebar/user_black.svg'
import { useCustomersStore } from '@/store/pinia/customers'
import { storeToRefs } from 'pinia';
import { SpringSpinner } from 'epic-spinners'

const props = defineProps({
  name: {
    type: String,
    required: false,
    default: 'autocomplete',
  },
  source: {
    type: [String, Array, Function],
    required: true,
    default: '',
  },
  label: {
    type: String,
    required: false,
    default: 'name',
  },
  responseProperty: {
    type: String,
    required: false,
    default: 'name',
  },
});
let data = reactive({
  search:'',
  isOpen:false,
  results:[],
});

const { t } = useI18n();
const emits = defineEmits(['clickCustomerRow'])

// watch(
//   () => data.search, 
//   async (newValue, oldValue) => {
//     if (newValue.length > 2) {
//       data.isOpen = true
//       arrayLikeSearch(props.source, newValue);
//       console.log(newValue)
//     }
// })

const target = ref(null)
const loading = ref(false)
const customersStore = useCustomersStore()
const { customers, selectedCustomer } = storeToRefs(useCustomersStore())

const onChange =useDebounceFn(() => {
  console.log(data.search)
  if (!props.source || !data.search) 
    return false;

  data.isOpen = true;
  switch (true) {
    case typeof props.source === 'string':
      return request(data.search);
    case typeof props.source === 'function':
      return props.source(data.search).then(response=>{   
        data.results = getResults(response);
      });
    case Array.isArray(props.source):
      return arrayLikeSearch(props.source);
    default:
      throw new Error("typeof source is "+(typeof props.source))
  }
}, 500);

const arrayLikeSearch = (items, search = '') => {
  data.results = items.filter((item) => {
    return item.name.toLowerCase().indexOf(data.search.toLowerCase()) > -1;
  });
}

const request = (query) => {
  loading.value = true
  customersStore.search(query).then((res) => {
    loading.value = false
    console.log(res)
    data.results = res    
  }).catch((err) => {
    console.log(err)
    loading.value = false
  })
}

const getResults = (response) => {
  if (props.responseProperty) {
    let foundObj;
    JSON.stringify(response, (_, nestedValue) => {
      if (nestedValue && nestedValue[props.responseProperty]) 
        foundObj = nestedValue[props.responseProperty];

      return nestedValue;
    });
    return foundObj;
  }
  if(Array.isArray(response)){
    return response;
  }
  return []
}
const setResult = (result) => {
  data.search = result[props.label];
  data.isOpen = false;
  // data.results = []
  emits('clickCustomerRow', result)
}

onClickOutside(target, (event) => {
  data.isOpen = !data.isOpen
})
</script>
<template>
  <div class="my-2 relative z-40 flex items-center">
    <input
      type="text"
      class="
        text-base
        font-medium
        block
        rounded-md
        transition
        ease-in-out
        py-2
        px-3
        text-gray-700
        placeholder-gray-400
        border-none
        focus:outline-none
        text-right
        w-40
        h-12
        search-input
      "
      :placeholder="t('dashboard.typeName')"
      :name="props.name"
      v-model="data.search"
      @input="onChange"
    />
    <spring-spinner
      v-show="loading"
      :animation-duration="2500"
      :size="20"
      color="grey"
    />
    <UserBlackIcon v-if="!loading" />
    <ul
      class="mt-1 border-2 border-slate-50 overflow-auto  shadow-lg rounded list-none max-h-40 w-40 absolute bg-white top-12"
      v-if="data.isOpen"
      ref="target"
    >
      <li 
        :class="[
          'hover:bg-blue-100 hover:text-blue-800',
          'w-full list-none text-right py-2 px-3 cursor-pointer text-sm']
        "
        v-for="(result, i) in data.results" :key="i"
        @click="setResult(result)"
      >
        <p>{{ result.name }}</p>
        <p>{{ result.mobile }}</p>
      </li>
    </ul>
  </div>
</template>
<style lang="scss" scoped>
  .search-input,
  .search-input:focus,
  .search-input:active {
    box-shadow: none;
  }
</style>