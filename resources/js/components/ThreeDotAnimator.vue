<template>
  <div class="bg-white flex space-x-2 p-5 rounded-full justify-center items-center">
    <div class="bg-gray-800 w-3 h-3 rounded-full animate-bounce blue-circle"></div>
    <div class="bg-gray-800 w-3 h-3 rounded-full animate-bounce green-circle"></div>
    <div class="bg-gray-800 w-3 h-3 rounded-full animate-bounce red-circle"></div>
  </div>
  <!-- <div class="min-h-screen flex justify-center items-center bg-black">

<div class="loader bg-white p-5 rounded-full flex space-x-3">
  <div class="w-5 h-5 bg-gray-800 rounded-full animate-bounce"></div>
  <div class="w-5 h-5 bg-gray-800 rounded-full animate-bounce"></div>
  <div class="w-5 h-5 bg-gray-800 rounded-full animate-bounce"></div>
</div>

</div> -->
</template>
<style lang="scss" scoped>
.blue-circle {
  animation-delay: 0.05s;
}

.green-circle {
  animation-delay: 0.2s;
}

.red-circle {
  animation-delay: 0.4s;
}

//   .loader div {
//   animation-duration: 0.5s;
// }

// .loader div:first-child {
//   animation-delay: 0.1s;
// }

// .loader div:nth-child(2) {
//   animation-delay: 0.3s;
// }

// .loader div:nth-child(3) {
//   animation-delay: 0.6s;
// }
</style>