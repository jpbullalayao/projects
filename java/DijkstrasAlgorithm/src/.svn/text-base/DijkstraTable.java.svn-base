
public class DijkstraTable {

	private Vertex[] vertices;
	private BinomialQueue queue;
	private AdjacencyList graph;
	private String path;
	
	public DijkstraTable(int length, AdjacencyList graph) {
		vertices   = new Vertex[length];
		queue      = new BinomialQueue(vertices.length);
		this.graph = graph;
	}
	
	
	public void Dijkstra(int start) {
		
		/* Creates Dijkstra Table */
		for (int i = 0; i < vertices.length; i++) {
			Vertex vertex;
			if (i == start) {
				vertex = new Vertex(0, -1, false);
			} else {
				vertex = new Vertex(Integer.MAX_VALUE, -1, false);	
			}
			vertices[i] = vertex;
		}
		vertices[start].setDistance(0);
		
		queue.insert(start, 0);
		
		/* Creates Binomial Queue */
		for (int vertex = 0; vertex < vertices.length; vertex++) {	
			if (vertex == start) {
				continue;
			} else {
				int distance = vertices[vertex].getDistance();
				queue.insert(vertex, distance);
			}
		}

		while (queue.head != null) {
			int vertex = queue.remove_min();
			
			/* Iterate through edges neighbors */
			for (Edge edge = graph.getEdge(vertex); edge != null; edge = edge.getNext()) {
				if (vertices[edge.getNeighbor()].getDistance() > vertices[vertex].getDistance() + edge.getCost()) {
					int newDistance = vertices[vertex].getDistance() + edge.getCost();
					vertices[edge.getNeighbor()].setDistance(newDistance);
					queue.reduce_key(edge.getNeighbor(), newDistance);
					vertices[edge.getNeighbor()].setPath(vertex);
				}
			}
		}
	}
	
	
	public void printTable() {
		
		for (int i = 0; i < vertices.length; i++) {
			System.out.println(i + ": " + "distance: " + vertices[i].getDistance() + 
							   " path: " + vertices[i].getPath() + 
							   " known: " + vertices[i].getKnown());
		}
	}
	
	
	public void printShortestPaths(int start, String[] cities) {
		
		for (int i = 0; i < cities.length; i++) {
			Path head = new Path(cities[i], null);
			System.out.print(cities[i] + "  " + vertices[i].getDistance() + ": ");
		
			int tmp = i;
			while (tmp != start) {
				tmp = vertices[tmp].getPath();
				Path node = new Path(cities[tmp], head);
				head = node;
			}
			
			/* Print out path */
			Path path = head;
			while (path != null) {
				System.out.print(path.getCity() + " ");
				path = path.getNext();
			}
			System.out.println();
		}
	}
}
