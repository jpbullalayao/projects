// Author: Jourdan Bul-lalayao
// Purpose: Small program that logs generated data into a text file
 
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
 
public class DataLog {
 
    private int numBlinks;
     
    public DataLog() {
        numBlinks = 0;
    }
     
    // Function: gen()
    // Purpose: Generate data to be logged  
    public void gen() {
        numBlinks++;
    }
     
    // Function: log()
    // Purpose: Log data into a .txt file
    public void log() {
         
        // Make file for # of blinks
        File blinkLog = new File("numBlinks.txt");
         
        // Try/catch statement necessary for FileWriter
        try {
            BufferedWriter writer = new BufferedWriter(new FileWriter(blinkLog));
             
            // If we don't use String.valueOf, it will output string equivalent of numBlinks.
            writer.write(String.valueOf(numBlinks)); 
             
            // ALWAYS CLOSE THE WRITER, file will be empty if you don't close it
            writer.close();
        } catch (IOException e) {
            System.out.println("Unable to write to file!");
            e.printStackTrace();
        }
    }
     
    public static void main(String[] args) {
         
        DataLog log = new DataLog();
         
        // Generate data
        for (int i = 0; i < 100; i++) {
            log.gen();
        }
         
        // Log data into txt file
        log.log();
    }
}