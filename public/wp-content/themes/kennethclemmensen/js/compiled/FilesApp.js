import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { Url } from './enums/Url';
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
        let elementId = 'files-app';
        new Vue({
            el: '#' + elementId,
            data: {
                files: []
            },
            created: function () {
                let element = document.getElementById(elementId);
                if (element) {
                    let xhr = new XMLHttpRequest();
                    xhr.open(HttpMethod.Get, Url.ApiFiles + element.dataset.type, true);
                    xhr.addEventListener(EventType.Load, () => {
                        this.files = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
                    });
                    xhr.addEventListener(EventType.Error, () => {
                        this.files = [];
                    });
                    xhr.send();
                }
                else {
                    this.files = [];
                }
            },
            components: {
                'files': {
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
                        },
                        updateFileDownloads: (file) => {
                            let xhr = new XMLHttpRequest();
                            xhr.open(HttpMethod.Put, Url.ApiFileDownloads + file.id, true);
                            xhr.addEventListener(EventType.Load, () => {
                                if (xhr.status === HttpStatusCode.Ok)
                                    file.downloads++;
                            });
                            xhr.send();
                        }
                    },
                    props: {
                        files: {
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
        });
    }
}
