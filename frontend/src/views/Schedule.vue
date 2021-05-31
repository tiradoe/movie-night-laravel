<template>
  <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">Schedule</h1>
  <hr class="mb-10 shadow" />

  <div
    v-if="showings.length > 0 || previousShowings.length > 0"
    class="sm:m-10"
  >
    <!-- UPCOMING SHOWINGS -->
    <h2 class="m-5 font-semibold text-left sm:m-0 sm:mb-5">
      Upcoming Showings
    </h2>
    <ul
      v-if="showings.length"
      class="flex flex-col bg-gray-300 rounded sm:px-5"
    >
      <li
        :key="showing.id"
        v-for="showing in showings"
        class="flex m-5 sm:m-10"
      >
        <span class="w-1/2 text-left">{{ showing.movie.title }}</span>
        <span class="w-1/2 text-right sm:text-left">{{
          prettyDate(showing.show_time)
        }}</span>
        <font-awesome-icon
          @click="deleteShowing(showing.id)"
          class="ml-5 cursor-pointer hover:text-red-700"
          icon="trash-alt"
        />
      </li>
    </ul>
    <span v-else class="text-left block">Nothing scheduled</span>

    <!-- PREVIOUS SHOWINGS -->
    <details class="flex pt-10 text-left sm:">
      <summary class="flex flex-row cursor-pointer">
        <h2
          v-if="previousShowings.length > 0"
          class="flex flex-row m-5 font-semibold underline sm:m-0 sm:mb-5"
        >
          Previous Showings
        </h2>
      </summary>
      <ul
        v-if="previousShowings.length > 0"
        class="flex flex-col bg-gray-300 rounded sm:px-5"
      >
        <li
          :key="showing.id"
          v-for="showing in previousShowings"
          class="flex m-5 sm:m-10"
        >
          <span class="w-1/2 text-left">{{ showing.movie.title }}</span>
          <span class="w-1/2 text-right sm:text-left">{{
            prettyDate(showing.show_time)
          }}</span>
          <font-awesome-icon
            @click="deleteShowing(showing.id)"
            class="ml-5 cursor-pointer hover:text-red-700"
            icon="trash-alt"
          />
        </li>
      </ul>
    </details>
  </div>
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
import store from "@/store/index";
import { Showing } from "@/types/index";

let previousShowings: Showing[] = [];
let showings: Showing[] = [];

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
          this.previousShowings = [];
          this.showings = [];

          response.data.showings.forEach((showing: Showing) => {
            const show_time = new Date(showing.show_time.toString());
            let today = new Date();
            today.setHours(0, 0, 0, 0);

            if (show_time.getTime() < today.getTime()) {
              this.previousShowings.push(showing);
            } else {
              this.showings.push(showing);
            }
          });
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
  },
  data: function () {
    return {
      showings: showings,
      previousShowings: previousShowings,
    };
  },
  mounted() {
    this.getShowings();
  },
});
</script>
