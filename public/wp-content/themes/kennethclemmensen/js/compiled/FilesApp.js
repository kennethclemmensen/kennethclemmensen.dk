import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { Url } from './enums/Url';
import { fromEvent } from 'rxjs';
/**
 * The FilesApp class contains methods to handle the functionality of the files
 */
export class FilesApp {
    /**
     * Initialize a new instance of the FilesApp class
     */
    constructor() {
        this.setupFilesApp();
    }
    /**
     * Setup the files app
     */
    setupFilesApp() {
        const filesApp = {
            components: {
                'files': {
                    data: () => {
                        return {
                            files: [],
                            offset: 0
                        };
                    },
                    created: function () {
                        const xhr = new XMLHttpRequest();
                        xhr.open(HttpMethod.Get, Url.ApiFiles + this.fileTypes, true);
                        fromEvent(xhr, EventType.Load).subscribe(() => {
                            this.files = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
                        });
                        fromEvent(xhr, EventType.Error).subscribe(() => {
                            this.files = [];
                        });
                        xhr.send();
                    },
                    methods: {
                        previousPage: function () {
                            this.offset -= parseInt(this.perPage);
                        },
                        nextPage: function () {
                            this.offset += parseInt(this.perPage);
                        },
                        updateFileDownloads: (file) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open(HttpMethod.Put, Url.ApiFileDownloads + file.id, true);
                            fromEvent(xhr, EventType.Load).subscribe(() => {
                                if (xhr.status === HttpStatusCode.Ok)
                                    file.downloads++;
                            });
                            xhr.send();
                        }
                    },
                    props: {
                        fileTypes: {
                            required: true,
                            type: String
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
                        },
                        numberOfDownloadsText: {
                            required: true,
                            type: String
                        }
                    },
                    template: `
						<div>
							<div v-for="file in files.slice(offset, (offset + parseInt(perPage)))">
								<a :href="file.url" @click="updateFileDownloads(file)" rel="nofollow" download>{{ file.fileName }}</a>
								<p>{{ file.description }}</p>
								<p>{{ numberOfDownloadsText }} {{ file.downloads }}</p>
							</div>
							<div class="pagination">
								<a href="#" @click.prevent="previousPage" v-if="offset > 0">{{ previousText }}</a>
								<a href="#" @click.prevent="nextPage" v-if="offset < (files.length - parseInt(perPage))">{{ nextText }}</a>
							</div>
						</div>
					`
                }
            }
        };
        Vue.createApp(filesApp).mount('#files-app');
    }
}
