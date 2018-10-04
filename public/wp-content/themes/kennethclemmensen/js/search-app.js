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
            this.$http.get('/wp-json/kcapi/v1/pages/' + this.searchString).then(response => {
                this.results = response.body;
            }, () => {
                this.results = [];
            });
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
            template: `
                <div>
                    <ul>
                        <li v-for="(result, index) in results" :key="result.id" v-if="index >= offset && index < offset + perPage">
                            <a :href="result.link">{{ result.title }}</a>
                            <p>{{ result.excerpt }}</p>
                        </li>
                    </ul>
                    <div>
                        <a href="#" @click.prevent="previousPage" v-if="offset > 0">{{ previousText }}</a>
                        <a href="#" @click.prevent="nextPage" v-if="offset < (results.length - perPage)">{{ nextText }}</a>
                    </div>
                </div>
            `
        }
    }
});