#Autodesk View and Data API workflow sample in PHP


##Description

*This sample is part of the [Developer-Autodesk/Autodesk-View-and-Data-API-Samples](https://github.com/Developer-Autodesk/autodesk-view-and-data-api-samples) repository.*

This is a sample in PHP demonstrating the complete Autodesk View and Data API workflow:

* Upload a file to bucket
* Start translation
* Monitor progress
* Load it in Viewer. 

##Dependencies

This sample requires PHP 5.4 or higher, please be sure to open the curl module

##Setup/Usage Instructions

* Get your consumer key and secret key from http://developer.autodesk.com
* Set your keys in server / config.php for CLIENT_ID and SECRET
* Modify http address client / javascript / global.js under server-side path according to your server configuration
* Change the path of info.html in server/index.php(around line 161)

Installation Notes for PHP on Mac
---------------------------------
Set up the built-in Apache server as described in
https://discussions.apple.com/docs/DOC-3083


Installation Notes for PHP on Windows(With XAMPP)
---------------------------------

1. Download and install XAMPP (https://www.apachefriends.org/index.html)
2. Fix the SSL issue (http://stackoverflow.com/questions/17478283/paypal-access-ssl-certificate-unable-to-get-local-issuer-certificate)
3. Copy /server and .client to c:\xampp\hotdocs
4. Launch XAMPP apache server and broswer to http://localhost/client/index.html




##Written by 

Originally created by galikaixin@iuxlabs.com

