import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
/**
 * The search app class contains methods to handle the search functionality
 */
export class SearchApp {
    /**
     * SearchApp constructor
     */
    constructor() {
        this.setupSearchApp();
    }
    /**
     * Setup the search app
     */
    setupSearchApp() {
        new Vue({
            el: '#search-app',
            data: {
                searchString: '',
                results: []
            },
            watch: {
                searchString: function () {
                    this.search();
                }
            },
            methods: {
                search: function () {
                    if (this.searchString === '') {
                        this.results = [];
                        return;
                    }
                    let request = new XMLHttpRequest();
                    request.open(HttpMethod.Get, '/wp-json/kcapi/v1/pages/' + this.searchString, true);
                    request.addEventListener('load', () => {
                        this.results = (request.status === HttpStatusCode.Ok) ? JSON.parse(request.responseText) : [];
                    });
                    request.addEventListener('error', () => {
                        this.results = [];
                    });
                    request.send();
                }
            },
            components: {
                'search-results': {
                    data: function () {
                        return {
                            offset: 0,
                            perPage: 5
                        };
                    },
                    methods: {
                        previousPage: function () {
                            this.offset -= this.perPage;
                        },
                        nextPage: function () {
                            this.offset += this.perPage;
                        }
                    },
                    props: {
                        results: {
                            required: true,
                            type: Array
                        },
                        previousText: {
                            required: true,
                            type: String
                        },
                        nextText: {
                            required: true,
                            type: String
                        }
                    },
                    watch: {
                        results: function () {
                            this.offset = 0;
                        }
                    },
                    template: `
                        <div>
                            <div v-for="result in results.slice(offset, (offset + perPage))" :key="result.id">
                                <a :href="result.link">{{ result.title }}</a>
                                <p>{{ result.excerpt }}</p>
                            </div>
                            <div>
                                <a href="#" @click.prevent="previousPage" v-if="offset > 0">{{ previousText }}</a>
                                <a href="#" @click.prevent="nextPage" v-if="offset < (results.length - perPage)">{{ nextText }}</a>
                            </div>
                        </div>
                    `
                }
            }
        });
    }
}