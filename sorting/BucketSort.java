/* Author: 		   	Jourdan Bul-lalayao
 * Class: 		   	BucketSort
 * Purpose: 	   	Performs bucket sort on an int array between specified indices
 * 				   	User also has option to sort the array in reverse order.
 * Helper class:	LLNode
 */

public class BucketSort {
	
	/* Method: 		bucketSort
	 * Arguments:	array, lowindex, highindex, reversed
	 */
	public void bucketSort(int[] array, int lowindex, int highindex,
			boolean reversed) {
		
		int buckets = (highindex - lowindex + 1) / 2;
		LLNode[] C = new LLNode[buckets];
		int min, max, interval;
		min = max = array[0];
		
		int curr, i, j;
		curr = i = j = 0;
		
		// Initializes each linked list index
		for (i = 0; i < buckets; i++) {
			C[i] = new LLNode(-1, null);
		}
		
		// Finds min and max in array
		for (i = lowindex; i <= highindex; i++) {
			curr = array[i];
			if (max < curr)
				max = curr;
			if (min > curr)
				min = curr;
		}
		
		interval = (max - min) / buckets;
		interval = Math.abs(interval);
		
		// Bucket sort
		LLNode val, temp;
		for (i = lowindex; i <= highindex; i++) {
			j = Math.abs(array[i]) / (interval + 1);
			
			// Ensures last element goes into last bucket
			if (j >= buckets)
				j = buckets - 1;
			
			// Inserts first element for linked list index
			if (C[j].next() == null) {
				val = new LLNode(array[i], null);
				C[j].setNext(val);
			}
			
			else {
				temp = C[j];
				
				if (!reversed) { // Doesn't reverse array
					while (temp.next() != null && temp.next().num() < array[i]) {
						temp = temp.next();
					}
					
					// End of list, so append element at tail of list
					if (temp.next() == null) {
						val = new LLNode(array[i], null);
						temp.setNext(val);
					}
					
					// Insert element inside list
					else {
						val = new LLNode(array[i], temp.next());
						temp.setNext(val);
					}
				}
				
				else { // Reverse the array
					while (temp.next() != null && temp.next().num() > array[i]) {
						temp = temp.next();
					}
					
					// End of list, so drop element at tail of list
					if (temp.next() == null) {
						val = new LLNode(array[i], null);
						temp.setNext(val);						
					}
					
					// Insert element inside list
					else {
						val = new LLNode(array[i], temp.next());
						temp.setNext(val);						
					}
				}
			}
		}
	
		// Transfer bucket elements into array
		j = lowindex;
		if (!reversed) { // Dont reverse array
			for (i = 0; i < buckets; i++) {
				while (C[i].next() != null) {
					array[j] = C[i].next().num();
					C[i] = C[i].next();
					j++;
				}
			}		
		}
		
		else { // Reverse the array
			for (i = buckets - 1; i >= 0; i--) {
				while (C[i].next() != null) {
					array[j] = C[i].next().num();
					C[i] = C[i].next();
					j++;
				}
			}				
		}
	}
}
