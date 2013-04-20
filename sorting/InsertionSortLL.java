/* Author: 		   Jourdan Bul-lalayao
 * Class: 		   InsertionSortLL
 * Purpose: 	   Performs insertion sort on a linked list between specified indices.
 * 				   User also has option to sort the list in reverse order.
 * Helper class:   LLNode
 * Helper methods: myInsertionSortLL
 */

public class InsertionSortLL {
	
	/* Method: 		insertionSortLL
	 * Arguments: 	list, reversed
	 * Return:	  	A (LLNode)
	 */
	public LLNode insertionSortLL(LLNode list, boolean reversed) {		
		
		LLNode A = myInsertionSortLL(list, reversed);
		return A;
	}
	
	
	/* Method:		myInsertionSortLL
	 * Arguments:	list, reversed
	 * Return:		list (LLNode)
	 */
	private LLNode myInsertionSortLL(LLNode list, boolean reversed) {
	
		LLNode i, j, head, temp;
		i = j = head = list;
		
		if (!reversed) { // Don't reverse the array
			
			if (i.next() != null) {
				while (i.next() != null) {
					if (i.elem().compareTo(i.next().elem()) > 0) {
						temp = i.next();
						i.setNext(temp.next());
	
						j = head;
						while (j.next() != null) {
							if (j == head) {
								if (j.elem().compareTo(temp.elem()) > 0) {
									temp.setNext(j);
									head = temp;
									i = head;
									break;
								}
	
								else {
									if (j.next().elem().compareTo(temp.elem()) > 0) {
										j.next().setNext(temp.next());
										j.setNext(temp);
										temp.setNext(j.next());
										i = temp;
										break;
									}
	
									else {
										if (j.next().elem()
												.compareTo(temp.elem()) < 0) {
											j.next().setNext(temp.next());
											j.setNext(temp);
											temp.setNext(j.next());
											i = temp;
											break;
										}
									}
								}
							}
	
							else {
								if (j.next().elem().compareTo(temp.elem()) > 0) {
									j.next().setNext(temp.next());
									j.setNext(temp);
									temp.setNext(j.next());
									i = temp;
									break;
								}
							}
							j = j.next();
						}
					}
					if (i.next() != null)
						i = i.next();
				}
				list = head;
			}
		}
		
		else { // Reverse the array
			while (i.next() != null) {
				if (i.elem().compareTo(i.next().elem()) < 0) {
					temp = i.next();
					i.setNext(temp.next());
	
					j = head;
					while (j.next() != null) {
						if (j == head) {
							if (j.elem().compareTo(temp.elem()) < 0) {
								temp.setNext(j);
								head = temp;
								i = head;
								break;
							}
							
							else {
								if (j.next().elem().compareTo(temp.elem()) < 0) {
									j.next().setNext(temp.next());
									j.setNext(temp);
									temp.setNext(j.next());
									i = temp;
									break;
								}
	
								else {
									if (j.next().elem().compareTo(temp.elem()) > 0) {
										j.next().setNext(temp.next());
										j.setNext(temp);
										temp.setNext(j.next());		
										i = temp;
										break;
									}
								}
							}
						}
						
						else {
							if (j.next().elem().compareTo(temp.elem()) < 0) {
								j.next().setNext(temp.next());
								j.setNext(temp);
								temp.setNext(j.next());		
								i = temp;
								break;
							}
						}
						j = j.next();
					}
				}
				if (i.next() != null)
					i = i.next();
			}
			list = head;			
		}
		return list;
	}
}