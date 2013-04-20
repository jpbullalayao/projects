/* Author: 		   Jourdan Bul-lalayao
 * Class: 		   HeapSort
 * Purpose: 	   Performs heap sort on a Comparable array between specified indices
 * 				   User also has option to sort the array in reverse order.
 * Helper methods: maxIndex				  
 */

public class HeapSort {
	
	/* Method:		heapSort
	 * Arguments:	array, lowindex, highindex, reversed
	 */
	public void heapSort(Comparable[] array, int lowindex, int highindex,
				boolean reversed) {
			
		Comparable temp;
		int sib, maxIndex, parent;
		
		if (highindex == 1)
			return;
		
		for (int i = highindex; i > lowindex; i = i - 2) {
			sib = i - 1;
			maxIndex = maxIndex(array, i, sib, reversed);
			parent = (maxIndex - 1) / 2;
			maxIndex = maxIndex(array, maxIndex, parent, reversed);
			
			if (maxIndex != parent) { // Switch parent and sibling
				temp = array[parent];
				array[parent] = array[maxIndex];
				array[maxIndex] = temp;				
			}
		}			
		
		// Swap highest element to the end
		temp = array[lowindex];
		array[lowindex] = array[highindex];
		array[highindex] = temp;
		
		heapSort(array, lowindex, highindex - 1, reversed);		
	}
	
	/* Method: 		maxIndex
	 * Arguments:	array, i, sib, reversed
	 * Purpose:     Finds max or min index for HeapSort, depending on whether or not array 
	 * 				will be reversed
	 * Return:		i or sib (int)
	 */
	private int maxIndex(Comparable[] array, int i, int sib, boolean reversed) {
		
		if (!reversed) { // Finds max
			if (array[i].compareTo(array[sib]) < 0)
				return sib;
			else
				return i;
		}
		else { // Finds min
			if (array[i].compareTo(array[sib]) < 0)
				return i;
			else
				return sib;		
		}
	}
}