/**
 * Represents a manual implementation of Java's "Stack" class, which is a last-in-first-out data structure.
 * 
 * @author  Jourdan Bul-lalayao <jpbullalayao@gmail.com>
 * @version 1.0
 * @since   2015-03-11
 */

public class Stack {

  /**
   * Object reference to last Node pushed onto the stack.
   */
  public Node top;	

  /**
   * Initializes a new stack object beginning with a null reference.
   */
  public Stack() {
    top = null;
  }

  /**
   * Inserts a new node onto the front (top) of the stack.
   * 
   * @param data Integer that new node will hold
   */
  public void push(int data) {
    Node node = new Node (data);
    node.next = top;
    top = node;
  }

  /**
   * Removes the node from front (top) of the stack.
   * 
   * @return Null or top Node
   */
  public Node pop() {
    if (top == null) {
      return null;
    } else {
      Node node = top;
      top = node.next;
      return node;
    }
  }

  /**
   * Returns data held by top node of the stack.
   * 
   * @return -1 or data on top of stack
   */
  public int peek() {
    if (top == null) {
      return -1;
    } else {
      return top.data;
    }
  }
  
  /**
   * 
   * @param  data Number to be searched in stack
   * @return -1 or distance from top (top returns 1)
   */
  public int search(int data) {
    int distance = 1;
    Node node = top;
    
    while (node != null) {
      if (node.data == data) {
        return distance;
      }
      distance++;
      node = node.next;
    }
    
    return -1; // Could not find specified data
  }
  
  /**
   * Checks if stack is empty.
   * 
   * @return true if empty, false if not empty
   */
  public boolean isEmpty() {
    return top == null;
  }
  
  @Override
  public String toString() {
    Node node = top;
    String result = "";

    while (node != null) {
      result += node.data + " ";
      node = node.next;
    }

    result += "\n";
    return result;
  }
}
