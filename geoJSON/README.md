This is a crude postcode insertion for geoJSON files for Australia. 
It can probably be modified for other countries though.

To make this script, you will need to download the geoJSON boundaries files.
You will also need to have postcodes loaded in a mysql database.

The, change the reading and writing filename variables to reflect the in and the out files.


Merge file is called: _postcode_insert.php


Resources required:

This is for inserting postcodes, and to be used in conjunction with 

Boundaries
https://github.com/stephenmuss/suburb-boundaries-geojson

Postcodes
https://github.com/vrdriver/australianpostcodes


* To speed up this process, it is recommended to add indexes to your search fields.
In my case, town and state.

This allowed for passing over 2600 postcodes within the php timeout limit.
	


# suburb-boundaries-geojson
THIS IS AN UPDATE TO THE ORIGINAL DATASET 

It now has the postcodes inserted in to the geoJSON files.

Other info:
This is the 2011 Australian state suburb digitial boundaries converted to GeoJSON format.

Suburbs have been separated into separate files for each state/territory and are sorted alphabetically.

The original data can be found as _State Suburbs ASGS Non ABS Structures Ed 2011 Digital Boundaries_
on the [Australian Bureau of Statistics website](http://www.abs.gov.au/AUSSTATS/abs@.nsf/DetailsPage/1270.0.55.003July%202011?OpenDocument).

All data is licensed under Creative Commons Attribution 2.5 Australia ([CC BY 2.5 AU](http://creativecommons.org/licenses/by/2.5/au/)).


Original boundary source: https://github.com/stephenmuss/suburb-boundaries-geojson
