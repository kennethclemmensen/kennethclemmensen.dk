import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { Url } from './enums/Url';
/**
 * The SearchApp class contains methods to handle the search functionality
 */
export class SearchApp {
    /**
     * Initialize a new instance of the SearchApp class
     */
    constructor() {
        this.setupSearchApp();
    }
    /**
     * Setup the search app
     */
    setupSearchApp() {
        const searchApp = {
            data() {
                return {
                    searchString: '',
                    results: []
                };
            },
            watch: {
                searchString: function () {
                    this.debouncedSearch();
                }
            },
            created: function () {
                const delay = 500;
                this.debouncedSearch = _.debounce(this.search, delay);
            },
            methods: {
                search: function () {
                    if (this.searchString === '') {
                        this.results = [];
                        return;
                    }
                    const xhr = new XMLHttpRequest();
                    xhr.open(HttpMethod.Get, Url.ApiPages + this.searchString, true);
                    xhr.addEventListener(EventType.Load, () => {
                        this.results = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
                    });
                    xhr.addEventListener(EventType.Error, () => {
                        this.results = [];
                    });
                    xhr.send();
                }
            },
            components: {
                'search-results': {
                    data: () => {
                        return {
                            offset: 0
                        };
                    },
                    methods: {
                        previousPage: function () {
                            this.offset -= parseInt(this.perPage);
                        },
                        nextPage: function () {
                            this.offset += parseInt(this.perPage);
                        }
                    },
                    props: {
                        results: {
                            required: true,
                            type: Array
                        },
                        perPage: {
                            required: true,
                            type: Number
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
                            <div v-for="result in results.slice(offset, (offset + parseInt(perPage)))" :key="result.id">
                                <a :href="result.link">{{ result.title }}</a>
                                <p>{{ result.excerpt }}</p>
                            </div>
                            <div class="pagination">
                                <a href="#" @click.prevent="previousPage" v-if="offset > 0">{{ previousText }}</a>
                                <a href="#" @click.prevent="nextPage" v-if="offset < (results.length - parseInt(perPage))">{{ nextText }}</a>
                            </div>
                        </div>
                    `
                }
            }
        };
        Vue.createApp(searchApp).mount('#search-app');
    }
}
