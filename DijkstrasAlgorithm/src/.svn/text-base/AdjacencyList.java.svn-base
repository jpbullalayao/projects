
public class AdjacencyList {

	private Edge[] edges;
	
	public AdjacencyList(int length) {
		edges = new Edge[length];
	}
	
	
	public void makeList(String city, int vertex, int neighbor, int cost) {
		
		Edge edge = new Edge(city, neighbor, cost, null);
		
		if (edges[vertex] == null) {
			edges[vertex] = edge;
		} else {
			Edge temp = edges[vertex];
			
			while (temp != null) {
				if (temp.getNext() == null) { // Only one element in list
					temp.setNext(edge);
					break;
				} else {
					if (temp.getNext().getNeighbor() > neighbor) {
						edge.setNext(temp.getNext());
						temp.setNext(edge);
						break;
					}
				}
				temp = temp.getNext();
			}
		}
	}
	
	
	public Edge getEdge(int vertex) {
		return edges[vertex];
	}
	
	
	public void printList(String city, int vertex) {
		
		System.out.print(city + ": ");

		Edge tmp = edges[vertex];
		while (tmp != null) {
			System.out.print(tmp.getCity() + " " + tmp.getCost());
			tmp = tmp.getNext();
		
			if (tmp != null)
				System.out.print(", ");
		}
		
		System.out.println();
	}
}
