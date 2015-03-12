/**
 * Represents a generic object used for the implementation of various data structures that need only point to one other object.
 * 
 * @author  Jourdan Bul-lalayao <jpbullalayao@gmail.com>
 * @version 1.0
 * @since   2015-03-11
 */
public class Node {
	
  /**
   * Holds basic integer type.
   */
  public int data;
	
  /**
   * Object reference to null or another Node object.
   */
  public Node next;

  /**
   * Initializes a new node object.
   * 
   * @param data Integer that initializing node will hold
   */
  public Node(int data) {
    this.data = data;
    next = null;
  }
}
