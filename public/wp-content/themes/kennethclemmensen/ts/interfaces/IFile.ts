/**
 * The IFile interface defines the properties for a file
 */
export interface IFile {

	/**
	 * The file id
	 */
	id: number;

	/**
	 * The file name
	 */
	fileName: string;

	/**
	 * The url to the file
	 */
	url: string;

	/**
	 * The file description
	 */
	description: string;

	/**
	 * The number of downloads
	 */
	downloads: number;
}