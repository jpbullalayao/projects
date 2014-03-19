/* Author: Jourdan Bul-lalayao
 * Class:  BinomialQueue.java
 * Purpose: Implements priority queue for cities based on distance to be used in Dijkstra's Algorithm. 
 */

public class BinomialQueue {

	private QueueNode[] queue;
	public  QueueNode head;
	private QueueNode subTree;
	
	public BinomialQueue(int length) {
		queue   = new QueueNode[length];
		head    = null;
		subTree = null;
	}
	
	
	/* Inserts the integer vertex into the queue, using the priority priority */
	public void insert(int vertex, int priority) {
		
		QueueNode node = new QueueNode(vertex, 0, priority, null, null, null);

		if (head == null) {
			head = node;
		} else {
			node.setRightSib(head);
			head = node;
			QueueNode tmp = head;
			
			while (tmp != null) {
				if (tmp.getRightSib() != null ) {
					if (tmp.getDegree() == tmp.getRightSib().getDegree()) {
						tmp = Merge(tmp, tmp.getRightSib()); /* Merges trees together */
						head = tmp;
					} else {
						tmp = tmp.getRightSib();
					}
				} else {
					break;
				}
			}
		}
		
		/* Sets Binomial Queue array of pointers */
		queue[vertex] = node;
	}
	
	private QueueNode Merge(QueueNode node1, QueueNode node2) {
		
		QueueNode tmp;
		
		if (node1.getDistance() < node2.getDistance()) { /* Node 1 is parent */
			node2.setRightSib(node1.getLeftChild());
			node1.setLeftChild(node2);
			node2.setParent(node1);
			tmp = node1;
		} else { /* Node 2 is parent */
			node1.setRightSib(node2.getLeftChild());
			node2.setLeftChild(node1);
			node1.setParent(node2);
			tmp = node2;
		}
		
		/* Increments degree of root */
		tmp.incrementDegree();
		return tmp;
	}
	
	
	/* Removes the element with the smallest priority from the queue */ 
	public int remove_min() {
		
		/* Only one node left to remove */
		if (head.getLeftChild() == null && head.getRightSib() == null) {
			int min = head.getVertex();
			queue[min] = null;
			head = null;
			return min;
		}
		
		
		/* Min is root of tree */
		if (head.getLeftChild() != null && head.getRightSib() == null) { 
			int min = head.getVertex();
			queue[min] = null;
			QueueNode tmp = head.getLeftChild();
			subTree = tmp;
			head = null;
			tmp.setParent(null);
			
			while (tmp.getRightSib() != null) {
				Reverse(tmp, tmp.getRightSib());
				tmp.setParent(null);
			}
			
			head = subTree;
			return min;
		}		
		
		QueueNode minNode = head;
		QueueNode tmp = head.getRightSib();
		
		
		/* Find vertex with smallest cost */
		while (tmp != null) {
			if (tmp.getDistance() < minNode.getDistance())
				minNode = tmp;
			tmp = tmp.getRightSib();
		}
		
		
		/* Removes min node from Binomial Queue */
		int min = minNode.getVertex();
		queue[min] = null;
		tmp = head;
		
		
		/* Removes min at head and head does not have a leftChild, but has a rightSib */
		if (min == head.getVertex() && head.getLeftChild() == null) {
			head = head.getRightSib();
			return min;
		}
		
		/* Removes min at head and head has a leftChild() and a rightSib() */
		if (min == head.getVertex() && head.getLeftChild() != null) {
			head.getLeftChild().setRightSib(head.getRightSib());
			head = head.getLeftChild();
			head.setParent(null);
			return min;
		}

		
		/* Takes pointer to min tree off the head */
		while (tmp.getRightSib() != null) {
			if (tmp.getRightSib().getVertex() != min) {
				tmp = tmp.getRightSib();
			} else {
				break;
			}
		}
		tmp.setRightSib(null);
		
		
		/* Reverse subtrees */
		tmp = minNode.getLeftChild();
		subTree = tmp;
		
		if (tmp != null) { // debug remove_min
			tmp.setParent(null);
			
			while (tmp.getRightSib() != null) {
				Reverse(tmp, tmp.getRightSib()); /* Reverse subTree */
				tmp.setParent(null);
			}
			
			/* Reorders trees based on degrees */
			Reorder(head, subTree);
			
			/* Merge reordered tree together */
			tmp = head;
			QueueNode tmp2 = head;
	
			while (tmp != null) {
				if (tmp.getRightSib() != null ) {
					if (tmp.getDegree() == tmp.getRightSib().getDegree()) {
						tmp = Merge(tmp, tmp.getRightSib()); /* Merges trees together */
						tmp2.setRightSib(tmp);
					} else {
						tmp2 = tmp;
						tmp = tmp.getRightSib();
					}
				} else {
					break;
				}
			}
		}//
		
		subTree = null;
		return min;
	}
	
	
	private void Reverse(QueueNode node1, QueueNode node2) {

		node1.setRightSib(node2.getRightSib());
		node2.setRightSib(subTree);
		subTree = node2;
	}
	
	
	private void Reorder(QueueNode node1, QueueNode node2) {
		
		QueueNode tmp = node1;
		
		while (tmp != null) {
			if (node2.getDegree() <= tmp.getDegree()) {
				QueueNode tmp2 = tmp.getRightSib();
				tmp.setRightSib(node2.getRightSib());
				node2.setRightSib(tmp);
				tmp = tmp2;
			} else {
				break;
			}
		}
		head = node2;
	}
	
	
	/* Reduce the priority of the element elem in the priority queue to new_priority,
	 * rearranging the queue as necessary. To implement this method efficiently, you will need
	 * to keep track of where each element is in the queue. Your best bet is probably an array of
	 * pointers into the queue.
	 */
	public void reduce_key(int vertex, int new_priority) {
		
		queue[vertex].setDistance(new_priority);
		
		QueueNode tmp = queue[vertex];
		int tmpDistance, tmpVertex;
		tmpDistance = tmpVertex = 0;
		
		while (tmp.getParent() != null) {
			/* Swaps values instead of nodes */
			if (tmp.getParent().getDistance() > new_priority) {
				tmpVertex   = vertex;
				tmpDistance = new_priority;
				tmp.setVertex(tmp.getParent().getVertex());
				tmp.setDistance(tmp.getParent().getDistance());
				queue[tmp.getVertex()] = tmp; // Change pointers
				tmp.getParent().setVertex(tmpVertex);
				tmp.getParent().setDistance(tmpDistance);
				tmp = tmp.getParent();
				queue[vertex] = tmp; // Change pointers
			} else {
				break;
			}
		}
	}

	
	public void printQueueList() {
		
		System.out.println("Queue: ");
		for (int vertex = 0; vertex < queue.length; vertex++) {
			if (queue[vertex] != null) {
				System.out.println(vertex + ": " + queue[vertex].getDistance());
			} else {
				System.out.println(vertex + ": " + queue[vertex]);
			}
			
		}
		System.out.println();
	}
	
	
	public void printHeap(QueueNode node) {
		
		if (node != null) {
			System.out.println(node.getVertex() + ": " + node.getDistance());
			printHeap(node.getLeftChild());
			printHeap(node.getRightSib());
		}
	}
}
