import { map } from 'rxjs';
import { ajax } from 'rxjs/ajax';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
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
        Vue.createApp({
            data() {
                return {
                    searchString: '',
                    results: [],
                    timeoutId: null
                };
            },
            watch: {
                searchString: function () {
                    if (this.timeoutId != null) {
                        clearTimeout(this.timeoutId);
                    }
                    const delay = 500;
                    this.timeoutId = setTimeout(() => {
                        this.search();
                        this.timeoutId = null;
                    }, delay);
                }
            },
            methods: {
                search: function () {
                    if (this.searchString === '') {
                        this.results = [];
                        return;
                    }
                    const searchResults$ = ajax({
                        url: '/wp-json/kcapi/v1/pages/' + this.searchString,
                        method: HttpMethod.Get,
                        responseType: 'text'
                    }).pipe(map((response) => {
                        this.results = (response.status === HttpStatusCode.Ok) ? JSON.parse(response.xhr.responseText) : [];
                    }));
                    searchResults$.subscribe();
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
        }).mount('#search-app');
    }
}
