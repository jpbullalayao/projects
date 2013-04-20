/* Author: 		   Jourdan Bul-lalayao
 * Class: 		   MergeSort
 * Purpose: 	   Performs merge sort on a Comparable array between specified indices.
 * 				   User also has option to sort the array in reverse order.
 * Helper methods: Merge
 */

public class MergeSort {
	
	/* Method: 		mergeSort
	 * Arguments:	array, lowindex, highindex, reversed
	 */
	public void mergeSort(Comparable[] array, int lowindex, int highindex,
			boolean reversed) {
		
		int mid = (lowindex + highindex) / 2;
		
		if (lowindex >= highindex)
			return;
	
		mergeSort(array, lowindex, mid, reversed);
		mergeSort(array, mid + 1, highindex, reversed);		
		Merge(array, lowindex, mid, highindex, reversed);
	}
	
	
	/* Method:		Merge
	 * Purpose:		Merges each half of array together
	 * Arguments:	array, lowindex, midindex, highindex, reversed
	 */
	private void Merge(Comparable[] array, int lowindex, int midindex, int highindex,
			boolean reversed) {
	
		int i, j, n;
		int mid = midindex + 1;
		n = 0;
		i = lowindex;
		j = mid;
		
		int size = highindex - lowindex + 1;
		Comparable[] temp = new Comparable[size];
	
		while (i < mid && j <= highindex) {
			if (!reversed) { // Don't reverse the array
				if (array[i].compareTo(array[j]) < 0) { // A[i] < B[i]
					temp[n] = array[i];
					i++; n++;
				}
	
				else {
					temp[n] = array[j];
					j++; n++;
				}
			}
			
			else { // Reverse the array
				if (array[i].compareTo(array[j]) > 0) { // A[i] > B[i]
					temp[n] = array[i];
					i++; n++;
				}
	
				else {
					temp[n] = array[j];
					j++; n++;
				}				
			}
		}
		
		if (i == mid) { // Merge rest of B into temp
			while (j <= highindex) {
				temp[n] = array[j];
				j++; n++;
			}
		}
		
		else { // Merge rest of A into temp
			while (i < mid) {
				temp[n] = array[i];
				i++; n++;
			}
		}
	
		// Copy elements from temp array into original array
		for (int k = lowindex; k <= highindex; k++)
			array[k] = temp[k - lowindex];
	}
}