<template>
  <h1 class="p-5 pb-0 text-3xl font-bold text-left sm:text-5xl sm:p-10 sm:pb-5">
    {{ heading || "Nothing Scheduled" }}
  </h1>
  <h2 class="text-left font-semibold sm:px-10 px-5 mb-5 text-2xl">
    {{ movieDate }}
  </h2>
  <hr class="mb-10 shadow" />
  <loader v-if="loading" item="movie" />
  <div v-else>
    <MovieDisplay
      class="sm:mx-10"
      v-if="movie"
      :movie="movie"
      :mainDisplay="true"
      :listId="listId"
    />
    <p v-else class="p-10 bg-white rounded shadow sm:m-10">
      <movie-quote
        actor="Heath Ledger"
        movie="The Dark Knight"
        quote="Do I really
    look like a guy with a plan? <br />You know what I am? I'm a dog chasing
    cars. <br />I wouldn't know what to do with one if I caught it! You know, I
    just... <i>do</i> things."
      />
    </p>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MovieDisplay from "@/components/MovieDisplay.vue";
import { Movie } from "@/types/index";
import MovieQuote from "@/components/MovieQuote.vue";
import Loader from "@/components/Loader.vue";
import { AxiosError, AxiosResponse } from "axios";
import store from "@/store/index";

const movie: Movie | null = null;

export default defineComponent({
  name: "Home",
  components: {
    MovieDisplay,
    MovieQuote,
    Loader,
  },
  data: function () {
    return {
      movie: movie,
      listId: 0,
      heading: "",
      movieDate: "",
      loading: true,
    };
  },
  computed: {},
  mounted() {
    this.$http
      .get("/api/showings?next=1")
      .then((response: AxiosResponse) => {
        const showTime = new Date(response.data.showing.show_time);
        this.getHeading(showTime);

        this.movie = response.data.movie;
        this.loading = false;
      })
      .catch((error: AxiosError) => {
        if (error.response?.status == 401) {
          store.commit("updateLogin", false);
          this.$router.push("/login");
        }
      });
  },
  methods: {
    getHeading(showTime: Date): void {
      const today = new Date();

      const showDate: string = showTime.toLocaleString("en-US", {
        month: "2-digit",
        day: "2-digit",
        year: "2-digit",
      });

      const todayDate: string = today.toLocaleString("en-US", {
        month: "2-digit",
        day: "2-digit",
        year: "2-digit",
      });

      if (showDate === todayDate) {
        this.heading = "TONIGHT";
        this.movieDate = showDate;
      } else {
        this.heading = `Next up`;
        this.movieDate = showDate;
      }
    },
  },
});
</script>
