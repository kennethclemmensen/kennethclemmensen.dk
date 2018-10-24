declare let Vue: any;

new Vue({
    el: '#search-app',
    data: {
        searchString: '',
        results: []
    },
    watch: {
        searchString: function(): void {
            this.search();
        }
    },
    methods: {
        search: function(): void {
            if(this.searchString === '') {
                this.results = [];
                return;
            }
            let self: any = this;
            let statusCodeOk: number = 200;
            let request: XMLHttpRequest = new XMLHttpRequest();
            request.open('get', '/wp-json/kcapi/v1/pages/' + this.searchString, true);
            request.addEventListener('load', () => {
                self.results = (request.status === statusCodeOk) ? JSON.parse(request.responseText) : [];
            });
            request.addEventListener('error', () => {
                self.results = [];
            });
            request.send();
        }
    },
    components: {
        'search-results': {
            data: function(): object {
                return {
                    offset: 0,
                    perPage: 5
                }
            },
            methods: {
                previousPage: function(): void {
                    this.offset -= this.perPage;
                },
                nextPage: function(): void {
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
                results: function(): void {
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