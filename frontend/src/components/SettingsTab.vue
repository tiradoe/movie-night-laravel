<template>
  <div
    class="flex flex-col items-center mt-5 bg-gray-300"
    v-show="!loading && selectedTab === 'settings'"
  >
    <div class="flex">
      <label for="is-public" class="mr-2">Public:</label>
      <input
        @change="updateAccess"
        type="checkbox"
        v-model="currentList.isPublic"
        id="is-public"
      />
    </div>

    <div class="flex">
      <span class="mr-2">URL:</span>
      <span>{{ app_host }}/list/{{ currentList.slug }}</span>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "@vue/runtime-core";

export default defineComponent({});
</script>
