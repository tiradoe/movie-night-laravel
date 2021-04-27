import { createStore } from "vuex";
import { List } from "@/types/index";

const movieList: List = {
    id: 0,
    name: "",
    movieCount: 0,
    movies: [],
};

export default createStore({
    state: {
        currentList: movieList,
    },
    mutations: {
        updateList(state, newList: List) {
            state.currentList = newList;
        },
    },
    actions: {},
    modules: {},
});
