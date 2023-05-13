import { fromEvent } from 'rxjs';
import { EventType } from './enums/EventType';
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
                    const xhr = new XMLHttpRequest();
                    xhr.open(HttpMethod.Get, '/wp-json/kcapi/v1/pages/' + this.searchString, true);
                    fromEvent(xhr, EventType.Load).subscribe(() => {
                        this.results = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
                    });
                    fromEvent(xhr, EventType.Error).subscribe(() => {
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
        }).mount('#search-app');
    }
}
