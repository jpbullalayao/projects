/* Author: 	Jourdan Bul-lalayao
 * Class: 	InsertionSort
 * Purpose: 	Performs insertion sort on a Comparable array between specified indices.
 * 		User also has option to sort the array in reverse order.
 */

public class InsertionSort {

	/* Method:    insertionSort 
	 * Arguments: array, lowindex, highindex, reversed 
	 * */
	public void insertionSort(Comparable[] array, int lowindex, int highindex,
			boolean reversed) {
		
		int i, j;
		Comparable curr;
		
		if (!reversed) { // Don't reverse the array
			for (i = lowindex + 1; i <= highindex; i++) {
				curr = array[i];
				for (j = i - 1; j >= lowindex && array[j].compareTo(curr) > 0; j--)
					array[j + 1] = array[j];
				array[j + 1] = curr;
			}
		}
		
		else { // Reverse the array
			for (i = lowindex + 1; i <= highindex; i++) {
				curr = array[i];
				for (j = i - 1; j >= lowindex && array[j].compareTo(curr) < 0; j--)
					array[j + 1] = array[j];
				array[j + 1] = curr;
			}
		}
	}
}


