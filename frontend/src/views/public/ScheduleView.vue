<template>
  <loader v-if="loading" item="schedule" />
  <div v-else>
    <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">
      {{ title }}
    </h1>
    <hr class="mb-10 shadow" />
    <div
      v-if="showings.length > 0 || previousShowings.length > 0"
      class="text-center sm:m-10 max-w-6xl xl:mx-auto"
    >
      <!-- UPCOMING SHOWINGS -->
      <h2 class="m-5 font-semibold text-left sm:m-0 sm:mb-5">
        Upcoming Showings
      </h2>
      <ul v-if="showings.length" class="flex flex-col">
        <li
          :key="showing.id"
          v-for="showing in showings"
          class="w-full p-2 bg-gray-300 mb-2"
        >
          <showing-info :showing="showing" />
        </li>
      </ul>

      <span v-else class="block text-left">Nothing scheduled</span>

      <!-- PREVIOUS SHOWINGS -->
      <details class="flex pt-10">
        <summary class="flex flex-row list-none cursor-pointer">
          <h2
            v-if="previousShowings.length > 0"
            class="flex flex-row m-5 font-semibold underline sm:m-0 sm:mb-5"
          >
            Previous Showings
          </h2>
        </summary>
        <ul
          v-if="previousShowings.length > 0"
          class="flex flex-col rounded sm:m-0"
        >
          <li
            :key="showing.id"
            v-for="showing in previousShowings"
            class="w-full p-2 bg-gray-300 mb-2"
          >
            <showing-info :showing="showing" />
          </li>
        </ul>
      </details>
    </div>
    <div v-else class="p-10 bg-gray-300 rounded shadow sm:m-10">
      <movie-quote
        actor="Tommy Wiseau"
        movie="The Room"
        quote="Anyway, how's
    your sex life?"
      />
    </div>
  </div>
</template>
<script lang="ts">
import {defineComponent} from "vue";
import Loader from "@/components/Loader.vue";
import MovieQuote from "@/components/MovieQuote.vue";
import ShowingInfo from "@/components/Showing.vue";
import {AxiosResponse} from "axios";
import {Schedule, Showing} from "@/types/index";

const previousShowings: Showing[] = [];
const showings: Showing[] = [];
let schedule: Schedule;

export default defineComponent({
  name: "ScheduleView",
  components: { MovieQuote, Loader, ShowingInfo },
  computed: {
    title(): string {
      if (this.schedule?.name) {
        return this.schedule.name;
      }
      return "Schedule";
    },
  },
  methods: {
    prettyDate(showTime: Date): string {
      return new Date(showTime).toLocaleString("en-US", {
        month: "2-digit",
        day: "2-digit",
        year: "2-digit",
      });
    },
    deleteShowing(showingId: number): void {
      const confirmDelete = confirm(`Delete showing?`);

      if (confirmDelete) {
        this.$http
          .delete(`/api/showings/${showingId}`)
          .then(this.getShowings());
      }
    },
    getShowings(): void {
      const pathname = window.location.pathname;

      const username = pathname.split("/")[2];
      const uuid = pathname.split("/")[4];

      this.$http
        .get(`/api/schedules/user/${username}/slug/${uuid}`)
        .then((response: AxiosResponse) => {
          this.previousShowings = [];
          this.showings = [];

          response.data.schedule.public_showings.forEach((showing: Showing) => {
            const show_time = new Date(showing.show_time.toString());
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (show_time.getTime() < today.getTime()) {
              this.schedule = response.data.schedule;
              this.previousShowings.push(showing);
            } else {
              this.showings.push(showing);
            }
          });

          this.loading = false;
        });
    },
  },
  data: function () {
    return {
      schedule: schedule,
      showings: showings,
      previousShowings: previousShowings,
      loading: true,
    };
  },
  mounted() {
    this.getShowings();
  },
});
</script>
