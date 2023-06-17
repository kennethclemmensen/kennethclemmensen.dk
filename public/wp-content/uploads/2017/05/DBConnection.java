package dalc;

import com.microsoft.sqlserver.jdbc.SQLServerDataSource;
import com.mysql.jdbc.jdbc2.optional.MysqlDataSource;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.sql.SQLException;
import java.util.Properties;

/**
 * The DBConnection class can connect to a database
 * depending of the values in a properties class
 *
 * @author Kenneth
 */
public class DBConnection {

    private static Connection m_connection; //the connection to the database
    private static String m_server; //the server
    private static String m_database; //the database
    private static int m_port; //the port number
    private static String m_user; //the user
    private static String m_password; //the users password

    /**
     * Set the different properties to the database connection
     * and return it
     * 
     * @return the database connection
     * @throws SQLException if the connection fails
     * @throws FileNotFoundException if the properties file is not found
     * @throws IOException if the properties is not readable
     */
    public static Connection getConnection() throws SQLException, FileNotFoundException, IOException {
        setConfig();
        //SQL Server
        SQLServerDataSource db = new SQLServerDataSource();
        db.setApplicationName("jdbc:sqlserver://");
        
        //MySql
        //MysqlDataSource db = new MysqlDataSource();
        db.setServerName(m_server);
        db.setDatabaseName(m_database);
        db.setPortNumber(m_port);
        db.setUser(m_user);
        db.setPassword(m_password);
        m_connection = db.getConnection();
        return m_connection;
    }
    
    /**
     * Set the properties from the properties file
     * 
     * @throws IOException if the properties is not readable
     */
    private static void setConfig() throws IOException {
		Properties properties = new Properties();
        properties.load(new FileReader("properties/db_connection.properties"));
        m_server = properties.getProperty("server");
        m_database = properties.getProperty("database");
        m_port = Integer.parseInt(properties.getProperty("portNo"));
        m_user = properties.getProperty("user");
        m_password = properties.getProperty("password");
    }
    
    /**
     * Close the database connection
     * 
     * @throws SQLException if the connection can't be closed
     */
    public static void closeConnection() throws SQLException {
        m_connection.close();
    }
}