/* Author:         Jourdan Bul-lalayao
 * Class: 	   OptimizedQuickSort
 * Purpose: 	   Performs selection sort on a Comparable array between specified indices.
 * 		   User also has option to sort the array in reverse order.
 */

public class SelectionSort {
	
	/* Method:	selectionSort
	 * Arguments: 	array, lowindex, highindex, reversed
	 */
	public void selectionSort(Comparable[] array, int lowindex, int highindex,
			boolean reversed) {
		
		int i, j;
		Comparable min;
		int swapindex;
		
		if (!reversed) { // Don't reverse the array
			for (i = lowindex; i <= highindex; i++) {
				min = array[i];
				swapindex = i;
				for (j = i + 1; j <= highindex; j++) {
					if (array[j].compareTo(min) < 0) {
						min = array[j];
						swapindex = j;
					}
				}
				array[swapindex] = array[i];
				array[i] = min;
			}
		}
		
		else { // Reverse the array
			for (i = lowindex; i <= highindex; i++) {
				min = array[i];
				swapindex = i;
				for (j = i + 1; j <= highindex; j++) {
					if (array[j].compareTo(min) > 0) {
						min = array[j];
						swapindex = j;
					}
				}
				array[swapindex] = array[i];
				array[i] = min;
			}			
		}
	}
}
