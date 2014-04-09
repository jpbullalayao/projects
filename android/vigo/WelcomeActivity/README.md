# Vigo App V1

# Notes
  * The class com.vigo.vigoapp.model.DatabaseConfigUtl is very important.  It is a "workaround" for ORMLite that greatly improves performance.  Whenever a change is made to a Data class (LogData.class, etc.) that requires a change in the database layout, run this class locally (not on the Android device).  It will regenerate the res/raw/ormlite_config.txt file.