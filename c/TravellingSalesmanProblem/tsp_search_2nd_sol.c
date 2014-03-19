/* Author:   Jourdan Bul-lalayao (TSP Solution #2)
 * File:     tsp_search_2nd_sol.c
 * Purpose:  Use a parallel iterative depth-first search to solve an 
 *           instance of the travelling salesman problem. Charitable threads
 *           donate to threads that are out of work.
 * Input:    From a user-specified file, the number of cities
 *           followed by the costs of travelling between the
 *           cities organized as a matrix:  the cost of
 *           travelling from city i to city j is the ij entry.
 * Output:   The best tour found by the program and the cost
 *           of the tour.
 * Compile:  gcc -g -Wall -o tsp_search_2nd_sol tsp_search_2nd_sol.c -lpthread
 * Usage:    ./tsp_search_2nd_sol <number of threads> <matrix_file>
 *
 * Notes:
 * 1.  Weights and cities are non-negative ints.
 * 2.  Program assumes the cost of travelling from a city to
 *     itself is zero, and the cost of travelling from one
 *     city to another city is positive.
 * 3.  Note that costs may not be symmetric:  the cost of travelling
 *     from A to B, will, in general, be different from the cost
 *     of travelling from B to A.
 * 4.  Salesperson's home town is 0.
 * 5.  This version uses a linked list for the stack.
 * 6.  Occasionally runs program correctly and gives correct answer.
 *     But it still gives a segmentation fault roughly 50% of the time.
 */
#include <stdio.h>
#include <stdlib.h>
#include <pthread.h>
 
const int INFINITY = 1000000;
const int NO_CITY = -1;
const int FALSE = 0;
const int TRUE = 1;
const int MAX_THREADS = 64;
 
typedef int city_t;
typedef int weight_t;
 
typedef struct {
   city_t* cities;
   int count;
   weight_t cost;
   weight_t size;
} tour_t;
 
typedef struct stack_struct {
   tour_t* tour_p;               /* Partial tour             */
   city_t  city;                 /* City under consideration */
   weight_t cost;                /* Cost of going to city    */
   struct stack_struct* next_p;  /* Next record on stack     */
   weight_t size;                /* Size of stack            */
} stack_elt_t;
 
/* Global variables */
int             thread_count;
int             n;
weight_t*       mat;
tour_t          best_tour;
pthread_mutex_t mutex;
pthread_mutex_t term_mutex;
pthread_cond_t  term_cond_var;
int             threads_in_cond_wait = 0;
stack_elt_t*    new_stack = NULL;
 
/*------------------------------------------------------------------*/
void Usage(char* prog_name);
void Read_mat(FILE* mat_file);
void Print_mat(void);
 
void Assign_partial_tours(int* partial_tour_count, city_t* first_final_city,
    city_t* last_final_city, long my_rank);
void Initialize_tour(tour_t* tour_p);
void *Search(void* rank);
int  Terminated(stack_elt_t** my_stack, weight_t size);
void Print_tour(tour_t* tour_p, char* title);
void Check_best_tour(city_t city, tour_t* tour_p, weight_t* local_best_cost);
int  Feasible(city_t city, city_t nbr, tour_t* tour_p, weight_t local_best_cost);
void Split_stack(stack_elt_t** my_stack);
int  Visited(city_t nbr, tour_t* tour_p);
void Push(tour_t* tour_p, city_t city, weight_t cost, stack_elt_t** stack_p, 
    weight_t size);
tour_t* Dup_tour(tour_t* tour_p);
void Pop(tour_t** tour_pp, city_t* city_p, weight_t* cost_p, 
    stack_elt_t** stack_p, weight_t* size_p);
int  Empty(stack_elt_t* stack);
 
 
/*------------------------------------------------------------------*/
int main(int argc, char* argv[]) {
   long       thread;
   pthread_t* thread_handles;
   FILE*      mat_file;
 
   if (argc != 3) Usage(argv[0]);
   thread_count = strtol(argv[1], NULL, 10);
   mat_file = fopen(argv[2], "r");
 
   if (mat_file == NULL) {
      fprintf(stderr, "Can't open %s\n", argv[2]);
      Usage(argv[0]);
   }
 
   Read_mat(mat_file);
   fclose(mat_file);
#  ifdef DEBUG
   Print_mat();
#  endif   
 
   if (thread_count <= 0 || thread_count > MAX_THREADS || thread_count >= n)
      Usage(argv[0]);
 
   thread_handles = malloc(thread_count*sizeof(pthread_t));
   pthread_mutex_init(&mutex, NULL);
   pthread_mutex_init(&term_mutex, NULL);
   pthread_cond_init(&term_cond_var, NULL);
 
   Initialize_tour(&best_tour);
   best_tour.cost = INFINITY;
 
   for (thread = 0; thread < thread_count; thread++)
      pthread_create(&thread_handles[thread], NULL, 
         Search, (void*) thread);
 
   for (thread = 0; thread < thread_count; thread++)
      pthread_join(thread_handles[thread], NULL); 
 
   Print_tour(&best_tour, "Best tour");
   printf("Cost = %d\n", best_tour.cost);
 
   pthread_mutex_destroy(&mutex);
   pthread_mutex_destroy(&term_mutex);
   pthread_cond_destroy(&term_cond_var);
   free(best_tour.cities);
   free(thread_handles);
   free(mat);
   return 0;
}  /* main */
 
 
/*------------------------------------------------------------------
 * Function:            Search
 * Purpose:             Search for an optimal tour using threads
 * In args:             rank
 * Global vars in:      mat, n
 * Global vars in/out:  best_tour
 * Return:              NULL
 */
void *Search(void* rank) {
   long         my_rank = (long) rank; /* Use long in case of 64-bit system */
   int          partial_tour_count;
   city_t       nbr;
   city_t       city, first_final_city, last_final_city;
   weight_t     cost;
   weight_t     size;
   weight_t     local_best_cost = INFINITY;
   tour_t*      tour_p;
   stack_elt_t* stack_p;
 
   // Initializes final cities for partial tours of each thread */
   Assign_partial_tours(&partial_tour_count, &first_final_city, &last_final_city, my_rank);
 
   tour_p = malloc(sizeof(tour_t));
   Initialize_tour(tour_p);
   /* Don't Push the first node, since Push duplicates */
   stack_p = malloc(sizeof(stack_elt_t));
   stack_p->tour_p = tour_p;
   stack_p->city = 0;
   stack_p->cost = 0;
   stack_p->size = 0;
   stack_p->next_p = NULL;
 
   while (!Terminated(&stack_p, size)) {
      Pop(&tour_p, &city, &cost, &stack_p, &size);
      tour_p->cities[tour_p->count] = city; 
      tour_p->cost += cost;
      tour_p->count++;
      if (tour_p->count == n) {
         size--;
         Check_best_tour(city, tour_p, &local_best_cost);
      }
      else {
         /* Makes sure that partial tours check from each threads' final cities only */
         if (tour_p->count == 1) {
            for (nbr = last_final_city; nbr >= first_final_city; nbr--) {
               size++;
               Push(tour_p, nbr, mat[n*city+nbr], &stack_p, size); 
            }
         }
         else { 
        for (nbr = n-1; nbr > 0; nbr--) 
               if (Feasible(city, nbr, tour_p, local_best_cost)) 
                  Push(tour_p, nbr, mat[n*city+nbr], &stack_p, size); 
         }
      }
      /* Push duplicates the tour.  So it needs to be freed */
      free(tour_p->cities);
      free(tour_p);
   }  /* while */
   return NULL;
}  /* Search */
 
 
/*------------------------------------------------------------------
 * Function:       Split_stack
 * Purpose:        Splits the thread's stack so another thread without 
 *                 work can complete work
 * In args:        my_stack
 * Global vars in: new_stack
 */
void Split_stack(stack_elt_t** my_stack) {
   struct stack_struct* head_p = *my_stack;
   struct stack_struct* curr_p = head_p->next_p; /* Starts at tour 1 of my_stack */
   struct stack_struct* temp_p = NULL;
   struct stack_struct* pred_p = head_p;
   int i = 1; /* Index of curr_p */
 
   while (curr_p != NULL) {
      if (i % 2 != 0) { // Alternately pushes tours onto new_stack 
         Push(curr_p->tour_p, curr_p->city, curr_p->cost, &new_stack, curr_p->size);
         temp_p = curr_p;
         pred_p->next_p = curr_p->next_p;
         curr_p = curr_p->next_p;
 
         /* Deletes node from my_stack that was pushed onto new stack */
         free(temp_p->tour_p->cities);
         free(temp_p->tour_p);
         free(temp_p); 
      } 
      else {
         pred_p = curr_p;
         curr_p = curr_p->next_p;
      }
      i++;
   }
} /* Split_stack */
 
 
/*------------------------------------------------------------------
 * Function:    Pop
 * Purpose:     Remove the top node from the stack and return it
 * In/out arg:  stack_pp:  on input the current stack, on output
 *                 the stack with the top record removed
 * Out args:    tour_pp:  the tour in the top stack node
 *              city_p:   the city in the top stack node
 *              cost_p:   the cost of visiting the city
 */
void Pop(tour_t** tour_pp, city_t* city_p, weight_t* cost_p, 
    stack_elt_t** stack_pp, weight_t* size_p) {
   stack_elt_t* stack_p = *stack_pp;
   *tour_pp = stack_p->tour_p;
   *city_p = stack_p->city;
   *cost_p = stack_p->cost;
   *size_p = stack_p->size;
   *stack_pp = stack_p->next_p;
   free(stack_p);
}  /* Pop */
 
 
/*------------------------------------------------------------------
 * Function:    Push
 * Purpose:     Add a new node to the top of the stack
 * In args:     tour_p, city, cost
 * In/out arg:  stack_pp:  on input pointer to current stack
 *                 on output pointer to stack with new top record
 * Note:        The input tour is duplicated before being pushed
 *              so that the existing tour can be used in the 
 *              Search function
 */
void Push(tour_t* tour_p, city_t city, weight_t cost, 
      stack_elt_t** stack_pp, weight_t size) {
   stack_elt_t* temp = malloc(sizeof(stack_elt_t));
   temp->tour_p = Dup_tour(tour_p);
   temp->city = city;
   temp->cost = cost;
   temp->size = size;
   temp->next_p = *stack_pp;
   *stack_pp = temp;
}  /* Push */
 
 
/*------------------------------------------------------------------
 * Function:           Terminated
 * Purpose:            Determines whether or not threads need to quit 
 *                     or if threads need to split their stacks to give 
 *                     to other threads that need work. Uses mutexes.
 * In args:            my_stack, my_stack_size
 * Global vars in/out: term_cond_var, term_mutex, threads_in_cond_wait,
 *             new_stack
 */
int Terminated(stack_elt_t** my_stack, weight_t my_stack_size) {
 
   if (my_stack_size >= 2 && threads_in_cond_wait > 0 && new_stack == NULL) {
      pthread_mutex_trylock(&term_mutex);
      if (threads_in_cond_wait > 0 && new_stack == NULL) {
         Split_stack(my_stack);
         pthread_cond_signal(&term_cond_var);
      }
      pthread_mutex_unlock(&term_mutex);
      return 0; /* Terminated = False; don't quit */
   } else if (!Empty(*my_stack)) { /* Stack not empty, keep working */
      return 0; /* Terminated = false; don't quit */
   } else { /* My stack is empty */
      pthread_mutex_lock(&term_mutex);
      if (threads_in_cond_wait == thread_count-1) {  /* Last thread running */
         threads_in_cond_wait++;
         pthread_cond_broadcast(&term_cond_var);
         pthread_mutex_unlock(&term_mutex);
         return 1; /* Terminated = true; quit */
      } else { /* Other threads still working, wait for work */
         threads_in_cond_wait++;
         while (pthread_cond_wait(&term_cond_var, &term_mutex) != 0);
         /* We've been awakened */
         if (threads_in_cond_wait < thread_count || new_stack != NULL) { /* We got work */
            *my_stack = new_stack;
            new_stack = NULL;
            threads_in_cond_wait--;
            pthread_mutex_unlock(&term_mutex);
            return 0; /* Terminated = false */
         } else { /* All threads done */
            pthread_mutex_unlock(&term_mutex);
            return 1; /* Terminated = true; quit */
         }
      } /* else wait for work */
   } /* else my_stack is empty */
} /* Terminated */
 
 
/*------------------------------------------------------------------
 * Function:        Feasible
 * Purpose:         Check whether nbr could possibly lead to a better
 *                  solution if it is added to the current tour.  The
 *                  functions checks whether nbr has already been visited
 *                  in the current tour, and, if not, whether adding the
 *                  edge from the current city to nbr will result in
 *                  a cost less than the current best cost.
 * In args:         city, nbr, tour_p, local_best_cost
 * Global vars in:  mat, n
 * Return:          TRUE if the nbr can be added to the current tour.
 *                  FALSE otherwise
 */
int Feasible(city_t city, city_t nbr, tour_t* tour_p, weight_t local_best_cost) {
   if (!Visited(nbr, tour_p) && 
        tour_p -> cost + mat[n*city + nbr] < local_best_cost)
      return TRUE;
   else
      return FALSE;
}  /* Feasible */
 
 
/*------------------------------------------------------------------
 * Function:            Check_best_tour
 * Purpose:             Determine whether the current n-city tour will be
 *                      better than the thread's local best tour.  If so, 
 *                      update threads local best tour. If this local best
 *                      tour is better than the global best tour, then update
 *                      the global best tour. Uses mutex.
 * In args:             city, tour_p, local_best_cost
 * Out args:            local_best_cost
 * Global vars in:      mat, n, mutex
 * Global vars in/out:  best_tour
 */
void Check_best_tour(city_t city, tour_t* tour_p, weight_t* local_best_cost) {
   int i;
 
   if (tour_p->cost + mat[city*n + 0] < *local_best_cost) {
      *local_best_cost = tour_p->cost + mat[city*n + 0];
      pthread_mutex_lock(&mutex);
      if (*local_best_cost < best_tour.cost) {
         for (i = 0; i < tour_p->count; i++)
            best_tour.cities[i] = tour_p->cities[i];
         best_tour.cities[n] = 0;
         best_tour.count = n+1;
         best_tour.cost = tour_p->cost + mat[city*n + 0];
      }
      pthread_mutex_unlock(&mutex);
   }
}  /* Check_best_tour */
 
 
/*------------------------------------------------------------------
 * Function:       Assign_partial_tours
 * Purpose:        Assigns partial tour values for each thread
 * In/out args:    partial_tour_count, first_final_city, last_final_city
 * In args:        my_rank
 * Global vars in: n, thread_count
 */
void Assign_partial_tours(int* partial_tour_count, 
    city_t* first_final_city, city_t* last_final_city, long my_rank) {
 
   int quotient, remainder;
 
   quotient  = (n-1) / thread_count;
   remainder = (n-1) % thread_count;
 
   if (my_rank < remainder) {
      *partial_tour_count = quotient+1;
      *first_final_city = my_rank * (*partial_tour_count) + 1;
   }
   else {
      *partial_tour_count = quotient;
      *first_final_city = my_rank * (*partial_tour_count) + remainder + 1;
   }
   *last_final_city = *first_final_city + (*partial_tour_count) - 1;   
} /* Assign_partial_tours */
 
 
/*------------------------------------------------------------------
 * Function:  Dup_tour
 * Purpose:   Create a duplicate of the tour referenced by tour_p:
 *            used by the Push function
 * In arg:    tour_p
 * Ret val:   Pointer to the copy of the tour
 */
tour_t* Dup_tour(tour_t* tour_p) {
   int i;
   tour_t* temp_p = malloc(sizeof(tour_t));
   temp_p->cities = malloc(n*sizeof(city_t));
   for (i = 0; i < n; i++)
      temp_p->cities[i] = tour_p->cities[i];
   temp_p->cost = tour_p->cost;
   temp_p->count = tour_p->count;
   return temp_p;
}  /* Dup_tour */
 
 
/*------------------------------------------------------------------
 * Function:  Empty
 * Purpose:   Determine whether the stack is empty
 * In arg:    stack_p
 * Ret val:   TRUE if stack is empty, FALSE otherwise
 */
int Empty(stack_elt_t* stack_p) {
   if (stack_p == NULL)
      return TRUE;
   else
      return FALSE;
}  /* Empty */
 
 
/*------------------------------------------------------------------
 * Function:  Print_tour
 * Purpose:   Print a tour
 * In args:   All
 */
void Print_tour(tour_t* tour_p, char* title) {
   int i;
 
   printf("%s:\n", title);
   for (i = 0; i < tour_p->count; i++)
      printf("%d ", tour_p->cities[i]);
   printf("\n\n");
}  /* Print_tour */
 
 
/*------------------------------------------------------------------
 * Function:   Visited
 * Purpose:    Use linear search to determine whether nbr has already
 *             bee visited on the current tour.
 * In args:    All
 * Return val: TRUE if nbr has already been visited.  
 *             FALSE otherwise
 */
int Visited(city_t nbr, tour_t* tour_p) {
   int i;
 
   for (i = 0; i < tour_p->count; i++)
      if ( tour_p->cities[i] == nbr ) return TRUE;
   return FALSE;
}  /* Visited */
 
 
/*------------------------------------------------------------------
 * Function:  Usage
 * Purpose:   Inform user how to start program and exit
 * In arg:    prog_name
 */
void Usage(char* prog_name) {
   fprintf(stderr, "usage: %s <number of threads> <matrix file>\n", prog_name);
   fprintf(stderr, "Number of threads must be less than matrix order\n");
   exit(0);
}  /* Usage */
 
 
/*------------------------------------------------------------------
 * Function:         Read_mat
 * Purpose:          Read in the number of cities and the matrix of costs
 * In arg:           mat_file
 * Global vars out:  mat, n
 */
void Read_mat(FILE* mat_file) {
   int i, j;
 
   fscanf(mat_file, "%d", &n);
   mat = malloc(n*n*sizeof(weight_t));
 
   for (i = 0; i < n; i++)
      for (j = 0; j < n; j++)
         fscanf(mat_file, "%d", &mat[n*i+j]);
}  /* Read_mat */
 
 
/*------------------------------------------------------------------
 * Function:        Print_mat
 * Purpose:         Print the number of cities and the matrix of costs
 * Global vars in:  mat, n
 */
void Print_mat(void) {
   int i, j;
 
   printf("Order = %d\n", n);
   printf("Matrix = \n");
   for (i = 0; i < n; i++) {
      for (j = 0; j < n; j++)
         printf("%2d ", mat[i*n+j]);
      printf("\n");
   }
   printf("\n");
}  /* Print_mat */
 
 
/*------------------------------------------------------------------
 * Function:    Initialize_tour
 * Purpose:     Initialize a tour with no visited cities
 * In/out arg:  tour_p
 */
void Initialize_tour(tour_t* tour_p) {
   int i;
 
   tour_p->cities = malloc((n+1)*sizeof(city_t));
   for (i = 0; i <= n; i++) {
      tour_p->cities[i] = NO_CITY;
   }
   tour_p->cost = 0;
   tour_p->count = 0;
   tour_p->size = 0;
}  /* Initialize_tour */