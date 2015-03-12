/**
 * Represents a manual implementation of Java's "Queue" class, which is a first-in-first-out data structure.
 * 
 * @author  Jourdan Bul-lalayao <jpbullalayao@gmail.com>
 * @version 1.0
 * @since   2015-03-11
 */

public class Queue {

  /**
   * Object reference to first Node in queue.
   */
  public Node first;
  
  /**
   * Object reference to last Node in queue.
   */
  public Node last;
  
  /**
   * Initializes a new queue object beginning with null references.
   */
  public Queue() {
    first = null;
    last = null;
  }

  /**
   * Inserts a new node at the end of the queue.
   * 
   * @param data Integer that new node will hold
   */
  public void enqueue(int data) {
    Node node = new Node(data);
    if (first == null) {
      first = node;
    } else {
      last.next = node;
    }
    last = node;
  }

  /**
   * Removes the node from front of the queue.
   * 
   * @return Null or first Node
   */
  public Node dequeue() {
    if (first != null) {
      Node node = first;
      first = first.next;
      if (first == null) {
        last = null;
      }
      return node;
    }
    return null;
  }

  /**
   * Returns data held by first node of the queue.
   * 
   * @return -1 or data from first node
   */
  public int peek() {
    if (first == null) {
      return -1;
    } else {
      return first.data;
    }
  }

  /**
   * Checks if queue is empty.
   * 
   * @return true if empty, false if not empty
   */
  public boolean isEmpty() {
    return first == null;
  }

  @Override
  public String toString() {
    Node node = first;
    String result = ""; 

    while (node != null) {
      result += node.data + " ";
      node = node.next;
    }
  
    result += "\n";
    return result;
  }
}

