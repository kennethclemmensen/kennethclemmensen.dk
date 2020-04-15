import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { Url } from './enums/Url';
import { IFile } from './interfaces/IFile';

/**
 * The FilesApp class contains methods to handle the functionality of the files
 */
export class FilesApp {

    /**
     * Initialize a new instance of the FilesApp class
     */
    public constructor() {
        this.setupFilesApp();
    }

    /**
     * Setup the files app
     */
    private setupFilesApp(): void {
        let elementId: string = 'files-app';
        new Vue({
            el: '#' + elementId,
            data: {
                files: []
            },
            created: function(): void {
                let element: HTMLElement | null = document.getElementById(elementId);
                if(element) {
                    let xhr: XMLHttpRequest = new XMLHttpRequest();
                    xhr.open(HttpMethod.Get, Url.ApiFiles + element.dataset.type, true);
                    xhr.addEventListener(EventType.Load, (): void => {
                        this.files = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
                    });
                    xhr.addEventListener(EventType.Error, (): void => {
                        this.files = [];
                    });
                    xhr.send();
                } else {
                    this.files = [];
                }
            },
            components: {
                'files': {
                    data: (): object => {
                        return {
                            offset: 0,
                            perPage: 7
                        };
                    },
                    methods: {
                        previousPage: function(): void {
                            this.offset -= this.perPage;
                        },
                        nextPage: function(): void {
                            this.offset += this.perPage;
                        },
                        updateFileDownloads: (file: IFile): void => {
                            let xhr: XMLHttpRequest = new XMLHttpRequest();
                            xhr.open(HttpMethod.Put, Url.ApiFileDownloads + file.id, true);
                            xhr.addEventListener(EventType.Load, (): void => {
                                if(xhr.status === HttpStatusCode.Ok) file.downloads++;
                            });
                            xhr.send();
                        }
                    },
                    props: {
                        files: {
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
                        },
                        numberOfDownloadsText: {
                            required: true,
                            type: String
                        }
                    },
                    template: `
                        <div>
                            <div v-for="file in files.slice(offset, (offset + perPage))">
                                <a :href="file.url" @click="updateFileDownloads(file)" rel="nofollow" download>{{ file.fileName }}</a>
                                <p>{{ file.description }}</p>
                                <p>{{ numberOfDownloadsText }} {{ file.downloads }}</p>
                            </div>
                            <div class="pagination">
                                <a href="#" @click.prevent="previousPage" v-if="offset > 0">{{ previousText }}</a>
                                <a href="#" @click.prevent="nextPage" v-if="offset < (files.length - perPage)">{{ nextText }}</a>
                            </div>
                        </div>
                    `
                }
            }
        });
    }
}