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
	 * Removes node from front (top) of the stack.
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
