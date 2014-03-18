/* Author: Jourdan Bul-lalayao
 * Class:  Dijkstra.java
 * Purpose: Driver class that implements Dijkstra's algorithm (shortest cost path 
 * 			from a city to another)
 * Notes:  Takes one input paramater (text file) that contains cities and costs.
 */

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

public class Dijkstra {

	public String[] cities;
	public HashTable table;
	public BinomialQueue queue;
	public AdjacencyList graph;
	public DijkstraTable dTable;
	
	public Dijkstra() {
		
	}
	
	
	public void getNumCities(String[] args) {
		BufferedReader input;
		String nextLine;
		int count = 0;
		
		try {
			input = new BufferedReader(new FileReader(args[0]));
			nextLine = input.readLine();
			
			while (nextLine.compareTo(".") != 0) {
				count++;
				nextLine = input.readLine();
			}
		} catch (IOException e) {
			System.out.println("File Error");
		}
		
		// City List
		cities = new String[count];
	}
	
	
	public void printCities() {
		for (int vertex = 0; vertex < cities.length; vertex++)
			System.out.println(cities[vertex]);
	}
	
	
	public void readFile(String[] args) {
		BufferedReader input;
		String start, finish, nextLine;
		int cost;
		int vertex = 0;

		try {
			input = new BufferedReader(new FileReader(args[0]));
			nextLine = input.readLine();
			while (nextLine.compareTo(".") != 0) {
				cities[vertex++] = nextLine;
				nextLine = input.readLine();
			}

			// Creates Hash Table
			makeTable();
			
			// Make instance to AdjacencyList
			graph = new AdjacencyList(cities.length);
			
			nextLine = input.readLine(); // Reads '.'
			while (nextLine != null) {
				start    = nextLine;
				nextLine = input.readLine();
				finish   = nextLine;
				nextLine = input.readLine();
				cost     = Integer.valueOf(nextLine).intValue();
				nextLine = input.readLine();
				
				// Creates Adjacency List
				makeList(start, finish, cost);
			}
		} catch (IOException e) {
			System.out.println("File Error");
		}
	}
	
	
	private void makeTable() {
		// Make instance to HashTable
		table = new HashTable(cities.length);
		
		for (int vertex = 0; vertex < cities.length; vertex++) {
			table.Hash(cities[vertex], vertex, false);
		}
	}
	
	
	private void makeList(String start, String finish, int cost) {
		int vertex 	 = table.getVertex(start);
		int neighbor = table.getVertex(finish);
		
		graph.makeList(finish, vertex, neighbor, cost);
	}
	
	
	public void printList() {
		System.out.println("Original Graph");
		
		for (int vertex = 0; vertex < cities.length; vertex++) {
			graph.printList(cities[vertex], vertex);
		}
		
		System.out.println();
	}
	
	
	public void Dijkstra(int start) {
		// Make instance to DijkstraTable
		dTable = new DijkstraTable(cities.length, graph);
		
		dTable.Dijkstra(start);
	}

	
	public void printDTable() {
		
		dTable.printTable();
	}
	
	
	public void printPaths(int start) {
		
		System.out.println("Shortest Paths\n");
		dTable.printShortestPaths(start, cities);
	}
	
	
	public static void main(String[] args) {
	
		Dijkstra d = new Dijkstra();
		int start = 0;
		
		if (args.length != 1) {
			System.out.println("Please specify 1 input file argument.");
		} else {
			d.getNumCities(args);
			d.readFile(args);
			d.printList();
			d.Dijkstra(start);
			d.printPaths(start);
		}
	}
}
