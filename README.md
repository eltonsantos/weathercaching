weathercaching
==============

WeatherCaching App + Code Created at NASA Space Apps 2013

See: http://spaceappschallenge.org/project/weathercaching/

The code in the PHP folder is the web app created uding CakePHP which stores observations to the data base. This data can be browsed at http://weatherboxing-theomccaie.rhcloud.com/obs

The file obPoster is a simple html form to mock a observation submitted via a sensor, usful when testting and developing.

PiHome\weathercache is a copy of the folder of scripts on the Rassbery Pi. This includes 3rd party libs. The "scan" script is used to find the address/name of the SensorTag the is doing the monitoring. This looks like "BC:6A:29:C3:A1:27" and needs to be set in the script weathercache.  weathercache is the main script that runs continuously and takes the SensorTag observations and puts them in QR code which redirects to the web app with the data send in the query string.



