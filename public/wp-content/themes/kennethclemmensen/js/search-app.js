const app = new Vue({
    el: '#search-app',
    data: {
        searchString: '',
        results: []
    },
    watch: {
        searchString: function() {
            this.search();
        }
    },
    methods: {
        search: function() {
            if(this.searchString === '') {
                this.results = [];
                return;
            }
            let self = this;
            let statusCodeOk = 200;
            let request = new XMLHttpRequest();
            request.open('get', '/wp-json/kcapi/v1/pages/' + this.searchString, true);
            request.onload = function() {
                self.results = (request.status === statusCodeOk) ? JSON.parse(request.responseText) : [];
            };
            request.onerror = function() {
                self.results = [];
            };
            request.send();
        }
    },
    components: {
        'search-results': {
            data: function() {
                return {
                    currentPage: 0,
                    offset: 0,
                    perPage: 5
                }
            },
            methods: {
                previousPage: function() {
                    this.currentPage--;
                    this.offset = this.currentPage * this.perPage;
                },
                nextPage: function() {
                    this.currentPage++;
                    this.offset = this.currentPage * this.perPage;
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
                results: function() {
                    this.currentPage = 0;
                    this.offset = 0;
                }
            },
            template: `
                <div>
                    <div v-for="(result, index) in results" :key="result.id">
                        <span v-if="index >= offset && index < offset + perPage">
                            <a :href="result.link">{{ result.title }}</a>
                            <p>{{ result.excerpt }}</p>
                        </span>
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