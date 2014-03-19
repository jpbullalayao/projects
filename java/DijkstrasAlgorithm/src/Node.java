/* Author: Jourdan Bul-lalayao
 * Class:  Node.java
 * Purpose: Sub-data structure used in Hash Table.
 */

public class Node {

	private String city;
	private int vertex;
	private Node next;
	
	public Node(String city, int vertex, Node next) {
		this.city = city;
		this.vertex = vertex;
		this.next = next;
	}
	
	public void setCity(String city) {
		this.city = city;
	}
	
	public void setVertex(int vertex) {
		this.vertex = vertex;
	}
	
	public void setNext(Node next) {
		this.next = next;
	}
	
	public String getCity() {
		return city;
	}
	
	public int getVertex() {
		return vertex;
	}
	
	public Node getNext() {
		return next;
	}
}
