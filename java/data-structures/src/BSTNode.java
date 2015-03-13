/**
 * Represents a generic object used to represent each node in a binary search tree.
 * 
 * @author  Jourdan Bul-lalayao <jpbullalayao@gmail.com>
 * @version 1.0
 * @since   2015-03-12
 */
public class BSTNode {

  /**
   * Holds basic integer type.
   */
  private int data; 
  
  /**
   * Object reference to left child.
   */
  private BSTNode left;
  
  /**
   * Object reference to right child.
   */
  private BSTNode right;

  /**
   * Initializes a new node in the binary search tree.
   * 
   * @param data Integer that initializing node will hold
   */
  public BSTNode(int data) {
    this.data = data;
    left = null;
    right = null;
  }

  public int getData() {
    return data;
  }

  public void setData(int data) {
    this.data = data;
  }

  public BSTNode getLeft() {
    return left;
  }

  public void setLeft(BSTNode left) {
    this.left = left;
  }

  public BSTNode getRight() {
    return right;
  }

  public void setRight(BSTNode right) {
    this.right = right;
  }
}
