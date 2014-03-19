/* Author: Jourdan Bul-lalayao
 * Class:  Vertex.java
 * Purpose: Sub-data structure used for Dijkstra Table
 */

public class Vertex {
	
	private int distance;
	private int path;
	private boolean known;
	
	public Vertex(int distance, int path, boolean known) {
		this.distance = distance;
		this.path 	  = path;
		this.known    = known;
	}
	
	public void setDistance(int distance) {
		this.distance = distance;
	}
	
	public void setPath(int path) {
		this.path = path;
	}
	
	public void setKnown(boolean known) {
		this.known = known;
	}
	
	public int getDistance() {
		return distance;
	}
	
	public int getPath() {
		return path;
	}
	
	public boolean getKnown() {
		return known;
	}
}
