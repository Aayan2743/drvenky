import axios from "axios";
import appService from "../../../services/appService";

export const frontendProductCategory = {
    namespaced: true,
    state: {
        ancestorsAndSelf: [],
        trees: [],
        trees1: [],
        lists: [],
    },
    getters: {
        ancestorsAndSelf: function (state) {
            return state.ancestorsAndSelf;
        },
        trees: function (state) {
            return state.trees;
        },
        trees1: function (state) {
            return state.trees;
        },
        lists: function (state) {
            return state.lists;
        }
    },
    actions: {
        ancestorsAndSelf: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`frontend/product-category/ancestors-and-self/${payload}`).then((res) => {
                    context.commit("ancestorsAndSelf", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        trees: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `frontend/product-category/tree`;
                axios.get(url).then((res) => {
                    context.commit("trees", res.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        trees1: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `frontend/product-category/tree1`;
                axios.get(url).then((res) => {
                    context.commit("trees1", res.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },


        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/product-category";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    context.commit("lists", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        }
    },
    mutations: {
        ancestorsAndSelf: function (state, payload) {
            state.ancestorsAndSelf = payload;
        },
        trees: function (state, payload) {
            state.trees = payload;
        },
        trees1: function (state, payload) {
            state.trees1 = payload;
        },
        lists: function (state, payload) {
            state.lists = payload;
        }
    },
};
