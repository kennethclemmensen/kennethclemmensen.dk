package dal;

import java.io.BufferedReader;
import java.io.FileReader;

/**
 * The FileHelper class contains a method 
 * to get the content from a file
 *
 * @author Kenneth
 */
public class FileHelper {

    /**
     * Get the content from a file
     *
     * @param path the path to the file
     * @return the content of the file
     * @throws Exception
     */
    public static StringBuilder getContentFromFile(String path) throws Exception {
        BufferedReader reader = null;
        try {
            StringBuilder res = new StringBuilder();
            reader = new BufferedReader(new FileReader(path));
            String line = reader.readLine();
            while(line != null) {
                res.append(line + "\n");
                line = reader.readLine();
            }
            reader.close();
            return res;
        } catch(Exception ex) {
            throw ex;
        } finally {
            if(reader != null) reader.close();
        }
    }
}