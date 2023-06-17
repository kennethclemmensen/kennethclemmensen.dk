package cacheapplication;

import java.util.Collection;
import java.util.HashMap;
import java.util.Map;
import java.util.Set;

/**
 * The AbstractGenericCache class contains
 * methods to manage the data in a cache
 *
 * @author Kenneth
 * @param <K> the key type in the cache
 * @param <V> the value type in the cache
 */
public abstract class AbstractGenericCache<K, V> {

    private Map<K, V> m_cache;
    private int m_maxSize;
    
    /**
     * Create a new AbstractGenericCache
     */
    public AbstractGenericCache() {
        m_cache = new HashMap<>();
        m_maxSize = -1; //no max size
    }
    
    /**
     * Create a new AbstractGenericCache with a fixed size
     * 
     * @param size the size of the cache
     */
    public AbstractGenericCache(int size) {
        m_cache = new HashMap<>();
        m_maxSize = size;
    }

    /**
     * Add a key and a value to the cache
     * 
     * @param key the key to add
     * @param value the value to add
     */
    public void add(K key, V value) {
        if(m_maxSize != -1 && getSize() == m_maxSize)
            throw new IndexOutOfBoundsException();
        m_cache.put(key, value);
    }
    
    /**
     * Add all entries from a map to the cache
     * 
     * @param map the map to get the entries from
     */
    public void addAll(Map<K, V> map) {
        boolean exceedsCacheSize = m_maxSize < (getSize() + map.size());
        if(m_maxSize != -1 && exceedsCacheSize)
            throw new IndexOutOfBoundsException();
        m_cache.putAll(map);
    }
    
    /**
     * Remove a key and the value from the cache
     * 
     * @param key the key to remove
     */
    public void remove(K key) {
        m_cache.remove(key);
    }
    
    /**
     * Check if the cache contains the key
     * 
     * @param key the key to check
     * @return true if the cache contains the key. False otherwise
     */
    public boolean containsKey(K key) {
        return m_cache.containsKey(key);
    }
    
    /**
     * Check if the cache is empty
     * 
     * @return true if the cache is empty. False otherwise
     */
    public boolean isEmpty() {
        return m_cache.isEmpty();
    }

    /**
     * Get a value based on the key
     * 
     * @param key the key to get the value from
     * @return the value
     */
    public V getValue(K key) {
        return m_cache.get(key);
    }
    
    /**
     * Get the size of the cache
     * 
     * @return the size of the cache
     */
    public int getSize() {
        return m_cache.size();
    }
    
    /**
     * Get the entire cache
     * 
     * @return the entire cache
     */
    public Map<K, V> getCache() {
        return m_cache;
    }
    
    /**
     * Get all the keys in the cache
     * 
     * @return the keys
     */
    public Set<K> getKeys() {
        return m_cache.keySet();
    }
    
    /**
     * Get all the values in the cache
     * 
     * @return the values
     */
    public Collection<V> getValues() {
        return m_cache.values();
    }
    
    /**
     * Clear the cache
     */
    public void clear() {
        m_cache.clear();
    }
    
    /**
     * Update the cache
     */
    protected abstract void updateCache();
}