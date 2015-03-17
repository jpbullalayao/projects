/**
 * API that provides several sorting algorithms that can be used on integer arrays.
 * 
 * @author  Jourdan Bul-lalayao <jpbullalayao@gmail.com>
 * @version 1.0
 * @since   2015-03-16
 */

package sorting;

public class Sorts {
 
  /**
   * Performs bubble sort on the given array using bitwise XOR operations to swap elements. 
   * 
   * @param array Basic integer array
   */
  public static void bubbleSort(int[] array) {
    if (array == null) {
      return;
    }
    
    int last = array.length - 1;
    
    for (int i = last; i > 0; i--) {
      for (int j = 0; j < i; j++) {
        if (array[j] > array[j + 1]) {
          array[j] ^= array[j + 1];
          array[j + 1] ^= array[j];
          array[j] ^= array[j + 1];
        }
      }
    }
  }
  
  /**
   * Performs selection sort on the given array. 
   * 
   * @param array Basic integer array
   */
  public static void selectionSort(int[] array) {
    if (array == null) {
      return;
    }
    
    for (int i = 0; i < array.length; i++) {
      int min = array[i];
      int swapIndex = i;
      
      for (int j = i + 1; j < array.length; j++) {
        if (min > array[j]) {
          min = array[j];
          swapIndex = j;
        }
      }
      
      int temp = array[i];
      array[i] = min;
      array[swapIndex] = temp;
    }
  }
  
  public static void main(String[] args) {
    int[] array = new int[] {5, 3, 1, 8, 9, 7, 6, 2, 0, 4};
    
    selectionSort(array);
    for (int i : array) {
      System.out.println(i);
    }
  }
}
