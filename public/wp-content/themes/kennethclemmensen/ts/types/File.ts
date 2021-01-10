/**
 * The File type defines the properties for a file
 */
export type File = {

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
};