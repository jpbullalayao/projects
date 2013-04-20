/* Author: 	   Jourdan Bul-lalayao
 * Class: 	   OptimizedQuickSort
 * Purpose: 	   Performs quick sort on a Comparable array between specified indices.
 * 		   Once array is small enough, performs insertion sort for better performance.
 * 		   User also has option to sort the array in reverse order.
 * Helper methods: computePivot, Partition
 */

public class OptimizedQuickSort {
	
	/* Method:	optimizedQuickSort
	 * Arguments:	array, lowindex, highindex, reversed
	 */
	public void optimizedQuickSort(Comparable[] array, int lowindex,
			int highindex, boolean reversed) {
	
		int length = highindex - lowindex + 1;
		Comparable first = array[lowindex];
		Comparable middle = array[(highindex + lowindex) / 2];
		Comparable last = array[highindex];
		
		if (length <= 100) {
			InsertionSort sort = new InsertionSort();
			sort.insertionSort(array, lowindex, highindex, reversed);
		}
	
		else {
			Comparable pivot = computePivot(first, middle, last);
			int pivotIndex = Partition(array, pivot, lowindex + 1, highindex, reversed);
			optimizedQuickSort(array, lowindex, pivotIndex - 1, reversed);
			optimizedQuickSort(array, pivotIndex, highindex, reversed);
		}
	}
	
	/* Method:	computePivot
	 * Purpose:	Determine pivot element of array based on first, middle and last index
	 * 		in array.
	 * Arguments:	first, middle, last
	 * Return:	pivot (Comparable)
	 */
	private Comparable computePivot(Comparable first, Comparable middle, Comparable last) {
		
		Comparable[] array = {first, middle, last};
		Comparable temp, pivot = 0;
		
		for (int i = 0; i < 2; i++) {
			if (array[i].compareTo(array[i + 1]) > 0) {
				temp = array[i];
				array[i] = array[i + 1];
				array[i + 1] = temp;
			}
			
			if (i == 1) {
				if (array[i].compareTo(array[i - 1]) < 0) 
					pivot = array[i - 1];
				else
					pivot = array[i];
			}
		}
		return pivot;
	}
	
	
	/* Method:	Partition
	 * Purpose:	Inserts pivot element in correct position by partioning array.
	 * Arguments:	array, pivot, lowindex, highindex, reversed
	 * Return:	j (int)
	 */
	private int Partition(Comparable[] array, Comparable pivot, int lowindex, 
			int highindex, boolean reversed) {
		
		Comparable temp;
		int i = lowindex;
		int j = highindex;
		
		// Swaps pivot element with lowindex
		if (array[lowindex - 1].compareTo(pivot) != 0) {
			for (int k = lowindex; k <= highindex; k++) {
				if (array[k].compareTo(pivot) == 0) {
					temp = array[lowindex - 1];
					array[lowindex - 1] = pivot;
					array[k] = temp;
				}
			}
		}
		
		if (!reversed) {
			while (i < j) {
				if (array[i].compareTo(pivot) > 0) {
					while (array[j].compareTo(pivot) > 0) { // array[j] > pivot
						j--;
					}
					
					if (i <= j) {
						temp = array[i];
						array[i] = array[j];
						array[j] = temp;
					}
	
					else {
						temp = array[j];
						array[j] = pivot;
						array[lowindex - 1] = temp;
					}
				}
				i++;
			}
		}
		
		else { // Reverse array
			while (i < j) {
	
				if (array[i].compareTo(pivot) < 0) {
					while (array[j].compareTo(pivot) < 0) { // array[j] > pivot
						j--;
					}
					
					if (i <= j) {
						temp = array[i];
						array[i] = array[j];
						array[j] = temp;
					}
	
					else {
						temp = array[j];
						array[j] = pivot;
						array[lowindex - 1] = temp;
					}
				}				
				i++;
			}
		}
		return j;
	}
}
