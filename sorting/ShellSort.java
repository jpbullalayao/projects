/* Author: 		   Jourdan Bul-lalayao
 * Class: 		   ShellSort
 * Purpose: 	   Performs shell sort on a Comparable array between specified indices
 * 				   using Hibbard's increments. User also has option to sort the array 
 * 				   in reverse order.
 */

public class ShellSort {

	/* Method: 		shellSort
	 * Arguments:	array, lowindex, highindex, reversed
	 */
	public void shellSort(Comparable[] array, int lowindex, int highindex,
			boolean reversed) {
	
		Comparable curr;
		int length = highindex - lowindex + 1;
		int i, j, increment;
		i = j = increment = 1;
		
		while (true) {
			if (increment * 2 - 1 < length)
				increment *= 2;
			else
				break;
		}
		
		increment--;
		
		if (!reversed) {
			for (i = increment; i <= highindex && increment >= 1; i += increment) {
				curr = array[i];
				for (j = i - increment; j >= lowindex && array[j].compareTo(curr) > 0; j -= increment)
					array[j + increment] = array[j];
				array[j + increment] = curr;
	
				if (i + increment > highindex) {
					increment /= 2;
					i = 0;
				}
			}
		}
		
		else { // Reverse the array
			for (i = increment; i <= highindex && increment >= 1; i += increment) {
				curr = array[i];
				for (j = i - increment; j >= lowindex && array[j].compareTo(curr) < 0; j -= increment)
					array[j + increment] = array[j];
				array[j + increment] = curr;
	
				if (i + increment > highindex) {
					increment /= 2;
					i = 0;
				}
			}
		}
	}
}