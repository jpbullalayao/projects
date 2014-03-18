/* Author: Jourdan Bul-lalayao
 * Class:  HashTable.java
 * Purpose: Hash Table data structure for faster lookups of each vertex/city.
 */

public class HashTable {

	private int size;
	private Node[] nodes;
	
	public HashTable(int size) {
		this.size = size;
		nodes = new Node[size];
	}
	
	public int Hash(String city, int vertex, boolean lookup) {
		
		int h, hashIndex, i;
		h = hashIndex = i = 0;
		
		for (i = 0; i < city.length(); i++)
			h = (h << 4) + (int) city.charAt(i);

		hashIndex = h % size;

		/* Make hash table if we aren't looking up vertices for adjacency list */
		if (lookup != true) {
			Node node = new Node(city, vertex, null);
			node.setNext(nodes[hashIndex]);
			nodes[hashIndex] = node;
		}

		return hashIndex;
	}
	
	public Node getNode(int hash) {
		return nodes[hash];
	}
	
	public int getVertex(String city) {
		
		int vertex = -1;
		int hash   = Hash(city, vertex, true);
		Node node  = getNode(hash);
		
		while (node != null) {
			if (node.getCity().equals(city)) {
				vertex = node.getVertex();
				break;
			} 
			node = node.getNext();
		}
		
		return vertex;
	}
}
