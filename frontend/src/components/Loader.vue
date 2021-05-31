<template>
  <div class="mt-10 w-screen">
    <img class="m-5 text-center mx-auto" src="@/assets/loading.gif" />
    <span v-if="item" class="m-5 font-extrabold">Loading {{ item }}</span>
    <span v-else class="m-5 font-extrabold">Loading</span>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
export default defineComponent({
  name: "Loader",
  props: {
    item: {
      type: String,
      required: false,
    },
  },
});
</script>
