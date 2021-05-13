<template>
  <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">Schedule</h1>
  <hr class="mb-10 shadow" />

  <ul v-if="showings.length > 0" class="flex flex-col">
    <li :key="showing.id" v-for="showing in showings" class="flex m-10">
      <span class="text-left w-1/2">{{ showing.movie.title }}</span>
      <span class="text-right sm:text-left w-1/2">{{
        prettyDate(showing.show_time)
      }}</span>
      <font-awesome-icon
        @click="deleteShowing(showing.id)"
        class="cursor-pointer hover:text-red-700"
        icon="trash-alt"
      />
    </li>
  </ul>
  <div v-else>
    <movie-quote
      actor="Tommy Wiseau"
      movie="The Room"
      quote="Anyway, how's
    your sex life?"
    />
  </div>
</template>

<script lang="ts">
import { AxiosError, AxiosResponse } from "axios";
import { defineComponent } from "vue";
import MovieQuote from "@/components/MovieQuote.vue";

export default defineComponent({
  name: "Schedule",
  components: { MovieQuote },
  methods: {
    prettyDate(showTime: Date): string {
      const formatted = new Date(showTime).toLocaleString("en-US", {
        month: "2-digit",
        day: "2-digit",
        year: "2-digit",
      });

      return formatted;
    },
    deleteShowing(showingId: number): void {
      const confirmDelete = confirm(`Delete showing?`);

      if (confirmDelete === true) {
        this.$http
          .delete(`/api/showings/${showingId}`)
          .then(this.getShowings());
      }
    },
    getShowings(): void {
      this.$http
        .get("/api/showings")
        .then((response: AxiosResponse) => {
          this.showings = response.data.showings;
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            this.$router.push("/login");
          }
        });
    },
  },
  data: function () {
    return {
      showings: [],
    };
  },
  mounted() {
    this.getShowings();
  },
});
</script>
