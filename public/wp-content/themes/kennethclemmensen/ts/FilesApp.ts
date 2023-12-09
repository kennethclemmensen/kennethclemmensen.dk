import { Observable, map } from 'rxjs';
import { AjaxResponse, ajax } from 'rxjs/ajax';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { File } from './types/File';

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
		Vue.createApp({
			components: {
				'files': {
					data: (): object => {
						return {
							files: [],
							offset: 0,
							perPage: 7
						};
					},
					created: function(): void {
						const files$: Observable<unknown> = ajax({
							url: '/wp-json/kcapi/v1/files?type=' + this.fileTypes,
							method: HttpMethod.Get,
							responseType: 'text'
						}).pipe(
							map((response: AjaxResponse<unknown>): void => {
								this.files = (response.status === HttpStatusCode.Ok) ? JSON.parse(response.xhr.responseText) : [];
							})
						);
						files$.subscribe();
					},
					methods: {
						previousPage: function(): void {
							this.offset -= parseInt(this.perPage);
						},
						nextPage: function(): void {
							this.offset += parseInt(this.perPage);
						},
						updateFileDownloads: (file: File): void => {
							/*const xhr: XMLHttpRequest = new XMLHttpRequest();
							const load$ = fromEvent(xhr, EventType.Load);
							xhr.open(HttpMethod.Put, '/wp-json/kcapi/v1/fileDownloads?fileid=' + file.id, true);
							load$.subscribe((): void => {
								if(xhr.status === HttpStatusCode.Ok) file.downloads++;
							});
							xhr.send();*/
							const downloads$: Observable<unknown> = ajax({
								url: '/wp-json/kcapi/v1/fileDownloads?fileid=' + file.id,
								method: HttpMethod.Put,
								responseType: 'text'
							}).pipe(
								map((response: AjaxResponse<unknown>): void => {
									if(response.status === HttpStatusCode.Ok) file.downloads++;
								})
							);
							downloads$.subscribe();
						}
					},
					props: {
						fileTypes: {
							required: true,
							type: String
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
		}).mount('#files-app');
	}
}