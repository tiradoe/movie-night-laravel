<template>
  <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">Schedule</h1>
  <hr class="mb-10 shadow" />

  <ul v-if="showings.length > 0" class="flex flex-col">
    <li :key="showing.id" v-for="showing in showings" class="flex m-10">
      <span class="text-left w-1/2">{{ showing.movie.title }}</span>
      <span class="text-right sm:text-left w-1/2">{{
        prettyDate(showing.show_time)
      }}</span>
    </li>
  </ul>
  <div v-else>
    <span class="block italic">"Anyway, how's your sex life?"</span>
    <span class="font-semibold">Tommy Wiseau - The Room</span>
  </div>
</template>

<script lang="ts">
import { AxiosResponse } from "axios";
import { defineComponent } from "vue";

export default defineComponent({
  name: "Schedule",
  components: {},
  methods: {
    prettyDate(showtime: Date): string {
      const showTime = new Date(showtime);

      const formatted = `${showTime.getMonth()}/${showTime.getDate()}/${showTime.getFullYear()}`;
      return formatted;
    },
  },
  data: function () {
    return {
      showings: [],
    };
  },
  mounted() {
    this.$http.get("/api/showings").then((response: AxiosResponse) => {
      this.showings = response.data.showings;
    });
  },
});
</script>
