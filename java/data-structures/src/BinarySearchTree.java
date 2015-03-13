/**
 * Represents a manual implementation of the binary search tree data structure.
 * 
 * @author  Jourdan Bul-lalayao <jpbullalayao@gmail.com>
 * @version 1.0
 * @since   2015-03-12
 */
public class BinarySearchTree {

  /**
   * Object reference to root of binary search tree.
   */
  private BSTNode root;

  /**
   * Initializes binary search tree with a null root.
   */
  public BinarySearchTree() {
    root = null;
  }

  /**
   * Calls recursive function to insert a new node into the binary search tree.
   * 
   * @param data Integer that new node will hold
   */
  public void insert(int data) {    
    root = insert(data, root);
  }

  /**
   * Recursive function that inserts a new node into the binary search tree.
   * 
   * @param  data Integer that new node will hold
   * @param  node Current node reference 
   * @return Binary search tree with new node
   */
  private BSTNode insert(int data, BSTNode node) {
    if (node == null) {
      return new BSTNode(data);
    }
    if (data <= node.getData()) {
      node.setLeft(insert(data, node.getLeft()));
    } else {
      node.setRight(insert(data, node.getRight()));
    }
    return node;
  }
  
  /**
   * Calls recursive function to check if a number exists in the binary search tree.
   * 
   * @param  data Integer to find in binary search tree
   * @return true if integer was found, false if not
   */
  public boolean find(int data) {
    return find(data, root);
  }
  
  /**
   * Recursive function that checks if a number exists in the binary search tree.
   * 
   * @param  data Integer to find in binary search tree
   * @param  node Current node reference
   * @return true if integer was found, false if not
   */
  private boolean find(int data, BSTNode node) {
    if (node == null) {
      return false;
    }
    if (data == node.getData()) {
      return true;
    } else {
      if (data < node.getData()) {
        return find(data, node.getLeft());
      } else {
        return find(data, node.getRight());
      }
    }
  }
  
  /**
   * Calls recursive function that performs preorder traversal on the binary search tree.
   * 
   * @return String-formatted data of the binary search tree
   */
  public String preorderTraverse() {
    String result = "Preorder: ";
    return preorderTraverse(root, result);
  }

  /**
   * Recursive function that performs preorder traversal on the binary search tree.
   * 
   * @return String-formatted data of the binary search tree
   */
  private String preorderTraverse(BSTNode node, String result) {
    if (node != null) {
      result += node.getData() + " ";
      result = preorderTraverse(node.getLeft(), result);
      result = preorderTraverse(node.getRight(), result);
    }
    return result;
  }

  /**
   * Calls recursive function that performs inorder traversal on the binary search tree.
   * 
   * @return String-formatted data of the binary search tree
   */
  public String inorderTraverse() {
    String result = "Inorder: ";
    return inorderTraverse(root, result);
  }

  /**
   * Recursive function that performs inorder traversal on the binary search tree.
   * 
   * @return String-formatted data of the binary search tree
   */
  private String inorderTraverse(BSTNode node, String result) {
    if (node != null) {
      result = inorderTraverse(node.getLeft(), result);
      result += node.getData() + " ";
      result = inorderTraverse(node.getRight(), result);
    }
    return result;
  }

  /**
   * Calls recursive function that performs postorder traversal on the binary search tree.
   * 
   * @return String-formatted data of the binary search tree
   */
  public String postorderTraverse() {
    String result = "Postorder: ";
    return postorderTraverse(root, result);
  }

  /**
   * Recursive function that performs postorder traversal on the binary search tree.
   * 
   * @return String-formatted data of the binary search tree
   */
  private String postorderTraverse(BSTNode node, String result) {
    if (node != null) {
      result = postorderTraverse(node.getLeft(), result);
      result = postorderTraverse(node.getRight(), result);
      result += node.getData() + " ";
    }
    return result;
  }
}
