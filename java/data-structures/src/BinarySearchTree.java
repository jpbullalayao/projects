public class BinarySearchTree {

  private BSTNode root;

  public BinarySearchTree() {
    root = null;
  }

  public void insert(int data) {    
    root = insert(data, root);
  }

  public BSTNode insert(int data, BSTNode node) {
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

  public void delete(int data) {
    root = delete(data, root);
  }
  
  public BSTNode delete(int data, BSTNode node) {
    return null;
  }
  
  public boolean find(int data) {
    return find(data, root);
  }
  
  public boolean find(int data, BSTNode node) {
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
  
  public String preorderTraverse() {
    String result = "Preorder: ";
    return preorderTraverse(root, result);
  }

  public String preorderTraverse(BSTNode node, String result) {
    if (node != null) {
      result += node.getData() + " ";
      result = preorderTraverse(node.getLeft(), result);
      result = preorderTraverse(node.getRight(), result);
    }
    return result;
  }

  public String inorderTraverse() {
    String result = "Inorder: ";
    return inorderTraverse(root, result);
  }

  public String inorderTraverse(BSTNode node, String result) {
    if (node != null) {
      result = inorderTraverse(node.getLeft(), result);
      result += node.getData() + " ";
      result = inorderTraverse(node.getRight(), result);
    }
    return result;
  }

  public String postorderTraverse() {
    String result = "Postorder: ";
    return postorderTraverse(root, result);
  }

  public String postorderTraverse(BSTNode node, String result) {
    if (node != null) {
      result = postorderTraverse(node.getLeft(), result);
      result = postorderTraverse(node.getRight(), result);
      result += node.getData() + " ";
    }
    return result;
  }
  
  public static void main(String[] args) {
    BinarySearchTree b = new BinarySearchTree();
    
    b.insert(4);
    b.insert(3);
    b.insert(1);
    b.insert(2);
    b.insert(8);
    b.insert(6);
    b.insert(5);
    b.insert(9);
    
    System.out.println(b.preorderTraverse());
    System.out.println(b.inorderTraverse());
    System.out.println(b.postorderTraverse());
    System.out.println(b.find(4));
    System.out.println(b.find(100));
    System.out.println(b.find(2));
    System.out.println(b.find(9));
    System.out.println(b.find(5));
  }
}
