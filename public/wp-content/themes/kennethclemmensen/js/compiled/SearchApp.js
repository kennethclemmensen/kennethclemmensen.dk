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
        new Vue({
            el: '#search-app',
            data: {
                searchString: '',
                results: []
            },
            watch: {
                searchString: function () {
                    this.debouncedSearch();
                }
            },
            created: function () {
                let delay = 500;
                this.debouncedSearch = _.debounce(this.search, delay);
            },
            methods: {
                search: function () {
                    if (this.searchString === '') {
                        this.results = [];
                        return;
                    }
                    let xhr = new XMLHttpRequest();
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
                            <div class="pagination">
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
