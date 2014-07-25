#Autodesk View and Data API workflow sample in PHP

##Description

*This sample is part of the [Developer-Autodesk/Autodesk-View-and-Data-API-Samples](https://github.com/Developer-Autodesk/autodesk-view-and-data-api-samples) repository.*

This is a sample in PHP demonstrating the complete Autodesk View and Data API workflow:

* Upload a file to bucket
* Start translation
* Monitor translation progress
* Load it in Viewer. 

##Dependencies

This sample requires PHP 5.4 or higher, please be sure to open the curl module

##Setup/Usage Instructions

* Get your consumer key and secret key from http://developer.autodesk.com
* Set your keys in server / config.php for CLIENT_ID and SECRET

Installation Notes for PHP on Mac
---------------------------------
Set up the built-in Apache server as described in
https://discussions.apple.com/docs/DOC-3083


Installation Notes for PHP on Windows(With XAMPP)
---------------------------------

* Download and install XAMPP (https://www.apachefriends.org/index.html)
* Fix the SSL issue (http://stackoverflow.com/questions/17478283/paypal-access-ssl-certificate-unable-to-get-local-issuer-certificate)
* Copy /server and /client to c:\xampp\hotdocs
* Launch XAMPP apache server and browse to http://localhost/client/index.html


## License

This sample is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT). Please see the [LICENSE](LICENSE) file for full details.

##Written by 

Originally created by galikaixin@iuxlabs.com

